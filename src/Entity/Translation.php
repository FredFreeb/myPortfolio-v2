<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Une clé de traduction et toutes ses valeurs par locale.
 *
 * Les valeurs sont stockées dans un champ JSON {locale: contenu} pour éviter
 * une table de jointure. Le locale de référence (source de vérité éditoriale)
 * est défini par self::REFERENCE_LOCALE.
 */
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
#[ORM\Table(name: 'translation')]
#[ORM\UniqueConstraint(name: 'uniq_translation_key_domain', columns: ['translation_key', 'domain'])]
#[ORM\Index(name: 'idx_translation_section', columns: ['section'])]
#[ORM\HasLifecycleCallbacks]
class Translation
{
    public const REFERENCE_LOCALE = 'fr';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Clé hiérarchique, ex: "nav.portfolio", "civitalisme.hero.title".
     */
    #[ORM\Column(length: 180)]
    private string $translationKey = '';

    #[ORM\Column(length: 64)]
    private string $domain = 'messages';

    /**
     * Section fonctionnelle (regroupement UI). Ex: "Navigation", "Page d'accueil",
     * "Civitalisme — En-tête". Si null, auto-déduite du préfixe de la clé.
     */
    #[ORM\Column(length: 120, nullable: true)]
    private ?string $section = null;

    /**
     * @var array<string, string> Dictionnaire locale → contenu.
     */
    #[ORM\Column(type: Types::JSON)]
    private array $contents = [];

    /**
     * Aide pour le traducteur (contexte, ton, longueur max…).
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function initializeTimestamps(): void
    {
        $now = new \DateTimeImmutable();
        $this->createdAt ??= $now;
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function touchUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    public function setTranslationKey(string $translationKey): static
    {
        $this->translationKey = $translationKey;
        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    /**
     * Section explicite, ou préfixe déduit de la clé ("civitalisme.hero.title" → "civitalisme.hero").
     */
    public function getEffectiveSection(): string
    {
        if ($this->section !== null && $this->section !== '') {
            return $this->section;
        }
        $pos = strrpos($this->translationKey, '.');
        return $pos === false ? 'divers' : substr($this->translationKey, 0, $pos);
    }

    public function setSection(?string $section): static
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    public function getContent(string $locale, ?string $fallback = self::REFERENCE_LOCALE): ?string
    {
        if (isset($this->contents[$locale]) && $this->contents[$locale] !== '') {
            return $this->contents[$locale];
        }
        if ($fallback !== null && isset($this->contents[$fallback]) && $this->contents[$fallback] !== '') {
            return $this->contents[$fallback];
        }
        return null;
    }

    public function setContent(string $locale, ?string $value): static
    {
        if ($value === null || trim($value) === '') {
            unset($this->contents[$locale]);
        } else {
            $this->contents[$locale] = $value;
        }
        return $this;
    }

    public function hasContentFor(string $locale): bool
    {
        return isset($this->contents[$locale]) && trim($this->contents[$locale]) !== '';
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

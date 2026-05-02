<?php

namespace App\Entity;

use App\Enum\ProjectAudience;
use App\Repository\ProjectUpdateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectUpdateRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ProjectUpdate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: ProjectAudience::class)]
    private ProjectAudience $audience = ProjectAudience::Public;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    private string $title = '';

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le résumé est obligatoire.')]
    private string $summary = '';

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu est obligatoire.')]
    private string $body = '';

    #[ORM\Column(length: 100)]
    private string $statusLabel = 'En construction';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $outcome = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $ctaLabel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ctaUrl = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isFeatured = false;

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    #[ORM\Column(options: ['default' => true])]
    private bool $isPublished = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function initializePublishedAt(): void
    {
        $this->publishedAt ??= new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->title ?: 'Bloc Civitalisme';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAudience(): ProjectAudience
    {
        return $this->audience;
    }

    public function setAudience(ProjectAudience $audience): static
    {
        $this->audience = $audience;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = trim((string) $title);

        return $this;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = trim((string) $summary);

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = trim((string) $body);

        return $this;
    }

    public function getStatusLabel(): string
    {
        return $this->statusLabel;
    }

    public function setStatusLabel(?string $statusLabel): static
    {
        $value = trim((string) $statusLabel);
        $this->statusLabel = '' === $value ? 'En construction' : $value;

        return $this;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(?string $outcome): static
    {
        $value = null === $outcome ? null : trim($outcome);
        $this->outcome = '' === $value ? null : $value;

        return $this;
    }

    public function getCtaLabel(): ?string
    {
        return $this->ctaLabel;
    }

    public function setCtaLabel(?string $ctaLabel): static
    {
        $value = null === $ctaLabel ? null : trim($ctaLabel);
        $this->ctaLabel = '' === $value ? null : $value;

        return $this;
    }

    public function getCtaUrl(): ?string
    {
        return $this->ctaUrl;
    }

    public function setCtaUrl(?string $ctaUrl): static
    {
        $value = null === $ctaUrl ? null : trim($ctaUrl);
        $this->ctaUrl = '' === $value ? null : $value;

        return $this;
    }

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt instanceof \DateTimeImmutable
            ? $publishedAt
            : ($publishedAt ? \DateTimeImmutable::createFromInterface($publishedAt) : new \DateTimeImmutable());

        return $this;
    }
}

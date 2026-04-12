<?php

namespace App\Entity;

use App\Entity\Concerns\SanitizesEntityDataTrait;
use App\Repository\TestimonialRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestimonialRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Testimonial
{
    use SanitizesEntityDataTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $authorName = '';

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $authorRole = null;

    #[ORM\Column(name: 'company', length: 100, nullable: true)]
    private ?string $companyName = null;

    #[ORM\Column(name: 'quote', type: Types::TEXT)]
    private string $content = '';

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $theme = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    #[ORM\Column(options: ['default' => true])]
    private bool $isPublished = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        $this->createdAt ??= new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->authorName ?: 'Témoignage';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorName(): string
    {
        return self::sanitizePlainText($this->authorName) ?? '';
    }

    public function setAuthorName(string $authorName): static
    {
        $this->authorName = self::sanitizePlainText($authorName) ?? '';

        return $this;
    }

    public function getAuthorRole(): ?string
    {
        return self::sanitizePlainText($this->authorRole);
    }

    public function setAuthorRole(?string $authorRole): static
    {
        $this->authorRole = self::sanitizePlainText($authorRole);

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return self::sanitizePlainText($this->companyName);
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = self::sanitizePlainText($companyName);

        return $this;
    }

    public function getContent(): string
    {
        return self::sanitizeLongText($this->content) ?? '';
    }

    public function setContent(string $content): static
    {
        $this->content = self::sanitizeLongText($content) ?? '';

        return $this;
    }

    public function getTheme(): ?string
    {
        return self::sanitizePlainText($this->theme);
    }

    public function setTheme(?string $theme): static
    {
        $this->theme = self::sanitizePlainText($theme);

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Backward-compatibility getters/setters.
     */
    public function getCompany(): ?string
    {
        return $this->getCompanyName();
    }

    public function setCompany(?string $company): static
    {
        return $this->setCompanyName($company);
    }

    public function getQuote(): string
    {
        return $this->getContent();
    }

    public function setQuote(string $quote): static
    {
        return $this->setContent($quote);
    }
}

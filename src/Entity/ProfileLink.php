<?php

namespace App\Entity;

use App\Entity\Concerns\SanitizesEntityDataTrait;
use App\Enum\LinkCategory;
use App\Repository\ProfileLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileLinkRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ProfileLink
{
    use SanitizesEntityDataTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, enumType: LinkCategory::class)]
    private LinkCategory $category = LinkCategory::Network;

    #[ORM\Column(length: 100)]
    private string $title = '';

    #[ORM\Column(length: 140, nullable: true)]
    private ?string $subtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $badge = null;

    #[ORM\Column(length: 30)]
    private string $theme = '';

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $year = null;

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
        return $this->title ?: 'Lien';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): LinkCategory
    {
        return $this->category;
    }

    public function setCategory(LinkCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getTitle(): string
    {
        return self::sanitizePlainText($this->title) ?? '';
    }

    public function setTitle(string $title): static
    {
        $this->title = self::sanitizePlainText($title) ?? '';

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return self::sanitizePlainText($this->subtitle);
    }

    public function setSubtitle(?string $subtitle): static
    {
        $this->subtitle = self::sanitizePlainText($subtitle);

        return $this;
    }

    public function getUrl(): ?string
    {
        return self::sanitizeUrl($this->url);
    }

    public function setUrl(?string $url): static
    {
        $this->url = self::sanitizeUrl($url);

        return $this;
    }

    public function getDescription(): ?string
    {
        return self::sanitizeLongText($this->description);
    }

    public function setDescription(?string $description): static
    {
        $this->description = self::sanitizeLongText($description);

        return $this;
    }

    public function getBadge(): ?string
    {
        return self::sanitizePlainText($this->badge);
    }

    public function setBadge(?string $badge): static
    {
        $value = self::sanitizePlainText($badge);
        $this->badge = null === $value ? null : strtoupper(substr($value, 0, 10));

        return $this;
    }

    public function getTheme(): string
    {
        return self::sanitizePlainText($this->theme) ?? '';
    }

    public function setTheme(string $theme): static
    {
        $this->theme = self::sanitizePlainText($theme) ?? '';

        return $this;
    }

    public function getYear(): ?string
    {
        return self::sanitizePlainText($this->year);
    }

    public function setYear(?string $year): static
    {
        $this->year = self::sanitizePlainText($year);

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
}

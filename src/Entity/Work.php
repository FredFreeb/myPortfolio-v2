<?php

namespace App\Entity;

use App\Entity\Concerns\SanitizesEntityDataTrait;
use App\Repository\WorkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: WorkRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Work
{
    use SanitizesEntityDataTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private string $title = '';

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $clientName = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $roleLabel = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $excerpt = '';

    #[ORM\Column(type: Types::TEXT)]
    private string $description = '';

    #[ORM\Column(length: 255)]
    private string $stackSummary = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $projectUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $repositoryUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $toolsUsed = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isFeatured = false;

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    #[ORM\Column(options: ['default' => true])]
    private bool $isPublished = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    private ?UploadedFile $imageFile = null;

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
        return $this->title ?: 'Projet';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClientName(): ?string
    {
        return self::sanitizePlainText($this->clientName);
    }

    public function setClientName(?string $clientName): static
    {
        $this->clientName = self::sanitizePlainText($clientName);

        return $this;
    }

    public function getRoleLabel(): ?string
    {
        return self::sanitizePlainText($this->roleLabel);
    }

    public function setRoleLabel(?string $roleLabel): static
    {
        $this->roleLabel = self::sanitizePlainText($roleLabel);

        return $this;
    }

    public function getExcerpt(): string
    {
        return self::sanitizeLongText($this->excerpt) ?? '';
    }

    public function setExcerpt(string $excerpt): static
    {
        $this->excerpt = self::sanitizeLongText($excerpt) ?? '';

        return $this;
    }

    public function getDescription(): string
    {
        return self::sanitizeLongText($this->description) ?? '';
    }

    public function setDescription(string $description): static
    {
        $this->description = self::sanitizeLongText($description) ?? '';

        return $this;
    }

    public function getStackSummary(): string
    {
        return self::sanitizePlainText($this->stackSummary) ?? '';
    }

    public function setStackSummary(string $stackSummary): static
    {
        $this->stackSummary = self::sanitizePlainText($stackSummary) ?? '';

        return $this;
    }

    public function getProjectUrl(): ?string
    {
        return self::sanitizeUrl($this->projectUrl);
    }

    public function setProjectUrl(?string $projectUrl): static
    {
        $this->projectUrl = self::sanitizeUrl($projectUrl);

        return $this;
    }

    public function getRepositoryUrl(): ?string
    {
        return self::sanitizeUrl($this->repositoryUrl);
    }

    public function setRepositoryUrl(?string $repositoryUrl): static
    {
        $this->repositoryUrl = self::sanitizeUrl($repositoryUrl);

        return $this;
    }

    public function getImagePath(): ?string
    {
        return self::normalizeAssetPath($this->imagePath);
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = self::normalizeAssetPath($imagePath);

        return $this;
    }

    public function getToolsUsed(): ?string
    {
        return self::sanitizePlainText($this->toolsUsed);
    }

    public function setToolsUsed(?string $toolsUsed): static
    {
        $this->toolsUsed = self::sanitizePlainText($toolsUsed);

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

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    public function setImageFile(?UploadedFile $imageFile): static
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}

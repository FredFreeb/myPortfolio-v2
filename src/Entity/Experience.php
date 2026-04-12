<?php

namespace App\Entity;

use App\Entity\Concerns\SanitizesEntityDataTrait;
use App\Repository\ExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Experience
{
    use SanitizesEntityDataTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'company', length: 100)]
    private string $companyName = '';

    #[ORM\Column(length: 100)]
    private string $role = '';

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 50)]
    private string $period = '';

    #[ORM\Column(type: Types::TEXT)]
    private string $summary = '';

    #[ORM\Column(name: 'logo_path', length: 255, nullable: true)]
    private ?string $logoPath = null;

    #[ORM\Column(name: 'logo_monogram', length: 10, nullable: true)]
    private ?string $monogram = null;

    #[ORM\Column(length: 30)]
    private string $theme = '';

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    #[ORM\Column(options: ['default' => true])]
    private bool $isPublished = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    private ?UploadedFile $logoFile = null;

    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        $this->createdAt ??= new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->companyName ?: 'Expérience';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): string
    {
        return self::sanitizePlainText($this->companyName) ?? '';
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = self::sanitizePlainText($companyName) ?? '';

        return $this;
    }

    public function getRole(): string
    {
        return self::sanitizePlainText($this->role) ?? '';
    }

    public function setRole(string $role): static
    {
        $this->role = self::sanitizePlainText($role) ?? '';

        return $this;
    }

    public function getLocation(): ?string
    {
        return self::sanitizePlainText($this->location);
    }

    public function setLocation(?string $location): static
    {
        $this->location = self::sanitizePlainText($location);

        return $this;
    }

    public function getStatus(): ?string
    {
        return self::sanitizePlainText($this->status);
    }

    public function setStatus(?string $status): static
    {
        $this->status = self::sanitizePlainText($status);

        return $this;
    }

    public function getPeriod(): string
    {
        return self::sanitizePlainText($this->period) ?? '';
    }

    public function setPeriod(string $period): static
    {
        $this->period = self::sanitizePlainText($period) ?? '';

        return $this;
    }

    public function getSummary(): string
    {
        return self::sanitizeLongText($this->summary) ?? '';
    }

    public function setSummary(string $summary): static
    {
        $this->summary = self::sanitizeLongText($summary) ?? '';

        return $this;
    }

    public function getLogoPath(): ?string
    {
        return self::normalizeAssetPath($this->logoPath);
    }

    public function setLogoPath(?string $logoPath): static
    {
        $this->logoPath = self::normalizeAssetPath($logoPath);

        return $this;
    }

    public function getMonogram(): ?string
    {
        return self::sanitizePlainText($this->monogram);
    }

    public function setMonogram(?string $monogram): static
    {
        $value = self::sanitizePlainText($monogram);
        $this->monogram = null === $value ? null : strtoupper(substr($value, 0, 10));

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

    public function getLogoFile(): ?UploadedFile
    {
        return $this->logoFile;
    }

    public function setLogoFile(?UploadedFile $logoFile): static
    {
        $this->logoFile = $logoFile;

        return $this;
    }

    /**
     * Backward-compatibility getters/setters used by existing templates/controllers.
     */
    public function getCompany(): string
    {
        return $this->getCompanyName();
    }

    public function setCompany(string $company): static
    {
        return $this->setCompanyName($company);
    }

    public function getLogoMonogram(): ?string
    {
        return $this->getMonogram();
    }

    public function setLogoMonogram(?string $logoMonogram): static
    {
        return $this->setMonogram($logoMonogram);
    }

    public function getImagePath(): ?string
    {
        return $this->getLogoPath();
    }

    public function setImagePath(?string $imagePath): static
    {
        return $this->setLogoPath($imagePath);
    }
}

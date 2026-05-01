<?php

namespace App\Entity;

use App\Entity\Concerns\SanitizesEntityDataTrait;
use App\Repository\TrainingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: TrainingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Training
{
    use SanitizesEntityDataTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'school', length: 100)]
    private string $schoolName = '';

    #[ORM\Column(length: 150)]
    private string $program = '';

    #[ORM\Column(length: 80)]
    private string $period = '';

    #[ORM\Column(type: Types::TEXT)]
    private string $summary = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 30)]
    private string $theme = '';

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    #[ORM\Column(options: ['default' => true])]
    private bool $isPublished = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    private ?UploadedFile $imageFile = null;

    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        $this->createdAt ??= new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->schoolName ?: 'Formation';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolName(): string
    {
        return self::sanitizePlainText($this->schoolName) ?? '';
    }

    public function setSchoolName(string $schoolName): static
    {
        $this->schoolName = self::sanitizePlainText($schoolName) ?? '';

        return $this;
    }

    public function getProgram(): string
    {
        return self::sanitizePlainText($this->program) ?? '';
    }

    public function setProgram(string $program): static
    {
        $this->program = self::sanitizePlainText($program) ?? '';

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

    public function getImagePath(): ?string
    {
        return self::normalizeAssetPath($this->imagePath);
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = self::normalizeAssetPath($imagePath);

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

    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    public function setImageFile(?UploadedFile $imageFile): static
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * Backward-compatibility getters/setters.
     */
    public function getSchool(): string
    {
        return $this->getSchoolName();
    }

    public function setSchool(string $school): static
    {
        return $this->setSchoolName($school);
    }
}

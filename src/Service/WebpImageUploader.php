<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class WebpImageUploader
{
    private const QUALITY = 84;

    /**
     * @var array<string, string>
     */
    private const TARGET_DIRECTORIES = [
        'works' => 'uploads/works',
        'experiences' => 'uploads/experiences',
        'trainings' => 'uploads/trainings',
    ];

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir
    )
    {
    }

    public function upload(
        UploadedFile $uploadedFile,
        string $target,
        ?string $existingRelativePath = null,
        ?string $preferredBaseName = null
    ): string {
        $targetDirectory = self::TARGET_DIRECTORIES[$target] ?? null;
        if (null === $targetDirectory) {
            throw new \InvalidArgumentException(sprintf('Target "%s" is not supported.', $target));
        }

        $sourcePath = $uploadedFile->getPathname();
        if (!is_file($sourcePath) || !is_readable($sourcePath)) {
            throw new \RuntimeException('Uploaded image file is unreadable.');
        }

        $relativePath = sprintf(
            '%s/%s',
            $targetDirectory,
            $this->buildWebpFileName($preferredBaseName ?? $uploadedFile->getClientOriginalName(), $sourcePath)
        );

        $absolutePath = $this->toAbsolutePublicPath($relativePath);
        $this->ensureDirectoryExists(\dirname($absolutePath));
        $temporaryPath = sprintf('%s.tmp', $absolutePath);

        $this->convertToWebp($sourcePath, $temporaryPath);

        if (!@rename($temporaryPath, $absolutePath)) {
            @unlink($temporaryPath);
            throw new \RuntimeException('Failed to finalize converted image file.');
        }

        $previousPath = $this->normalizeRelativePath($existingRelativePath);
        if (null !== $previousPath && $previousPath !== $relativePath) {
            $this->delete($previousPath);
        }

        return $relativePath;
    }

    public function delete(?string $relativePath): void
    {
        $path = $this->normalizeRelativePath($relativePath);
        if (null === $path || !$this->isManagedPath($path)) {
            return;
        }

        $absolutePath = $this->toAbsolutePublicPath($path);
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    private function convertToWebp(string $sourcePath, string $destinationPath): void
    {
        if (\function_exists('imagewebp')) {
            $this->convertToWebpWithGd($sourcePath, $destinationPath);
            return;
        }

        if (class_exists(\Imagick::class)) {
            $this->convertToWebpWithImagick($sourcePath, $destinationPath);
            return;
        }

        throw new \RuntimeException(
            'WebP conversion backend unavailable. Enable GD with WebP support (recommended in Dockerfile) or install Imagick.'
        );
    }

    private function convertToWebpWithGd(string $sourcePath, string $destinationPath): void
    {
        $imageInfo = @getimagesize($sourcePath);
        $mimeType = \is_array($imageInfo) ? ($imageInfo['mime'] ?? null) : null;
        $sourceImage = match ($mimeType) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($sourcePath),
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/webp' => @imagecreatefromwebp($sourcePath),
            default => false,
        };

        if (false === $sourceImage) {
            throw new \InvalidArgumentException('Unsupported image format. Allowed types: jpg, jpeg, png, webp.');
        }

        try {
            if (!imageistruecolor($sourceImage)) {
                imagepalettetotruecolor($sourceImage);
            }
            imagealphablending($sourceImage, true);
            imagesavealpha($sourceImage, true);

            if (!@imagewebp($sourceImage, $destinationPath, self::QUALITY)) {
                throw new \RuntimeException('Image conversion to WEBP failed.');
            }
        } finally {
            imagedestroy($sourceImage);
        }
    }

    private function convertToWebpWithImagick(string $sourcePath, string $destinationPath): void
    {
        try {
            $imagick = new \Imagick($sourcePath);
            $imagick->setImageFormat('webp');
            $imagick->setImageCompressionQuality(self::QUALITY);

            if (!$imagick->writeImage($destinationPath)) {
                throw new \RuntimeException('Image conversion to WEBP failed.');
            }
        } catch (\ImagickException $exception) {
            throw new \RuntimeException('Image conversion to WEBP failed.', previous: $exception);
        } finally {
            if (isset($imagick) && $imagick instanceof \Imagick) {
                $imagick->clear();
                $imagick->destroy();
            }
        }
    }

    private function buildWebpFileName(string $baseName, string $sourcePath): string
    {
        $base = pathinfo($baseName, \PATHINFO_FILENAME);
        $slug = $this->slugify($base);
        $signature = sha1_file($sourcePath);
        $hash = \is_string($signature) ? substr($signature, 0, 12) : bin2hex(random_bytes(6));

        return sprintf('%s-%s.webp', $slug, $hash);
    }

    private function slugify(string $value): string
    {
        $decoded = html_entity_decode($value, \ENT_QUOTES | \ENT_HTML5, 'UTF-8');
        $plain = strip_tags($decoded);
        $plain = trim($plain);

        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $plain);
        $candidate = false !== $ascii ? $ascii : $plain;

        $candidate = strtolower($candidate);
        $candidate = preg_replace('/[^a-z0-9]+/', '-', $candidate) ?? 'image';
        $candidate = trim($candidate, '-');

        return '' !== $candidate ? $candidate : 'image';
    }

    private function ensureDirectoryExists(string $absoluteDirectory): void
    {
        if (is_dir($absoluteDirectory)) {
            return;
        }

        if (!@mkdir($absoluteDirectory, 0775, true) && !is_dir($absoluteDirectory)) {
            throw new \RuntimeException(sprintf('Unable to create upload directory "%s".', $absoluteDirectory));
        }
    }

    private function normalizeRelativePath(?string $path): ?string
    {
        if (null === $path) {
            return null;
        }

        $normalized = str_replace('\\', '/', trim($path));
        $normalized = ltrim($normalized, '/');
        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, 7);
        }
        $normalized = trim($normalized, '/');

        return '' !== $normalized ? $normalized : null;
    }

    private function isManagedPath(string $path): bool
    {
        foreach (self::TARGET_DIRECTORIES as $directory) {
            if (str_starts_with($path, $directory.'/')) {
                return true;
            }
        }

        return false;
    }

    private function toAbsolutePublicPath(string $relativePath): string
    {
        return sprintf('%s/public/%s', rtrim($this->projectDir, '/'), ltrim($relativePath, '/'));
    }
}

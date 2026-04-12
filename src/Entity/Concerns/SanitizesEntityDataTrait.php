<?php

namespace App\Entity\Concerns;

trait SanitizesEntityDataTrait
{
    protected static function sanitizePlainText(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $text = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = strip_tags($text);
        $text = str_replace("\r", "\n", $text);
        $text = preg_replace('/\x{00A0}/u', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
        $text = trim($text);

        return '' === $text ? null : $text;
    }

    protected static function sanitizeLongText(?string $value): ?string
    {
        if (null === $value) {
            return null;
        }

        $text = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = strip_tags($text);
        $text = preg_replace('/\x{00A0}/u', ' ', $text) ?? $text;
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        $lines = explode("\n", $text);
        $normalized = [];
        foreach ($lines as $line) {
            $line = preg_replace('/\s+/u', ' ', trim($line)) ?? trim($line);
            if ('' !== $line) {
                $normalized[] = $line;
            }
        }

        $result = trim(implode("\n", $normalized));

        return '' === $result ? null : $result;
    }

    protected static function sanitizeUrl(?string $value): ?string
    {
        $url = self::sanitizePlainText($value);

        if (null === $url) {
            return null;
        }

        if (str_starts_with($url, 'www.')) {
            $url = sprintf('https://%s', $url);
        }

        return $url;
    }

    protected static function normalizeAssetPath(?string $value): ?string
    {
        $path = self::sanitizePlainText($value);
        if (null === $path) {
            return null;
        }

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        $path = trim($path, '/');

        return '' === $path ? null : $path;
    }
}

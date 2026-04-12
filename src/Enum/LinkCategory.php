<?php

namespace App\Enum;

enum LinkCategory: string
{
    case Network = 'network';
    case Hobby = 'hobby';

    public function label(): string
    {
        return match ($this) {
            self::Network => 'Réseau',
            self::Hobby => 'Hobbies',
        };
    }

    /**
     * @return array<string, self>
     */
    public static function choices(): array
    {
        return [
            self::Network->label() => self::Network,
            self::Hobby->label() => self::Hobby,
        ];
    }
}

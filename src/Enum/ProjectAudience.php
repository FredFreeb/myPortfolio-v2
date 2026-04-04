<?php

namespace App\Enum;

enum ProjectAudience: string
{
    case Public = 'grand-public';
    case Institutional = 'institutionnel';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Grand public',
            self::Institutional => 'Institutionnel',
        };
    }

    /**
     * @return array<string, self>
     */
    public static function choices(): array
    {
        return [
            self::Public->label() => self::Public,
            self::Institutional->label() => self::Institutional,
        ];
    }
}

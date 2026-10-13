<?php

namespace App\Enums;

enum RequestStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::InProgress => 'In progress',
            self::Closed => 'Closed',
        };
    }

    /**
     * Name of the badge colour used by the admin panel.
     */
    public function tone(): string
    {
        return match ($this) {
            self::New => 'blue',
            self::InProgress => 'gold',
            self::Closed => 'green',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])->all();
    }
}

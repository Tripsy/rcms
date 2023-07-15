<?php

declare(strict_types=1);

namespace App\Enums;

enum AccountStatus: string
{
    use EnumTrait;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function text(): string
    {
        return match($this)
        {
            self::ACTIVE => __('Active'),
            self::INACTIVE => __('Inactive'),
        };
    }
}

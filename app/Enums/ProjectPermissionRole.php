<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumTrait;

enum ProjectPermissionRole: string
{
    use EnumTrait;

    case MANAGER = 'manager';
    case OPERATOR = 'operator';

    public function text(): string
    {
        return match ($this) {
            self::MANAGER => __('Manager'),
            self::OPERATOR => __('Operator'),
        };
    }
}

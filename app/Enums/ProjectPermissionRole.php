<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectPermissionRole: string
{
    use EnumTrait;

    case MANAGER = 'manager';
    case OPERATOR = 'operator';

    public function text(): string
    {
        return match($this)
        {
            self::MANAGER => __('Manager'),
            self::OPERATOR => __('Operator'),
        };
    }
}

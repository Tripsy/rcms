<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumTrait;

enum DefaultOption: string
{
    use EnumTrait;

    case YES = 'yes';
    case NO = 'no';

    public function text(): string
    {
        return match ($this) {
            self::YES => __('Yes'),
            self::NO => __('No'),
        };
    }
}

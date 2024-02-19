<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumTrait;

enum BlueprintComponentType: string
{
    use EnumTrait;
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case CHECkBOX = 'checkbox';
    case RADIO = 'radio';
    case IMAGE = 'image';

    public function text(): string
    {
        return match ($this) {
            self::TEXT => __('Text'),
            self::TEXTAREA => __('Textarea'),
            self::SELECT => __('Select'),
            self::CHECkBOX => __('Checkbox'),
            self::RADIO => __('Radio'),
            self::IMAGE => __('Image'),
        };
    }
}

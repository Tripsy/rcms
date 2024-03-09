<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumTrait;

enum BlueprintComponentFormat: string
{
    use EnumTrait;

    case TEXT = 'text';
    case HTML = 'html';
    case MARKDOWN = 'markdown';
    case OPTION = 'option'; //used when component_type is select, radio, checkbox
    case FILE = 'file';

    public function text(): string
    {
        return match ($this) {
            self::TEXT => __('Text'),
            self::HTML => __('HTML'),
            self::MARKDOWN => __('Markdown'),
            self::OPTION => __('Option'),
            self::FILE => __('File'),
        };
    }
}

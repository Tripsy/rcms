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
    case FILE = 'file';

    public function text(): string
    {
        return match ($this) {
            self::TEXT => __('Text'),
            self::HTML => __('HTML'),
            self::MARKDOWN => __('Markdown'),
            self::FILE => __('File'),
        };
    }
}

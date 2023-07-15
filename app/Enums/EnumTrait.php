<?php

declare(strict_types=1);

namespace App\Enums;

trait EnumTrait
{
    public static function toArray($use_key = true): array
    {
        $cases = self::cases();

        $array = [];

        foreach ($cases as $v) {
            $array[($use_key ? $v->value : $v->text())] = $v->text();
        }

       return $array;
    }

    public static function justKeys(): array
    {
        $cases = self::cases();

        $array = [];

        foreach ($cases as $v) {
            $array[] = $v->value;
        }

       return $array;
    }
}

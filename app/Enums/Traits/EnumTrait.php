<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait EnumTrait
{
    //    public static function fromValue(string $value): self
    //    {
    //        $cases = self::cases();
    //
    //        foreach ($cases as $c) {
    //            if($value === $c->value ){
    //                return $c;
    //            }
    //        }
    //
    //        throw new \ValueError($name. ' is not a valid value for enum ' . self::class );
    //    }

    //    public static function toObject(string $value): self
    //    {
    //        $cases = self::cases();
    //
    //        foreach ($cases as $c) {
    //            if($value === $c->value ){
    //                return $c;
    //            }
    //        }
    //
    //        throw new \ValueError($name. ' is not a valid value for enum ' . self::class );
    //    }

    public static function toArray($use_key = true): array
    {
        $cases = self::cases();

        $array = [];

        foreach ($cases as $c) {
            $array[($use_key ? $c->value : $c->text())] = $c->text();
        }

        return $array;
    }

    public static function justKeys(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function listKeys(): string
    {
        return implode(', ', array_column(self::cases(), 'value'));
    }
}

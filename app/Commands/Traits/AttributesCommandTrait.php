<?php

declare(strict_types=1);

namespace App\Commands\Traits;

use BadMethodCallException;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

trait AttributesCommandTrait
{
    /**
     * Return array [key => value]  with private attributes
     */
    public function attributes(array $excludeAttributes = []): array
    {
        $result = [];

        $reflection = new ReflectionClass($this);
        $attributes = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();

            if (in_array($attributeName, $excludeAttributes) === true) {
                continue;
            }

            $methodName = Str::camel('get_'.$attributeName);

            if (method_exists($this, $methodName)) {
                $result[$attributeName] = call_user_func([$this, $methodName]);
            } else {
                throw new BadMethodCallException(__('message.exception.class_method_not_found', [
                    'method' => $methodName,
                    'class' => $this::class,
                ]));
            }
        }

        return $result;
    }
}

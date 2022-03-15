<?php

declare(strict_types=1);

namespace GraphQL\SchemaObject;

/**
 * Class ArgumentsObject.
 */
abstract class ArgumentsObject
{
    public function toArray(): array
    {
        $argsArray = [];
        foreach ($this as $name => $value) {
            if ($value !== null) {
                $argsArray[$name] = $value;
            }
        }

        return $argsArray;
    }
}

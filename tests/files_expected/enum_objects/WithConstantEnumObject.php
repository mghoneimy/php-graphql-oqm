<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\EnumObject;

class WithConstantEnumObject extends EnumObject
{
    public const FIXED_VALUE = 'fixed_value';
}

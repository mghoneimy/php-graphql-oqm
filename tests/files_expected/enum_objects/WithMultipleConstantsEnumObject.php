<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\EnumObject;

class WithMultipleConstantsEnumObject extends EnumObject
{
    public const SOME_VALUE = 'some_value';
    public const ANOTHER_VALUE = 'another_value';
    public const ONEMOREVALUE = 'oneMoreValue';
}

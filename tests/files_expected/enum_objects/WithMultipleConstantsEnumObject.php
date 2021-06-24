<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\EnumObject;

class WithMultipleConstantsEnumObject extends EnumObject
{
    const SOME_VALUE = "some_value";
    const ANOTHER_VALUE = "another_value";
    const ONEMOREVALUE = "oneMoreValue";
}

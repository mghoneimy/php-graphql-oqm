<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\ArgumentsObject;
use GraphQL\RawObject;

class WithMultipleEnumArgArgumentsObject extends ArgumentsObject
{
    protected $enumProperty;

    public function setEnumProperty($some)
    {
        $this->enumProperty = new RawObject($some);

        return $this;
    }
}

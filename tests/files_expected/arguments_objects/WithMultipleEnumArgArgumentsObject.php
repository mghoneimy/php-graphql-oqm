<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\RawObject;
use GraphQL\SchemaObject\ArgumentsObject;

class WithMultipleEnumArgArgumentsObject extends ArgumentsObject
{
    protected $enumProperty;

    public function setEnumProperty($some)
    {
        $this->enumProperty = new RawObject($some);

        return $this;
    }
}

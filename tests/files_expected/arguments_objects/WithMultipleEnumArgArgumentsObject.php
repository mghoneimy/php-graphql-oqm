<?php

namespace GraphQL\Tests\SchemaObject;

class WithMultipleEnumArgArgumentsObject extends \GraphQL\SchemaObject\ArgumentsObject
{
    protected SomeEnumObject $enumProperty;

    public function setEnumProperty(SomeEnumObject $enumProperty)
    {
        $this->enumProperty = $enumProperty;
        return $this;
    }
}

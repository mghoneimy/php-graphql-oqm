<?php

namespace GraphQL\Tests\SchemaObject;

class WithEnumValueInputObject extends \GraphQL\SchemaObject\InputObject
{
    protected $enumVal;

    public function setEnumVal(SomeEnumObjectInputObject $enumVal)
    {
        $this->enumVal = $enumVal;
        return $this;
    }
}

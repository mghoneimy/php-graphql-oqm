<?php

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\InputObject;

class WithEnumValueInputObject extends InputObject
{
    protected $enumVal;

    public function setEnumVal($enumVal)
    {
        $this->enumVal = $enumVal;

        return $this;
    }
}

<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\InputObject;

class WithEnumValueInputObject extends InputObject
{
    protected $enumVal;

    public function setEnumVal($enumVal)
    {
        $this->enumVal = $enumVal;

        return $this;
    }
}

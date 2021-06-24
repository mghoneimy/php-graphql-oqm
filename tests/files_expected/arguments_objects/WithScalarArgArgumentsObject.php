<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\ArgumentsObject;

class WithScalarArgArgumentsObject extends ArgumentsObject
{
    protected $scalarProperty;

    public function setScalarProperty($scalarProperty)
    {
        $this->scalarProperty = $scalarProperty;

        return $this;
    }
}

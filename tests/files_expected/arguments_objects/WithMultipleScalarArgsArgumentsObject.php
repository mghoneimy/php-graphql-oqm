<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\ArgumentsObject;

class WithMultipleScalarArgsArgumentsObject extends ArgumentsObject
{
    protected $scalarProperty;
    protected $another_scalar_property;

    public function setScalarProperty($scalarProperty)
    {
        $this->scalarProperty = $scalarProperty;

        return $this;
    }

    public function setAnotherScalarProperty($anotherScalarProperty)
    {
        $this->another_scalar_property = $anotherScalarProperty;

        return $this;
    }
}

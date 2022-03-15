<?php

namespace GraphQL\Tests\SchemaObject;

class WithMultipleScalarArgsArgumentsObject extends \GraphQL\SchemaObject\ArgumentsObject
{
    protected string $scalarProperty;
    protected string $another_scalar_property;

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

<?php

namespace GraphQL\Tests\SchemaObject;

class WithScalarArgArgumentsObject extends \GraphQL\SchemaObject\ArgumentsObject
{
    protected string $scalarProperty;

    public function setScalarProperty($scalarProperty)
    {
        $this->scalarProperty = $scalarProperty;
        return $this;
    }
}

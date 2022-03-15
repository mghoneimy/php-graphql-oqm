<?php

namespace GraphQL\Tests\SchemaObject;

class WithMultipleInputObjectArgsArgumentsObject extends \GraphQL\SchemaObject\ArgumentsObject
{
    protected SomeInputObject $objectProperty;
    protected AnotherInputObject $another_object_property;

    public function setObjectProperty(SomeInputObject $objectProperty)
    {
        $this->objectProperty = $objectProperty;
        return $this;
    }

    public function setAnotherObjectProperty(AnotherInputObject $another_object_property)
    {
        $this->another_object_property = $another_object_property;
        return $this;
    }
}

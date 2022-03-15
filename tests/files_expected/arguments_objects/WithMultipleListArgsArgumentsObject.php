<?php

namespace GraphQL\Tests\SchemaObject;

class WithMultipleListArgsArgumentsObject extends \GraphQL\SchemaObject\ArgumentsObject
{
    protected array $listProperty;
    protected array $another_list_property;

    public function setListProperty(array $listProperty)
    {
        $this->listProperty = $listProperty;
        return $this;
    }

    public function setAnotherListProperty(array $anotherListProperty)
    {
        $this->another_list_property = $anotherListProperty;
        return $this;
    }
}

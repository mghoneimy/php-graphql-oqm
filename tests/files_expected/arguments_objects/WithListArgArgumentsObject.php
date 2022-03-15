<?php

namespace GraphQL\Tests\SchemaObject;

class WithListArgArgumentsObject extends \GraphQL\SchemaObject\ArgumentsObject
{
    protected array $listProperty;

    public function setListProperty(array $listProperty)
    {
        $this->listProperty = $listProperty;
        return $this;
    }
}

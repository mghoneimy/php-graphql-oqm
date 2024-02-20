<?php

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\QueryObject;

class InterfaceObject1QueryObject extends QueryObject
{
    const OBJECT_NAME = "InterfaceObject1";

    public function selectValue()
    {
        $this->selectField("value");

        return $this;
    }
}

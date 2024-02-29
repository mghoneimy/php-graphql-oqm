<?php

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\InterfaceObject;

class TestInterfaceQueryObject extends InterfaceObject
{
    public function onInterfaceObject1(): InterfaceObject1QueryObject
    {
        return $this->addImplementation(InterfaceObject1QueryObject::class);
    }

    public function onInterfaceObject2(): InterfaceObject2QueryObject
    {
        return $this->addImplementation(InterfaceObject2QueryObject::class);
    }

    public function selectInterfaceField()
    {
        $this->selectField("interface_field");

        return $this;
    }
}

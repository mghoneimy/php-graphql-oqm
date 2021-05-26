<?php

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\UnionObject;

class UnionTestObjectUnionObject extends UnionObject
{
    public function onUnionObject1()
    {
        $object = new UnionObject1QueryObject();
        $this->addPossibleType($object);

        return $object;
    }

    public function onUnionObject2()
    {
        $object = new UnionObject2QueryObject();
        $this->addPossibleType($object);

        return $object;
    }
}

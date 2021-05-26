<?php

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\QueryObject;

class UnionObject1QueryObject extends QueryObject
{
    const OBJECT_NAME = "UnionObject1";

    public function selectUnion(UnionObject1UnionArgumentsObject $argsObject = null)
    {
        $object = new UnionTestObjectUnionObject("union");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);

        return $object;
    }
}

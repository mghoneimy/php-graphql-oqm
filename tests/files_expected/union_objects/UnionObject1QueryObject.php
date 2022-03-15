<?php

namespace GraphQL\Tests\SchemaObject;

class UnionObject1QueryObject extends \GraphQL\SchemaObject\QueryObject
{
    public const OBJECT_NAME = 'UnionObject1';

    public function selectUnion(UnionObject1UnionArgumentsObject $argsObject = null)
    {
        $object = new UnionTestObjectUnionObject('union');
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
        return $object;
    }
}

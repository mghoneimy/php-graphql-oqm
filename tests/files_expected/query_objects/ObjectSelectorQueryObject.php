<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\QueryObject;

class ObjectSelectorQueryObject extends QueryObject
{
    public const OBJECT_NAME = 'ObjectSelector';

    public function selectOthers(RootOthersArgumentsObject $argsObject = null)
    {
        $object = new OtherQueryObject('others');
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);

        return $object;
    }
}

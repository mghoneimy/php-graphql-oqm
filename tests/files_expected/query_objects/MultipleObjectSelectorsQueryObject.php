<?php

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\QueryObject;

class MultipleObjectSelectorsQueryObject extends QueryObject
{
    const OBJECT_NAME = "MultipleObjectSelectors";

    public function selectRight()
    {
        $object = new MultipleObjectSelectorsRightQueryObject("right");
        $this->selectField($object);

        return $object;
    }

    /**
     * @deprecated
     */
    public function selectLeftObjects()
    {
        $object = new LeftQueryObject("left_objects");
        $this->selectField($object);

        return $object;
    }
}

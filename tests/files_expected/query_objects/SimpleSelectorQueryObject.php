<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\QueryObject;

class SimpleSelectorQueryObject extends QueryObject
{
    const OBJECT_NAME = "SimpleSelector";

    public function selectName()
    {
        $this->selectField("name");

        return $this;
    }
}

<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\ArgumentsObject;

class WithListArgArgumentsObject extends ArgumentsObject
{
    protected $listProperty;

    public function setListProperty(array $listProperty)
    {
        $this->listProperty = $listProperty;

        return $this;
    }
}

<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\ArgumentsObject;

class WithInputObjectArgArgumentsObject extends ArgumentsObject
{
    protected $objectProperty;

    public function setObjectProperty(SomeInputObject $someInputObject)
    {
        $this->objectProperty = $someInputObject;

        return $this;
    }
}

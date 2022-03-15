<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\ArgumentsObject;

class WithInputObjectArgArgumentsObject extends ArgumentsObject
{
    protected $objectProperty;

    public function setObjectProperty(SomeInputObject $someInputObject)
    {
        $this->objectProperty = $someInputObject;

        return $this;
    }
}

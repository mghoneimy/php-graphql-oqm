<?php

namespace GraphQL\Tests\SchemaObject;

class WithInputObjectArgArgumentsObject extends \GraphQL\SchemaObject\ArgumentsObject
{
    protected \SomeInputObject $objectProperty;

    public function setObjectProperty(\SomeInputObject $objectProperty)
    {
        $this->objectProperty = $objectProperty;
        return $this;
    }
}

<?php

namespace GraphQL\Tests\SchemaObject;

class WithInputObjectValueInputObject extends \GraphQL\SchemaObject\InputObject
{
    protected $inputObject;

    public function setInputObject(\WithListValueInputObject $inputObject)
    {
        $this->inputObject = $inputObject;
        return $this;
    }
}

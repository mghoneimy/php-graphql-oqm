<?php

namespace GraphQL\Tests\SchemaObject;

class WithMultipleInputObjectValuesInputObject extends \GraphQL\SchemaObject\InputObject
{
    protected $inputObject;
    protected $inputObjectTwo;

    public function setInputObject(WithListValueInputObject $inputObject)
    {
        $this->inputObject = $inputObject;
        return $this;
    }

    public function setInputObjectTwo(_TestFilterInputObject $inputObjectTwo)
    {
        $this->inputObjectTwo = $inputObjectTwo;
        return $this;
    }
}

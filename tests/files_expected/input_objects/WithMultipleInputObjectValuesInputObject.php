<?php

namespace gmostafa\GraphQL\Tests\SchemaObject;

use gmostafa\GraphQL\SchemaObject\InputObject;

class WithMultipleInputObjectValuesInputObject extends InputObject
{
    protected $inputObject;
    protected $inputObjectTwo;

    public function setInputObject(WithListValueInputObject $withListValueInputObject)
    {
        $this->inputObject = $withListValueInputObject;

        return $this;
    }

    public function setInputObjectTwo(_TestFilterInputObject $testFilterInputObject)
    {
        $this->inputObjectTwo = $testFilterInputObject;

        return $this;
    }
}

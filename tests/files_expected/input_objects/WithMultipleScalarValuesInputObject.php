<?php

namespace GraphQL\Tests\SchemaObject;

class WithMultipleScalarValuesInputObject extends \GraphQL\SchemaObject\InputObject
{
    protected $valOne;
    protected $val_two;

    public function setValOne($valOne)
    {
        $this->valOne = $valOne;
        return $this;
    }

    public function setValTwo($valTwo)
    {
        $this->val_two = $valTwo;
        return $this;
    }
}

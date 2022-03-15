<?php

namespace GraphQL\Tests\SchemaObject;

class WithScalarValueInputObject extends \GraphQL\SchemaObject\InputObject
{
    protected $valOne;

    public function setValOne($valOne)
    {
        $this->valOne = $valOne;
        return $this;
    }
}

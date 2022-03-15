<?php

namespace GraphQL\Tests\SchemaObject;

class WithListValueInputObject extends \GraphQL\SchemaObject\InputObject
{
    protected $listOne;

    public function setListOne(array $listOne)
    {
        $this->listOne = $listOne;
        return $this;
    }
}

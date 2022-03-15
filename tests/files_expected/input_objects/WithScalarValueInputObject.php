<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\InputObject;

class WithScalarValueInputObject extends InputObject
{
    protected $valOne;

    public function setValOne($valOne)
    {
        $this->valOne = $valOne;

        return $this;
    }
}

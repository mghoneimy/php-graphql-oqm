<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\InputObject;

class WithListValueInputObject extends InputObject
{
    protected $listOne;

    public function setListOne(array $listOne)
    {
        $this->listOne = $listOne;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\ArgumentsObject;

class WithScalarArgArgumentsObject extends ArgumentsObject
{
    protected $scalarProperty;

    public function setScalarProperty($scalarProperty)
    {
        $this->scalarProperty = $scalarProperty;

        return $this;
    }
}

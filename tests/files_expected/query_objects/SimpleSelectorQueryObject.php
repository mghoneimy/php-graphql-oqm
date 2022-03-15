<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\QueryObject;

class SimpleSelectorQueryObject extends QueryObject
{
    public const OBJECT_NAME = 'SimpleSelector';

    public function selectName()
    {
        $this->selectField('name');

        return $this;
    }
}

<?php

namespace GraphQL\Tests\SchemaObject;

class SimpleSelectorQueryObject extends \GraphQL\SchemaObject\QueryObject
{
    public const OBJECT_NAME = 'SimpleSelector';

    public function selectName()
    {
        $this->selectField('name');
        return $this;
    }
}

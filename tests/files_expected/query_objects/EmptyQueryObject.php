<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\QueryObject;

class EmptyQueryObject extends QueryObject
{
    public const OBJECT_NAME = 'Empty';
}

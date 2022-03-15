<?php

declare(strict_types=1);

namespace GraphQL\SchemaObject;

use GraphQL\InlineFragment;

/**
 * Class UnionObject.
 */
abstract class UnionObject extends QueryObject
{
    protected function addPossibleType(QueryObject $possibleType)
    {
        $fragment = new InlineFragment($possibleType::OBJECT_NAME, $possibleType);
        $this->selectField($fragment);
    }
}

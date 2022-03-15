<?php

declare(strict_types=1);

namespace GraphQL\Tests\SchemaObject;

use GraphQL\SchemaObject\QueryObject;

class MultipleSimpleSelectorsQueryObject extends QueryObject
{
    public const OBJECT_NAME = 'MultipleSimpleSelectors';

    public function selectFirstName()
    {
        $this->selectField('first_name');

        return $this;
    }

    /**
     * @deprecated is deprecated
     */
    public function selectLastName()
    {
        $this->selectField('last_name');

        return $this;
    }

    public function selectGender()
    {
        $this->selectField('gender');

        return $this;
    }
}

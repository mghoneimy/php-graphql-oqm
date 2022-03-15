<?php

namespace GraphQL\Tests\SchemaObject;

class _TestFilterInputObject extends \GraphQL\SchemaObject\InputObject
{
    protected $first_name;
    protected $lastName;
    protected $ids;
    protected $testFilter;

    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
        return $this;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setIds(array $ids)
    {
        $this->ids = $ids;
        return $this;
    }

    public function setTestFilter(\_TestFilterInputObject $testFilter)
    {
        $this->testFilter = $testFilter;
        return $this;
    }
}

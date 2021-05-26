<?php

namespace GraphQL\Tests;

use GraphQL\Query;
use GraphQL\SchemaObject\QueryObject;
use GraphQL\SchemaObject\UnionObject;
use PHPUnit\Framework\TestCase;

/**
 * Class QueryObjectTest
 *
 * @package GraphQL\Tests
 */
class UnionObjectTest extends TestCase
{
    /**
     * @covers \GraphQL\SchemaObject\UnionObject
     */
    public function testUnionObject()
    {
        $object = new SimpleUnionObject('union');
        $object->onType1()->selectScalar();
        $object->onType2()->selectAnotherScalar();
        $this->assertEquals(
            'query {
union {
... on Type1 {
scalar
}
... on Type2 {
anotherScalar
}
}
}',
            (string) $object->getQuery());
    }
}

class SimpleUnionObject extends UnionObject
{
    const OBJECT_NAME = 'Simple';



    public function onType1()
    {
        $object = new Type1QueryObject();

        $this->addPossibleType($object);

        return $object;
    }

    public function onType2()
    {
        $object = new Type2QueryObject();

        $this->addPossibleType($object);

        return $object;
    }
}

abstract class SimpleSubTypeQueryObject extends QueryObject
{
    public function selectScalar()
    {
        $this->selectField('scalar');

        return $this;
    }

    public function selectAnotherScalar()
    {
        $this->selectField('anotherScalar');

        return $this;
    }
}

class Type1QueryObject extends SimpleSubTypeQueryObject
{
    const OBJECT_NAME = 'Type1';
}

class Type2QueryObject extends SimpleSubTypeQueryObject
{
    const OBJECT_NAME = 'Type2';
}


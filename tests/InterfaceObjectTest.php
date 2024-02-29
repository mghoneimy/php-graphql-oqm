<?php

namespace GraphQL\Tests;

use GraphQL\SchemaObject\QueryObject;
use GraphQL\SchemaObject\InterfaceObject;
use PHPUnit\Framework\TestCase;

/**
 * Class QueryObjectTest
 *
 * @package GraphQL\Tests
 */
class InterfaceObjectTest extends TestCase
{
    /**
     * @covers \GraphQL\SchemaObject\InterfaceObject
     */
    public function testInterfaceObject()
    {
        $object = new SimpleInterfaceObject('interface');
        $object->onType1()->selectScalar();
        $object->onType2()->selectAnotherScalar();
        $object->onType2()->selectScalar();
        $this->assertEquals(
            'query {
interface {
... on Type1 {
scalar
}
... on Type2 {
anotherScalar
scalar
}
}
}',
            (string) $object->getQuery());
    }
}

class SimpleInterfaceObject extends InterfaceObject
{
    const OBJECT_NAME = 'Simple';



    public function onType1(): InterfaceType1QueryObject
    {
        return $this->addImplementation(InterfaceType1QueryObject::class);
    }

    public function onType2(): InterfaceType2QueryObject
    {
        return $this->addImplementation(InterfaceType2QueryObject::class);
    }
}

abstract class InterfaceSimpleSubTypeQueryObject extends QueryObject
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

class InterfaceType1QueryObject extends InterfaceSimpleSubTypeQueryObject
{
    const OBJECT_NAME = 'Type1';
}

class InterfaceType2QueryObject extends InterfaceSimpleSubTypeQueryObject
{
    const OBJECT_NAME = 'Type2';
}


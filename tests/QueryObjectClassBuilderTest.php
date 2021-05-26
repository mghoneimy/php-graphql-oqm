<?php

namespace GraphQL\Tests;

use GraphQL\Enumeration\FieldTypeKindEnum;
use GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder;
use GraphQL\SchemaObject\QueryObject;

class QueryObjectClassBuilderTest extends CodeFileTestCase
{
    private const TESTING_NAMESPACE = 'GraphQL\\Tests\\SchemaObject';

    /**
     * @return string
     */
    protected static function getExpectedFilesDir()
    {
        return parent::getExpectedFilesDir() . '/query_objects';
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::__construct
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::build
     */
    public function testBuildEmptyQueryObject()
    {
        $objectName = 'Empty';
        $classBuilder = new QueryObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'QueryObject';
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::__construct
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::build
     */
    public function testBuildRootQueryObject()
    {
        $objectName = QueryObject::ROOT_QUERY_OBJECT_NAME;
        $classBuilder = new QueryObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'QueryObject';
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addScalarField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addSimpleSelector
     */
    public function testAddSimpleSelector()
    {
        $objectName = 'SimpleSelector';
        $classBuilder = new QueryObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'QueryObject';
        $classBuilder->addScalarField('name', false, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @depends testAddSimpleSelector
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addScalarField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addSimpleSelector
     */
    public function testAddMultipleSimpleSelectors()
    {
        $objectName = 'MultipleSimpleSelectors';
        $classBuilder = new QueryObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'QueryObject';
        $classBuilder->addScalarField('first_name', false, null);
        $classBuilder->addScalarField('last_name', true, 'is deprecated');
        $classBuilder->addScalarField('gender', false, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addObjectField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addObjectSelector
     */
    public function testAddObjectSelector()
    {
        $objectName = 'ObjectSelector';
        $classBuilder = new QueryObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'QueryObject';
        $classBuilder->addObjectField('others', 'Other', FieldTypeKindEnum::OBJECT, 'RootOthersArgumentsObject', false, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @depends testAddObjectSelector
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addObjectField
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder::addObjectSelector
     */
    public function testAddMultipleObjectSelectors()
    {
        $objectName = 'MultipleObjectSelectors';
        $classBuilder = new QueryObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $objectName .= 'QueryObject';
        $classBuilder->addObjectField('right', 'MultipleObjectSelectorsRight', FieldTypeKindEnum::OBJECT, 'MultipleObjectSelectorsRightArgumentsObject', false, null);
        $classBuilder->addObjectField('left_objects', 'Left', FieldTypeKindEnum::OBJECT, 'MultipleObjectSelectorsLeftObjectsArgumentsObject', true, null);
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }
}

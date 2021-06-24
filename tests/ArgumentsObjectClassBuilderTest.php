<?php

namespace GraphQL\Tests;


use gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder;

/**
 * Class ArgumentsObjectClassBuilderTest
 *
 * @package GraphQL\Tests
 */
class ArgumentsObjectClassBuilderTest extends CodeFileTestCase
{
    private const TESTING_NAMESPACE = 'gmostafa\\GraphQL\\Tests\\SchemaObject';

    /**
     * @return string
     */
    protected static function getExpectedFilesDir()
    {
        return parent::getExpectedFilesDir() . '/arguments_objects';
    }

    /**
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::__construct
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::addScalarArgument
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::build
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addProperty
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addScalarSetter
     */
    public function testAddScalarArgument()
    {
        $objectName = 'WithScalarArgArgumentsObject';
        $classBuilder = new ArgumentsObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $classBuilder->addScalarArgument('scalarProperty');
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::__construct
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::addScalarArgument
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::build
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addProperty
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addScalarSetter
     */
    public function testAddMultipleScalarArguments()
    {
        $objectName = 'WithMultipleScalarArgsArgumentsObject';
        $classBuilder = new ArgumentsObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $classBuilder->addScalarArgument('scalarProperty');
        $classBuilder->addScalarArgument('another_scalar_property');
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::__construct
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::addListArgument
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::build
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addProperty
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addListSetter
     */
    public function testAddListArgument()
    {
        $objectName = 'WithListArgArgumentsObject';
        $classBuilder = new ArgumentsObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $classBuilder->addListArgument('listProperty', 'string');
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::__construct
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::addListArgument
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::build
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addProperty
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addListSetter
     */
    public function testAddMultipleListArguments()
    {
        $objectName = 'WithMultipleListArgsArgumentsObject';
        $classBuilder = new ArgumentsObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $classBuilder->addListArgument('listProperty', 'string');
        $classBuilder->addListArgument('another_list_property', 'string');
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::__construct
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::addInputObjectArgument
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::build
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addProperty
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addObjectSetter
     */
    public function testAddInputObjectArgument()
    {
        $objectName = 'WithInputObjectArgArgumentsObject';
        $classBuilder = new ArgumentsObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $classBuilder->addInputObjectArgument('objectProperty', 'Some');
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }

    /**
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::__construct
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::addInputObjectArgument
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder::build
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addProperty
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\ObjectClassBuilder::addObjectSetter
     */
    public function testAddMultipleInputObjectArguments()
    {
        $objectName = 'WithMultipleInputObjectArgsArgumentsObject';
        $classBuilder = new ArgumentsObjectClassBuilder(static::getGeneratedFilesDir(), $objectName, static::TESTING_NAMESPACE);
        $classBuilder->addInputObjectArgument('objectProperty', 'Some');
        $classBuilder->addInputObjectArgument('another_object_property', 'Another');
        $classBuilder->build();

        $this->assertFileEquals(
            static::getExpectedFilesDir() . "/$objectName.php",
            static::getGeneratedFilesDir() . "/$objectName.php"
        );
    }
}
<?php

namespace GraphQL\Tests;

use gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;

class ClassFileTest extends CodeFileTestCase
{
    /**
     * @inheritdoc
     */
    protected static function getExpectedFilesDir()
    {
        return parent::getExpectedFilesDir() . '/classes';
    }

    /**
     * @throws Exception
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::__construct
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::writeFile
     */
    public function testEmptyClass()
    {
        $fileName = 'EmptyClass';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyClass
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::extendsClass
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateClassName
     */
    public function testExtendsClass()
    {
        $fileName = 'ClassExtendsBase';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->extendsClass('Base');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @param ClassFile $class
     *
     * @depends clone testEmptyClass
     */
    public function testExtendsEmptyClassName(ClassFile $class)
    {
        $class->extendsClass('');
        $class->writeFile();

        $fileName = $class->getFileName();
        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyClass
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::implementsInterface
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateClassName
     */
    public function testImplementsInterfaces()
    {
        $fileName = 'ClassImplementsInterface';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->implementsInterface('InterfaceOne');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());

        $fileName = 'ClassImplementsMultipleInterfaces';
        $class->changeFileName($fileName);
        $class->implementsInterface('InterfaceTwo');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @param ClassFile $class
     *
     * @depends clone testImplementsInterfaces
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateClassName
     */
    public function testImplementDuplicateInterfaces(ClassFile $class)
    {
        $class->implementsInterface('InterfaceOne');
        $class->writeFile();

        $fileName = $class->getFileName();
        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @param ClassFile $class
     *
     * @depends clone testEmptyClass
     */
    public function testImplementEmptyInterfaceName(ClassFile $class)
    {
        $class->implementsInterface('');
        $class->writeFile();

        $fileName = $class->getFileName();
        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyClass
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addTrait
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateTraits
     */
    public function testUseTraits()
    {
        $fileName = 'ClassWithTrait';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->addTrait('TraitOne');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());

        $fileName = 'ClassWithMultipleTraits';
        $class->changeFileName($fileName);
        $class->addTrait('TraitTwo');
        $class->addTrait('TraitThree');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @param ClassFile $class
     *
     * @depends clone testUseTraits
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateTraits
     */
    public function testUseDuplicateTraits(ClassFile $class)
    {
        $class->addTrait('TraitThree');
        $class->writeFile();

        $fileName = $class->getFileName();
        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @param ClassFile $class
     *
     * @depends clone testEmptyClass
     */
    public function testUseEmptyTraitName(ClassFile $class)
    {
        $class->addTrait('');
        $class->writeFile();

        $fileName = $class->getFileName();
        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @return ClassFile
     *
     * @depends testEmptyClass
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addConstant
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateConstants
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::serializeParameterValue
     */
    public function testClassWithConstants()
    {
        $fileName = 'ClassWithConstant';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->addConstant('CONST_ONE', 'ONE');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());

        $fileName = 'ClassWithMultipleConstants';
        $class->changeFileName($fileName);
        $class->addConstant('CONST_TWO', 2);
        $class->addConstant('CONST_THEE', true);
        $class->addConstant('CONST_FOUR', false);
        $class->addConstant('CONST_FIVE', '');
        $class->addConstant('CONST_SIX', 6.6);
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @param ClassFile $class
     *
     * @depends clone testClassWithConstants
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateConstants
     */
    public function testClassWithDuplicateConstants(ClassFile $class)
    {
        $class->addConstant('CONST_TWO', 2);
        $class->writeFile();

        $fileName = $class->getFileName();
        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @param ClassFile $class
     *
     * @depends clone testEmptyClass
     */
    public function testConstantWithEmptyName(ClassFile $class)
    {
        $class->addConstant('', null);
        $class->writeFile();

        $fileName = $class->getFileName();
        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testClassWithConstants
     * @depends testUseTraits
     * @depends testExtendsClass
     * @depends testImplementsInterfaces
     *
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateFileContents
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::setNamespace
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateNamespace
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addImport
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateImports
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addProperty
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateProperties
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addMethod
     * @covers \gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateMethods
     */
    public function testFullClass()
    {
        $fileName = 'ClassWithEverything';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);

        $class->setNamespace('GraphQl\\Test');
        $class->addImport('GraphQl\\Base\\Base');
        $class->addImport('GraphQl\\Interfaces\\Intr1');
        $class->addImport('GraphQl\\Interfaces\\Intr2');
        $class->addImport('GraphQl\\Base\\Trait1');
        $class->addImport('GraphQl\\Base\\Trait2');

        $class->extendsClass('Base');
        $class->implementsInterface('Intr1');
        $class->implementsInterface('Intr2');

        $class->addTrait('Trait1');
        $class->addTrait('Trait2');
        $class->addConstant('CONST_ONE', 1);
        $class->addConstant('CONST_TWO', '');
        $class->addProperty('propertyOne');
        $class->addProperty('propertyTwo', '');

        $class->addMethod('public function dumpAll() {
    print \'dumping\';
}');
        $class->addMethod('protected function internalStuff($i) {
    return ++$i;
}');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir() . "/$fileName.php", $class->getWritePath());
    }
}
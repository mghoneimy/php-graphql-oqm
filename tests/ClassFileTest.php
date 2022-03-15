<?php

declare(strict_types=1);

namespace GraphQL\Tests;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;
use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\Parameter;

class ClassFileTest extends CodeFileTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function getExpectedFilesDir()
    {
        return parent::getExpectedFilesDir().'/classes';
    }

    /**
     * @throws Exception
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::__construct
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::writeFile
     */
    public function testEmptyClass()
    {
        $fileName = 'EmptyClass';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyClass
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::extendsClass
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::getClassName
     */
    public function testExtendsClass()
    {
        $fileName = 'ClassExtendsBase';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->extendsClass('Base');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());
    }

    /**
     * @depends clone testEmptyClass
     */
    public function testExtendsEmptyClassName(ClassFile $class)
    {
        $class->extendsClass('');
        $class->writeFile();

        $fileName = $class->getClassName();
        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyClass
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::implementsInterface
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::getClassName
     */
    public function testImplementsInterfaces()
    {
        $fileName = 'ClassImplementsInterface';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->implementsInterface('InterfaceOne');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());

        $fileName = 'ClassImplementsMultipleInterfaces';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->implementsInterface('InterfaceOne');
        $class->implementsInterface('InterfaceTwo');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @depends clone testEmptyClass
     */
    public function testImplementEmptyInterfaceName(ClassFile $class)
    {
        $this->expectException(\Nette\InvalidArgumentException::class);
        $this->expectExceptionMessage("Value '' is not valid class name");

        $class->implementsInterface('');
        $class->writeFile();
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyClass
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addTrait
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateTraits
     */
    public function testUseTraits()
    {
        $fileName = 'ClassWithTrait';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->addTrait('TraitOne');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());

        $fileName = 'ClassWithMultipleTraits';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->addTrait('TraitOne');
        $class->addTrait('TraitTwo');
        $class->addTrait('TraitThree');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @depends clone testUseTraits
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateTraits
     */
    public function testUseDuplicateTraits(ClassFile $class)
    {
        $this->expectException(\Nette\InvalidStateException::class);
        $this->expectExceptionMessage("Cannot add trait 'TraitThree', because it already exists.");
        $class->addTrait('TraitThree');
        $class->writeFile();
    }

    /**
     * @depends clone testEmptyClass
     */
    public function testUseEmptyTraitName(ClassFile $class)
    {
        $this->expectException(\Nette\InvalidArgumentException::class);
        $this->expectExceptionMessage("Value '' is not valid trait name");

        $class->addTrait('');
        $class->writeFile();
    }

    /**
     * @return ClassFile
     *
     * @depends testEmptyClass
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addConstant
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateConstants
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::serializeParameterValue
     */
    public function testClassWithConstants()
    {
        $fileName = 'ClassWithConstant';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->addConstant('CONST_ONE', 'ONE');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());

        $fileName = 'ClassWithMultipleConstants';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName);
        $class->addConstant('CONST_ONE', 'ONE');
        $class->addConstant('CONST_TWO', 2);
        $class->addConstant('CONST_THEE', true);
        $class->addConstant('CONST_FOUR', false);
        $class->addConstant('CONST_FIVE', '');
        $class->addConstant('CONST_SIX', 6.6);
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());

        return $class;
    }

    /**
     * @depends clone testClassWithConstants
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateConstants
     */
    public function testClassWithDuplicateConstants(ClassFile $class)
    {
        $this->expectException(\Nette\InvalidStateException::class);
        $this->expectExceptionMessage("Cannot add constant 'CONST_TWO', because it already exists.");
        $class->addConstant('CONST_TWO', 2);
        $class->writeFile();
    }

    /**
     * @depends clone testEmptyClass
     */
    public function testConstantWithEmptyName(ClassFile $class)
    {
        $this->expectException(\Nette\InvalidArgumentException::class);
        $this->expectExceptionMessage("Value '' is not valid name.");

        $class->addConstant('', null);
        $class->writeFile();
    }

    /**
     * @throws Exception
     *
     * @depends testClassWithConstants
     * @depends testUseTraits
     * @depends testExtendsClass
     * @depends testImplementsInterfaces
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateFileContents
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::setNamespace
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateNamespace
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addImport
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateImports
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addProperty
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateProperties
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::addMethod
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile::generateMethods
     */
    public function testFullClass()
    {
        $fileName = 'ClassWithEverything';
        $class = new ClassFile(static::getGeneratedFilesDir(), $fileName, 'GraphQl\\Test');

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

        $class->addMethod('dumpAll')->setVisibility(ClassLike::VisibilityPublic)->setBody("print 'dumping';");
        $class->addMethod('internalStuff')->setVisibility(ClassLike::VisibilityProtected)->setParameters([ new Parameter('i') ])->setBody('return ++$i;');
        $class->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $class->getWritePath());
    }
}

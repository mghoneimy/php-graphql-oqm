<?php

declare(strict_types=1);

namespace GraphQL\Tests;

use Exception;
use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile;
use Nette\PhpGenerator\ClassLike;

class TraitFileTest extends CodeFileTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function getExpectedFilesDir()
    {
        return parent::getExpectedFilesDir().'/traits';
    }

    /**
     * Happy scenario test, create empty trait with just name and write it to file system.
     *
     * @throws Exception
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::__construct
     */
    public function testEmptyTrait()
    {
        $fileName = 'EmptyTrait';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyTrait
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::setNamespace
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateNamespace
     */
    public function testTraitWithNamespace()
    {
        $fileName = 'TraitWithNamespace';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName, "GraphQL\Test");
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyTrait
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::setNamespace
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateNamespace
     */
    public function testTraitWithEmptyNamespace()
    {
        $fileName = 'EmptyTrait';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyTrait
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addImport
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateImports
     */
    public function testTraitWithImports()
    {
        $fileName = 'TraitWithImports';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->addImport("GraphQL\Query");
        $trait->addImport("GraphQL\Client");
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyTrait
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addImport
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateImports
     */
    public function testTraitWithEmptyImport()
    {
        $fileName = 'EmptyTrait';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);

        $this->expectException(\Nette\InvalidArgumentException::class);
        $this->expectExceptionMessage("Value '' is not valid class/function/constant name.");

        $trait->addImport('');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * Maybe this should be rather moved to an integration test?
     *
     * @throws Exception
     *
     * @depends testTraitWithNamespace
     * @depends testTraitWithImports
     *
     * @coversNothing
     */
    public function testTraitWithNamespaceAndImports()
    {
        $fileName = 'TraitWithNamespaceAndImports';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName, 'GraphQL\\Test');
        $trait->addImport('GraphQL\\Query');
        $trait->addImport('GraphQL\\Client');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyTrait
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addProperty
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateProperties
     */
    public function testTraitWithProperties()
    {
        $fileName = 'TraitWithProperties';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->addProperty('property1');
        $trait->addProperty('propertyTwo');
        $trait->addProperty('property_three');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());

        return $trait;
    }

    /**
     * @throws Exception
     *
     * @depends testTraitWithProperties
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addProperty
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateProperties
     */
    public function testTraitWithEmptyProperty()
    {
        $fileName = 'EmptyTrait';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);

        $this->expectException(\Nette\InvalidArgumentException::class);
        $this->expectExceptionMessage("Value '' is not valid name.");

        $trait->addProperty('');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @depends clone testTraitWithProperties
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addProperty
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateProperties
     */
    public function testTraitWithDuplicateProperties(TraitFile $trait)
    {
        $this->expectException(\Nette\InvalidStateException::class);
        $this->expectExceptionMessage("Cannot add property 'property1', because it already exists.");

        // Adding the same property again
        $trait->addProperty('property1');
    }

    /**
     * @throws Exception
     *
     * @depends testTraitWithProperties
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addProperty
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateProperties
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::serializeParameterValue
     */
    public function testTraitWithPropertiesAndValues()
    {
        $fileName = 'TraitWithPropertiesAndValues';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->addProperty('propertyOne', null);
        $trait->addProperty('propertyTwo', 2);
        $trait->addProperty('propertyThree', 'three');
        $trait->addProperty('propertyFour', false);
        $trait->addProperty('propertyFive', true);
        $trait->addProperty('propertySix', '');
        $trait->addProperty('propertySeven', 7.7);
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyTrait
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addMethod
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateMethods
     */
    public function testTraitWithOneMethod()
    {
        $fileName = 'TraitWithOneMethod';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->addMethod('testTheTrait')->setVisibility(ClassLike::VisibilityPublic)->addBody('print "test!";')->addBody('die();');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testTraitWithOneMethod
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addMethod
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateMethods
     */
    public function testTraitWithMultipleMethods()
    {
        $fileName = 'TraitWithMultipleMethods';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->addMethod('testTheTrait')->setVisibility(ClassLike::VisibilityPublic)->addBody('$this->innerTest();')->addBody('die();');
        $trait->addMethod('innerTest', true, 'is deprecated')->setVisibility(ClassLike::VisibilityPrivate)->addBody('print "test!";')->addBody('die();');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testEmptyTrait
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::addMethod
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateMethods
     */
    public function testTraitWithEmptyMethod()
    {
        $this->expectException(\Nette\InvalidArgumentException::class);
        $this->expectExceptionMessage("Value '' is not valid name.");
        $fileName = 'EmptyTrait';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->addMethod('');
        $trait->writeFile();
    }

    /**
     * @throws Exception
     *
     * @depends testTraitWithProperties
     * @depends testTraitWithMultipleMethods
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateFileContents
     */
    public function testTraitWithPropertiesAndMethods()
    {
        $fileName = 'TraitWithPropertiesAndMethods';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName);
        $trait->addProperty('propOne');
        $trait->addProperty('propTwo', true);
        $trait->addMethod('getProperties')->setVisibility(ClassLike::VisibilityPublic)->addBody('return [$this->propOne, $this->propTwo];');
        $trait->addMethod('clearProperties')->setVisibility(ClassLike::VisibilityPublic)->addBody('$this->propOne = 1;')->addBody('$this->propTwo = 2;');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }

    /**
     * @throws Exception
     *
     * @depends testTraitWithNamespaceAndImports
     * @depends testTraitWithPropertiesAndMethods
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\TraitFile::generateFileContents
     */
    public function testTraitWithEverything()
    {
        $fileName = 'TraitWithEverything';
        $trait = new TraitFile(static::getGeneratedFilesDir(), $fileName, 'GraphQL\\Test');
        $trait->addImport('GraphQL\\Query');
        $trait->addImport('GraphQL\\Client');
        $trait->addProperty('propOne');
        $trait->addProperty('propTwo', 'bool');
        $trait->addMethod('getProperties')->setVisibility(ClassLike::VisibilityPublic)->addBody('return [$this->propOne, $this->propTwo];');
        $trait->addMethod('clearProperties')->setVisibility(ClassLike::VisibilityPublic)->addBody('$this->propOne = 1;')->addBody('$this->propTwo = 2;');
        $trait->writeFile();

        $this->assertFileEquals(static::getExpectedFilesDir()."/$fileName.php", $trait->getWritePath());
    }
}

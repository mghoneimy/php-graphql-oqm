<?php

namespace GraphQL\Tests;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile;

/**
 * Class AbstractCodeFileTest
 *
 * @package GraphQL\Tests
 */
class AbstractCodeFileTest extends CodeFileTestCase
{
    /**
     * @var AbstractCodeFile
     */
    protected $codeFile;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @inheritdoc
     */
    protected static function getExpectedFilesDir()
    {
        return parent::getExpectedFilesDir() . DIRECTORY_SEPARATOR . 'abstract_code_files';
    }

    /**
     *
     */
    protected function setUp(): void
    {
        $this->fileName = 'EmptyCodeFile';
        $this->codeFile = $this->getMockForAbstractClass(
            AbstractCodeFile::class,
            [static::getGeneratedFilesDir(), $this->fileName]
        );
        $this->codeFile->method('generateFileContents')->willReturn("<?php" . PHP_EOL);
    }

    /**
     * @testdox Test the behavior of the constructor when provided with an invalid directory
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::__construct
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::validateDirectory
     */
    public function testInvalidWriteDirInConstructor()
    {
        $this->expectException(\Exception::class);
        $mock = $this->getMockForAbstractClass(
            AbstractCodeFile::class,
            [static::getGeneratedFilesDir() . DIRECTORY_SEPARATOR . 'invalid', $this->fileName]
        );
        $mock->method('generateFileContents')->willReturn("<?php" . PHP_EOL);
    }

    /**
     * @testdox Test the behavior of the constructor when provided with a non-writable directory
     *
     * @requires OS ^Windows
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::__construct()
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::validateDirectory
     */
    public function testUnwritableDirInConstructor()
    {

        $testDir = static::getGeneratedFilesDir() . DIRECTORY_SEPARATOR . 'unwritable-constructor';
        mkdir($testDir);
        chmod($testDir, 0444);

        $this->expectException(\Exception::class);

        $mock = $this->getMockForAbstractClass(
            AbstractCodeFile::class,
            [$testDir, $this->fileName]
        );
        $mock->method('generateFileContents')->willReturn("<?php" . PHP_EOL);
    }

    /**
     * @testdox Test the behavior of changeWriteDir method when provided with an invalid directory
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::changeWriteDir
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::validateDirectory
     */
    public function testInvalidWriteDir()
    {

        $this->expectException(\Exception::class);
        $this->codeFile->changeWriteDir(static::getGeneratedFilesDir() . DIRECTORY_SEPARATOR . 'invalid');
    }

    /**
     * @testdox Test the behavior of changeWriteDir method when provided with a non-writable directory
     *
     * @requires OS ^Windows
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::changeWriteDir
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::validateDirectory
     */
    public function testUnwritableDir()
    {
        $testDir = static::getGeneratedFilesDir() . DIRECTORY_SEPARATOR .'unwritable';
        mkdir($testDir);
        chmod($testDir, 0444);

        $this->expectException(\Exception::class);
        $this->codeFile->changeWriteDir($testDir);
    }

    /**
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::getWritePath
     */
    public function testWritePathGetter()
    {
        $this->assertEquals(static::getGeneratedFilesDir() . DIRECTORY_SEPARATOR . $this->fileName . ".php", $this->codeFile->getWritePath());
    }

    /**
     * @depends testWritePathGetter
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::writeFile
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::writeFileToPath
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::generateFileContents
     */
    public function testFileWritingWorks()
    {
        $this->codeFile->writeFile();

        $this->assertTrue(
            $this->assertFileEqualsIgnoreWhitespace(
                static::getExpectedFilesDir() . DIRECTORY_SEPARATOR . $this->fileName . ".php", $this->codeFile->getWritePath()
            )
        );

    }

    /**
     * @depends testFileWritingWorks
     *
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::changeWriteDir
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::changeFileName
     * @covers \GraphQL\SchemaGenerator\CodeGenerator\CodeFile\AbstractCodeFile::WriteFile
     */
    public function testFileWritingWorksWithTrailingSlash()
    {
        $this->fileName = 'EmptyCodeFileWithSlash';
        $this->codeFile->changeWriteDir($this->codeFile->getWriteDir() . DIRECTORY_SEPARATOR);
        $this->codeFile->changeFileName($this->fileName);
        $this->codeFile->writeFile();

        $this->assertTrue(
            $this->assertFileEqualsIgnoreWhitespace(
                static::getExpectedFilesDir() . DIRECTORY_SEPARATOR . $this->fileName . ".php", $this->codeFile->getWritePath()
            )
        );
    }
}
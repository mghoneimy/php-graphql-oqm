<?php

namespace GraphQL\Tests;

use PHPUnit\Framework\TestCase;

abstract class CodeFileTestCase extends TestCase
{
    /**
     * @return string
     */
    protected static function getGeneratedFilesDir()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files_generated';
    }

    /**
     * @return string
     */
    protected static function getExpectedFilesDir()
    {

        return dirname(__FILE__) . DIRECTORY_SEPARATOR . 'files_expected';
    }

    /**
     * Create directory before executing the tests
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if ( !is_dir(static::getGeneratedFilesDir() )) {
            mkdir(static::getGeneratedFilesDir());
        }
    }

    /**
     * Remove directory created during running this class' tests
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        static::removeDirRecursive(static::getGeneratedFilesDir());
    }

    /**
     * @param $dirName
     */
    private static function removeDirRecursive($dirName)
    {

        foreach (scandir($dirName) as $fileName) {
            if ($fileName !== '.' && $fileName !== '..') {
                $filePath = $dirName . DIRECTORY_SEPARATOR . $fileName;
                if (is_dir($filePath)) {
                    static::removeDirRecursive($filePath);
                } else {
                    unlink($filePath);
                }
            }
        }

        @rmdir($dirName);
    }

    /**
     * Read file content ignoring whitespace characters
     * @param $filename
     * @return string
     */
    private function readFileContent($filename) {

        $content = file_get_contents($filename);

        if (!$content) {
            return '';
        }

        return str_replace(["\t","\n","\r","\0","\x0B"],'', $content );
    }

    /**
     * Compare files by content
     * @param $expectedFile
     * @param $actualFile
     * @return bool
     */
    public function assertFileEqualsIgnoreWhitespace($expectedFile, $actualFile ) {

        $expected = $this->readFileContent($expectedFile);

        if (empty($expected)) {
            return false;
        }

        $generated = $this->readFileContent($actualFile);

        if (empty($generated)) {
            return false;
        }

        if (strcmp($expected, $generated) !== 0) {
            return false;
        }

        return true;

    }



}
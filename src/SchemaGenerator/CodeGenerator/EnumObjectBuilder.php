<?php

namespace gmostafa\GraphQL\SchemaGenerator\CodeGenerator;

use gmostafa\GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;

/**
 * Class EnumObjectBuilder
 *
 * @package GraphQL\SchemaGenerator\CodeGenerator
 */
class EnumObjectBuilder implements ObjectBuilderInterface
{
    /**
     * @var ClassFile
     */
    protected $classFile;

    /**
     * EnumObjectBuilder constructor.
     *
     * @param string $writeDir
     * @param string $objectName
     * @param string $namespace
     */
    public function __construct(string $writeDir, string $objectName, string $namespace = self::DEFAULT_NAMESPACE)
    {
        $className = $objectName . 'EnumObject';

        $this->classFile = new ClassFile($writeDir, $className);
        $this->classFile->setNamespace($namespace);
        if ($namespace !== self::DEFAULT_NAMESPACE) {
            $this->classFile->addImport('gmostafa\\GraphQL\\SchemaObject\\EnumObject');
        }
        $this->classFile->extendsClass('EnumObject');
    }

    /**
     * @param string $valueName
     */
    public function addEnumValue(string $valueName)
    {
        $constantName = strtoupper($valueName);
        $this->classFile->addConstant($constantName, $valueName);
    }

    /**
     * @return void
     */
    public function build(): void
    {
        $this->classFile->writeFile();
    }
}
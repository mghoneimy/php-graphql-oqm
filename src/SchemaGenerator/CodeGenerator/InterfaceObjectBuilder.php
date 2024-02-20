<?php

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;
use GraphQL\Util\StringLiteralFormatter;

/**
 * Class InterfaceObjectBuilder
 *
 * @package GraphQL\SchemaGenerator\CodeGenerator
 */
class InterfaceObjectBuilder extends QueryObjectClassBuilder
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
        $className = $objectName . 'QueryObject';

        $this->classFile = new ClassFile($writeDir, $className);
        $this->classFile->setNamespace($namespace);
        if ($namespace !== self::DEFAULT_NAMESPACE) {
            $this->classFile->addImport('GraphQL\\SchemaObject\\InterfaceObject');
        }
        $this->classFile->extendsClass('InterfaceObject');
    }

    /**
     * @param string $typeName
     */
    public function addImplementation(string $typeName)
    {
        $upperCamelCaseTypeName = StringLiteralFormatter::formatUpperCamelCase($typeName);
        $objectClassName = $typeName . 'QueryObject';
        $method = "public function on$upperCamelCaseTypeName(): $objectClassName
{
    return \$this->addImplementation($objectClassName::class);
}";
        $this->classFile->addMethod($method);
    }

    /**
     * @return void
     */
    public function build(): void
    {
        $this->classFile->writeFile();
    }
}

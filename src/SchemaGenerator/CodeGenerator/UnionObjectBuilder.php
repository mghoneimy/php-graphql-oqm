<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;
use GraphQL\SchemaObject\UnionObject;
use GraphQL\Util\StringLiteralFormatter;

/**
 * Class EnumObjectBuilder.
 */
class UnionObjectBuilder extends AbstractObjectBuilder
{
    /**
     * EnumObjectBuilder constructor.
     */
    public function __construct(string $writeDir, string $objectName, string $namespace = self::DEFAULT_NAMESPACE)
    {
        $className = $objectName.'UnionObject';

        $this->classFile = new ClassFile($writeDir, $className, $namespace);
        $this->classFile->extendsClass(UnionObject::class);
    }

    public function addPossibleType(string $typeName): void
    {
        $upperCamelCaseTypeName = StringLiteralFormatter::formatUpperCamelCase($typeName);
        $objectClassName = $typeName.'QueryObject';
        $method = $this->classFile->addMethod("on$upperCamelCaseTypeName");
        $method->addBody('$object = new '.$objectClassName.'();');
        $method->addBody('$this->addPossibleType($object);');
        $method->addBody('return $object;');
    }
}

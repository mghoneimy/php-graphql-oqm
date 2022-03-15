<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;
use GraphQL\SchemaObject\InputObject;
use GraphQL\Util\StringLiteralFormatter;

/**
 * Class InputObjectClassBuilder.
 */
class InputObjectClassBuilder extends ObjectClassBuilder
{
    /**
     * SchemaObjectBuilder constructor.
     */
    public function __construct(string $writeDir, string $objectName, string $namespace = self::DEFAULT_NAMESPACE, array $interfaces = [])
    {
        $className = $objectName.'InputObject';

        $this->classFile = new ClassFile($writeDir, $className, $namespace);
        $this->classFile->extendsClass(InputObject::class);

        if ($interfaces) {
            foreach ($interfaces as $interface) {
                $this->classFile->implementsInterface($interface);
            }
        }
    }

    public function addScalarValue(string $argumentName)
    {
        $upperCamelCaseArg = StringLiteralFormatter::formatUpperCamelCase($argumentName);
        $this->addProperty($argumentName);
        $this->addScalarSetter($argumentName, $upperCamelCaseArg);
    }

    public function addListValue(string $argumentName, string $typeName)
    {
        $upperCamelCaseArg = StringLiteralFormatter::formatUpperCamelCase($argumentName);
        $this->addProperty($argumentName);
        $this->addListSetter($argumentName, $upperCamelCaseArg, $typeName);
    }

    public function addInputObjectValue(string $argumentName, string $typeName)
    {
        $typeName .= 'InputObject';
        $upperCamelCaseArg = StringLiteralFormatter::formatUpperCamelCase($argumentName);
        $this->addProperty($argumentName);
        $this->addObjectSetter($argumentName, $upperCamelCaseArg, $typeName);
    }
}

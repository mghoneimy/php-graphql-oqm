<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;
use GraphQL\SchemaObject\ArgumentsObject;
use GraphQL\Util\StringLiteralFormatter;

/**
 * Class ArgumentsObjectClassBuilder.
 */
class ArgumentsObjectClassBuilder extends ObjectClassBuilder
{
    /**
     * ArgumentsObjectClassBuilder constructor.
     */
    public function __construct(string $writeDir, string $objectName, string $namespace = self::DEFAULT_NAMESPACE)
    {
        $this->classFile = new ClassFile($writeDir, $objectName, $namespace);
        $this->classFile->extendsClass(ArgumentsObject::class);
    }

    public function addScalarArgument(string $argumentName, string $typeName): void
    {
        $lowerTypeName = strtolower($typeName);
        if ($lowerTypeName === 'boolean') {
            $lowerTypeName = 'bool';
        }

        if ($lowerTypeName === 'id') {
            $lowerTypeName = 'string';
        }

        if ($lowerTypeName === 'money') {
            $lowerTypeName = 'string';
        }

        if ($lowerTypeName === 'url') {
            $lowerTypeName = 'string';
        }

        assert(in_array($lowerTypeName, ['bool', 'int', 'float', 'string']), $lowerTypeName);
        $upperCamelCaseArg = StringLiteralFormatter::formatUpperCamelCase($argumentName);
        $this->addProperty($argumentName, $lowerTypeName);
        $this->addScalarSetter($argumentName, $upperCamelCaseArg);
    }

    /**
     * @param string string $argumentName
     * @param string string $typeName
     */
    public function addListArgument(string $argumentName, string $typeName): void
    {
        $upperCamelCaseArg = StringLiteralFormatter::formatUpperCamelCase($argumentName);
        $this->addProperty($argumentName, 'array');
        $this->addListSetter($argumentName, $upperCamelCaseArg, $typeName);
    }

    public function addInputEnumArgument(string $argumentName, string $typeName): void
    {
        $typeName .= 'EnumObject';
        $upperCamelCaseArg = StringLiteralFormatter::formatUpperCamelCase($argumentName);
        $this->addProperty($argumentName, $typeName);
        $this->addEnumSetter($argumentName, $upperCamelCaseArg, $typeName);
    }

    public function addInputObjectArgument(string $argumentName, string $typeName): void
    {
        $typeName .= 'InputObject';
        $upperCamelCaseArg = StringLiteralFormatter::formatUpperCamelCase($argumentName);
        $this->addProperty($argumentName, $typeName);
        $this->addObjectSetter($argumentName, $upperCamelCaseArg, $typeName);
    }
}

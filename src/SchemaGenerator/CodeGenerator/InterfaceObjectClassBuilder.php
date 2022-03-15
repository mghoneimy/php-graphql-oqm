<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\Enumeration\FieldTypeKindEnum;
use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\InterfaceFile;
use GraphQL\SchemaObject\QueryObject;

class InterfaceObjectClassBuilder extends QueryObjectClassBuilder
{
    /** @noinspection MagicMethodsValidityInspection*/
    public function __construct(string $writeDir, $objectName, string $namespace = self::DEFAULT_NAMESPACE)
    {
        $className = $objectName.'InterfaceObject';

        $this->classFile = new InterfaceFile($writeDir, $className, $namespace);

        // Special case for handling root query object
        if ($objectName === QueryObject::ROOT_QUERY_OBJECT_NAME) {
            $objectName = '';
        }

        $this->classFile->addConstant('OBJECT_NAME', $objectName);
    }

    protected function addScalarSetter($propertyName, $upperCamelName)
    {
        $lowerCamelName = lcfirst($upperCamelName);
        $method = $this->classFile->addMethod("set$upperCamelName");
        $method->addParameter($lowerCamelName);
    }

    protected function addListSetter(string $propertyName, string $upperCamelName, string $propertyType)
    {
        $lowerCamelName = lcfirst($upperCamelName);
        $method = $this->classFile->addMethod("set$upperCamelName");
        $method->addParameter($lowerCamelName)->setType($propertyType);
    }

    protected function addObjectSetter(string $propertyName, string $upperCamelName, string $objectClass)
    {
        $lowerCamelName = lcfirst(str_replace('_', '', $objectClass));
        $method = $this->classFile->addMethod("set$upperCamelName");
        $method->addParameter($lowerCamelName)->setType($objectClass);
    }

    protected function addEnumSetter(string $propertyName, string $upperCamelName, string $objectClass)
    {
        $lowerCamelName = lcfirst(str_replace('_', '', $objectClass));
        $method = $this->classFile->addMethod("set$upperCamelName");
        $method->addParameter($lowerCamelName)->setType($objectClass);
    }

    protected function addSimpleSelector(string $propertyName, string $upperCamelName, bool $isDeprecated, ?string $deprecationReason)
    {
        $this->classFile->addMethod("select$upperCamelName", $isDeprecated, $deprecationReason);
    }

    protected function addObjectSelector(string $fieldName, string $upperCamelName, string $fieldTypeName, FieldTypeKindEnum $fieldTypeKind, string $argsObjectName, bool $isDeprecated, ?string $deprecationReason)
    {
        $method = $this->classFile->addMethod("select$upperCamelName", $isDeprecated, $deprecationReason);
        $method->addParameter('argsObject')->setType($argsObjectName)->setDefaultValue(null);
    }
}

<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\Enumeration\FieldTypeKindEnum;
use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;
use GraphQL\SchemaObject\QueryObject;
use GraphQL\Util\StringLiteralFormatter;
use Nette\PhpGenerator\Parameter;

/**
 * Class QueryObjectClassBuilder.
 */
class QueryObjectClassBuilder extends ObjectClassBuilder
{
    /**
     * QueryObjectClassBuilder constructor.
     */
    public function __construct(string $writeDir, string $objectName, string $namespace = self::DEFAULT_NAMESPACE, array $interfaces = [])
    {
        $className = $objectName.'QueryObject';

        $this->classFile = new ClassFile($writeDir, $className, $namespace);
        $this->classFile->extendsClass(QueryObject::class);

        if ($interfaces) {
            foreach ($interfaces as $interface) {
                $this->classFile->implementsInterface($interface);
            }
        }

        // Special case for handling root query object
        if ($objectName === QueryObject::ROOT_QUERY_OBJECT_NAME) {
            $objectName = '';
        }
        $this->classFile->addConstant('OBJECT_NAME', $objectName);
    }

    public function addScalarField(string $fieldName, bool $isDeprecated, ?string $deprecationReason)
    {
        $upperCamelCaseProp = StringLiteralFormatter::formatUpperCamelCase($fieldName);
        $this->addSimpleSelector($fieldName, $upperCamelCaseProp, $isDeprecated, $deprecationReason);
    }

    public function addObjectField(string $fieldName, string $typeName, FieldTypeKindEnum $typeKind, string $argsObjectName, bool $isDeprecated, ?string $deprecationReason)
    {
        $upperCamelCaseProp = StringLiteralFormatter::formatUpperCamelCase($fieldName);
        $this->addObjectSelector($fieldName, $upperCamelCaseProp, $typeName, $typeKind, $argsObjectName, $isDeprecated, $deprecationReason);
    }

    protected function addSimpleSelector(string $propertyName, string $upperCamelName, bool $isDeprecated, ?string $deprecationReason)
    {
        $method = $this->classFile->addMethod("select$upperCamelName", $isDeprecated, $deprecationReason);
        $method->addBody('$this->selectField(?);', [$propertyName]);
        $method->addBody('return $this;');
    }

    protected function addObjectSelector(string $fieldName, string $upperCamelName, string $fieldTypeName, FieldTypeKindEnum $fieldTypeKind, string $argsObjectName, bool $isDeprecated, ?string $deprecationReason)
    {
        $objectClass = $fieldTypeName.($fieldTypeKind === FieldTypeKindEnum::UNION_OBJECT ? 'UnionObject' : 'QueryObject');

        $method = $this->classFile->addMethod("select$upperCamelName", $isDeprecated, $deprecationReason);
        $method->setParameters([
            (new Parameter('argsObject'))->setType($argsObjectName)->setDefaultValue(null),
        ]);
        $method->addBody('$object = new '.$objectClass.'(?);', [$fieldName]);
        $method->addBody('if ($argsObject !== null) {');
        $method->addBody('    $object->appendArguments($argsObject->toArray());');
        $method->addBody('}');
        $method->addBody('$this->selectField($object);');
        $method->addBody('return $object;');
    }
}

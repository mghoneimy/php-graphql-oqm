<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator;

use GraphQL\Client;
use GraphQL\Enumeration\FieldTypeKindEnum;
use GraphQL\SchemaGenerator\CodeGenerator\ArgumentsObjectClassBuilder;
use GraphQL\SchemaGenerator\CodeGenerator\EnumObjectBuilder;
use GraphQL\SchemaGenerator\CodeGenerator\InputObjectClassBuilder;
use GraphQL\SchemaGenerator\CodeGenerator\InterfaceObjectClassBuilder;
use GraphQL\SchemaGenerator\CodeGenerator\ObjectBuilderInterface;
use GraphQL\SchemaGenerator\CodeGenerator\QueryObjectClassBuilder;
use GraphQL\SchemaGenerator\CodeGenerator\UnionObjectBuilder;
use GraphQL\SchemaObject\QueryObject;
use GraphQL\Util\StringLiteralFormatter;
use RuntimeException;

/**
 * This class scans the GraphQL API schema and generates Classes that map to the schema objects' structure.
 *
 * Class SchemaClassGenerator
 */
class SchemaClassGenerator
{
    /**
     * @var SchemaInspector
     */
    protected $schemaInspector;

    /**
     * @var string
     */
    private $writeDir;

    /**
     * @var string
     */
    private $generationNamespace;

    /**
     * This array is used as a set to store the already generated objects
     * Array structure: [$objectName] => true
     *AND complete covering the schema scanner class.
     *
     * @var array
     */
    private $generatedObjects;

    /**
     * SchemaClassGenerator constructor.
     */
    public function __construct(Client $client, string $writeDir = '', string $namespace = ObjectBuilderInterface::DEFAULT_NAMESPACE)
    {
        $this->schemaInspector = new SchemaInspector($client);
        $this->generatedObjects = [];
        $this->writeDir = $writeDir;
        $this->generationNamespace = $namespace;
        $this->setWriteDir();
    }

    public function generateRootQueryObject(): bool
    {
        $objectArray = $this->schemaInspector->getQueryTypeSchema();
        $rootObjectName = QueryObject::ROOT_QUERY_OBJECT_NAME;
        $queryTypeName = $objectArray['name'];
        // $rootObjectDescr = $objectArray['description'];

        if (array_key_exists($queryTypeName, $this->generatedObjects)) {
            return true;
        }

        $this->generatedObjects[$queryTypeName] = true;

        $queryObjectBuilder = new QueryObjectClassBuilder($this->writeDir, $rootObjectName, $this->generationNamespace);
        $this->appendQueryObjectFields($queryObjectBuilder, $rootObjectName, $objectArray['fields']);
        $queryObjectBuilder->build();

        return true;
    }

    /**
     * This method receives the array of object fields as an input and adds the fields to the query object building.
     */
    private function appendQueryObjectFields(QueryObjectClassBuilder $queryObjectBuilder, string $currentTypeName, array $fieldsArray): void
    {
        foreach ($fieldsArray as $fieldArray) {
            $name = $fieldArray['name'];
            // Skip fields with name "query"
            if ($name === 'query') {
                continue;
            }

            // $description = $fieldArray['description'];
            [$typeName, $typeKind] = $this->getTypeInfo($fieldArray);
            $typeKind = FieldTypeKindEnum::from($typeKind);

            if ($typeKind === FieldTypeKindEnum::SCALAR) {
                $queryObjectBuilder->addScalarField($name, $fieldArray['isDeprecated'], $fieldArray['deprecationReason']);
            } elseif ($typeKind === FieldTypeKindEnum::ENUM_OBJECT) {
                $this->generateEnumObject($typeName);
                $queryObjectBuilder->addScalarField($name, $fieldArray['isDeprecated'], $fieldArray['deprecationReason']);
            } else {
                // Generate nested type object if it wasn't generated
                $objectGenerated = $this->generateObject($typeName, $typeKind);
                if ($objectGenerated) {
                    // Generate nested type arguments object if it wasn't generated
                    $argsObjectName = $currentTypeName.StringLiteralFormatter::formatUpperCamelCase($name).'ArgumentsObject';
                    $argsObjectGenerated = $this->generateArgumentsObject($argsObjectName, $fieldArray['args'] ?? []);
                    if ($argsObjectGenerated) {
                        // Add sub type as a field to the query object if all generation happened successfully
                        $queryObjectBuilder->addObjectField($name, $typeName, $typeKind, '\\'.$this->generationNamespace.'\\'.$argsObjectName, $fieldArray['isDeprecated'], $fieldArray['deprecationReason']);
                    }
                }
            }
        }
    }

    protected function generateObject(string $objectName, FieldTypeKindEnum $objectKind): bool
    {
        return match ($objectKind) {
            FieldTypeKindEnum::OBJECT => $this->generateQueryObject($objectName),
            FieldTypeKindEnum::INPUT_OBJECT => $this->generateInputObject($objectName),
            FieldTypeKindEnum::ENUM_OBJECT => $this->generateEnumObject($objectName),
            FieldTypeKindEnum::UNION_OBJECT => $this->generateUnionObject($objectName),
            FieldTypeKindEnum::INTERFACE_OBJECT => $this->generateInterfaceObject($objectName),
            default => false,
        };
    }

    protected function generateQueryObject(string $objectName): bool
    {
        if (array_key_exists($objectName, $this->generatedObjects)) {
            return true;
        }

        $this->generatedObjects[$objectName] = true;
        $objectArray = $this->schemaInspector->getObjectSchema($objectName);
        $objectName = $objectArray['name'];
        $implements = $objectArray['implements'] ?? [];
        $objectBuilder = new QueryObjectClassBuilder($this->writeDir, $objectName, $this->generationNamespace, $implements);

        $this->appendQueryObjectFields($objectBuilder, $objectName, $objectArray['fields']);
        $objectBuilder->build();

        return true;
    }

    protected function generateInterfaceObject(string $objectName)
    {
        if (array_key_exists($objectName, $this->generatedObjects)) {
            return true;
        }

        $this->generatedObjects[$objectName] = true;
        $objectArray = $this->schemaInspector->getObjectSchema($objectName);
        $objectName = $objectArray['name'];
        $objectBuilder = new InterfaceObjectClassBuilder($this->writeDir, $objectName, $this->generationNamespace);

        $this->appendQueryObjectFields($objectBuilder, $objectName, $objectArray['fields']);
        $objectBuilder->build();

        return true;
    }

    protected function generateInputObject(string $objectName): bool
    {
        if (array_key_exists($objectName, $this->generatedObjects)) {
            return true;
        }

        $this->generatedObjects[$objectName] = true;
        $objectArray = $this->schemaInspector->getInputObjectSchema($objectName);
        $objectName = $objectArray['name'];
        $implements = $objectArray['implements'] ?? [];
        $objectBuilder = new InputObjectClassBuilder($this->writeDir, $objectName, $this->generationNamespace, $implements);

        foreach ($objectArray['inputFields'] as $inputFieldArray) {
            $name = $inputFieldArray['name'];
            // $description = $inputFieldArray['description'];
            // $defaultValue = $inputFieldArray['defaultValue'];
            [$typeName, $typeKind, $typeKindWrappers] = $this->getTypeInfo($inputFieldArray);

            $typeKind = FieldTypeKindEnum::from($typeKind);

            $objectGenerated = true;
            if ($typeKind !== FieldTypeKindEnum::SCALAR) {
                $objectGenerated = $this->generateObject($typeName, $typeKind);
            }

            if ($objectGenerated) {
                if (in_array(FieldTypeKindEnum::LIST, $typeKindWrappers, true)) {
                    $objectBuilder->addListValue($name, $typeName);
                } elseif ($typeKind === FieldTypeKindEnum::SCALAR) {
                    $objectBuilder->addScalarValue($name);
                } elseif ($typeKind === FieldTypeKindEnum::ENUM_OBJECT) {
                    $typeName = $this->generationNamespace.'\\'.$typeName.'EnumObject';
                    $objectBuilder->addInputObjectValue($name, $typeName);
                } else {
                    $typeName = $this->generationNamespace.'\\'.$typeName;
                    $objectBuilder->addInputObjectValue($name, $typeName);
                }
            }
        }

        $objectBuilder->build();

        return true;
    }

    protected function generateEnumObject(string $objectName): bool
    {
        if (array_key_exists($objectName, $this->generatedObjects)) {
            return true;
        }

        $this->generatedObjects[$objectName] = true;

        $objectArray = $this->schemaInspector->getEnumObjectSchema($objectName);
        $objectName = $objectArray['name'];
        $objectBuilder = new EnumObjectBuilder($this->writeDir, $objectName, $this->generationNamespace);

        foreach ($objectArray['enumValues'] as $enumValue) {
            $name = $enumValue['name'];
            // $description = $enumValue['description'];
            $objectBuilder->addEnumValue($name);
        }
        $objectBuilder->build();

        return true;
    }

    protected function generateUnionObject(string $objectName): bool
    {
        if (array_key_exists($objectName, $this->generatedObjects)) {
            return true;
        }

        $this->generatedObjects[$objectName] = true;

        $objectArray = $this->schemaInspector->getUnionObjectSchema($objectName);
        $objectName = $objectArray['name'];
        $objectBuilder = new UnionObjectBuilder($this->writeDir, $objectName, $this->generationNamespace);

        foreach ($objectArray['possibleTypes'] as $possibleType) {
            $objectKind = $possibleType['kind'];
            $objectKind = FieldTypeKindEnum::from($objectKind);

            $this->generateObject($possibleType['name'], $objectKind);
            $objectBuilder->addPossibleType($possibleType['name']);
        }
        $objectBuilder->build();

        return true;
    }

    protected function generateArgumentsObject(string $argsObjectName, array $arguments): bool
    {
        if (array_key_exists($argsObjectName, $this->generatedObjects)) {
            return true;
        }

        $this->generatedObjects[$argsObjectName] = true;

        $objectBuilder = new ArgumentsObjectClassBuilder($this->writeDir, $argsObjectName, $this->generationNamespace);

        foreach ($arguments as $argumentArray) {
            $name = $argumentArray['name'];
            // $description = $inputFieldArray['description'];
            // $defaultValue = $inputFieldArray['defaultValue'];
            [$typeName, $typeKind, $typeKindWrappers] = $this->getTypeInfo($argumentArray);
            $typeKind = $typeKind instanceof FieldTypeKindEnum ? $typeKind : FieldTypeKindEnum::from($typeKind);

            $objectGenerated = true;
            if ($typeKind !== FieldTypeKindEnum::SCALAR) {
                $objectGenerated = $this->generateObject($typeName, $typeKind);
            }

            if ($objectGenerated) {
                if (in_array(FieldTypeKindEnum::LIST, $typeKindWrappers, true)) {
                    $objectBuilder->addListArgument($name, $typeName);
                } elseif ($typeKind === FieldTypeKindEnum::SCALAR) {
                    $objectBuilder->addScalarArgument($name, $typeName);
                } elseif ($typeKind === FieldTypeKindEnum::ENUM_OBJECT) {
                    $typeName = '\\'.$this->generationNamespace.'\\'.$typeName;
                    $objectBuilder->addInputEnumArgument($name, $typeName);
                } else {
                    $typeName = '\\'.$this->generationNamespace.'\\'.$typeName;
                    $objectBuilder->addInputObjectArgument($name, $typeName);
                }
            }
        }
        $objectBuilder->build();

        return true;
    }

    /**
     * @param array $dataArray : The subarray which contains the key "type"
     *
     * @return array : Array formatted as [$typeName, $typeKind, $typeKindWrappers]
     */
    protected function getTypeInfo(array $dataArray): array
    {
        $typeArray = $dataArray['type'];
        $typeWrappers = [];
        while ($typeArray['ofType'] !== null) {
            $typeWrappers[] = $typeArray['kind'] instanceof FieldTypeKindEnum ? $typeArray['kind'] : FieldTypeKindEnum::from($typeArray['kind']);
            $typeArray = $typeArray['ofType'];

            // Throw exception if next array doesn't have ofType key
            if (!array_key_exists('ofType', $typeArray)) {
                throw new RuntimeException('Reached the limit of nesting in type info');
            }
        }

        return [$typeArray['name'], $typeArray['kind'], $typeWrappers];
    }

    /**
     * Sets the write directory if it's not set for the class.
     */
    private function setWriteDir(): void
    {
        if ($this->writeDir !== '') {
            return;
        }

        $currentDir = dirname(__FILE__);
        while (basename($currentDir) !== 'php-graphql-oqm') {
            $currentDir = dirname($currentDir);
        }

        $this->writeDir = $currentDir.'/schema_object';
    }

    public function getWriteDir(): string
    {
        $this->setWriteDir();

        return $this->writeDir;
    }
}

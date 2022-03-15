<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

/**
 * Class ObjectClassBuilder.
 */
abstract class ObjectClassBuilder extends AbstractObjectBuilder
{
    protected function addProperty(string $propertyName, ?string $propertyType = null)
    {
        $this->classFile->addProperty($propertyName, $propertyType);
    }

    /**
     * @param string $propertyName
     * @param string $upperCamelName
     */
    protected function addScalarSetter($propertyName, $upperCamelName)
    {
        $lowerCamelName = lcfirst($upperCamelName);
        $method = $this->classFile->addMethod("set$upperCamelName");
        $method->addParameter($lowerCamelName);
        $method->addBody('$this->? = $?;', [$propertyName, $lowerCamelName]);
        $method->addBody('return $this;');
    }

    protected function addListSetter(string $propertyName, string $upperCamelName, string $propertyType)
    {
        $lowerCamelName = lcfirst($upperCamelName);
        $method = $this->classFile->addMethod("set$upperCamelName");
        $method->addParameter($lowerCamelName)->setType('array');
        $method->addBody('$this->? = $?;', [$propertyName, $lowerCamelName]);
        $method->addBody('return $this;');
    }

    protected function addEnumSetter(string $propertyName, string $upperCamelName, string $objectClass)
    {
        $this->addObjectSetter($propertyName, $upperCamelName, $objectClass);
    }

    protected function addObjectSetter(string $propertyName, string $upperCamelName, string $objectClass)
    {
        $method = $this->classFile->addMethod("set$upperCamelName");
        $method->addParameter($propertyName)->setType($objectClass);
        $method->addBody('$this->? = $?;', [$propertyName, $propertyName]);
        $method->addBody('return $this;');
    }
}

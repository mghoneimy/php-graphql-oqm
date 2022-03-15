<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\EnumFile;

/**
 * Class EnumObjectBuilder.
 */
class EnumObjectBuilder extends AbstractObjectBuilder
{
    public function __construct(string $writeDir, string $objectName, string $namespace = self::DEFAULT_NAMESPACE)
    {
        $className = $objectName.'EnumObject';

        $this->classFile = new EnumFile($writeDir, $className, $namespace);
    }

    public function addEnumValue(string $valueName)
    {
        $constantName = strtoupper($valueName);
        $this->classFile->addConstant($constantName, $valueName);
    }
}

<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

use GraphQL\SchemaGenerator\CodeGenerator\CodeFile\ClassFile;

abstract class AbstractObjectBuilder implements ObjectBuilderInterface
{
    protected ClassFile $classFile;

    public function build(): void
    {
        $this->classFile->writeFile();
    }
}

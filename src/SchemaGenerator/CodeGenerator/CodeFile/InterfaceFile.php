<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator\CodeFile;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\InterfaceType;
use Nette\PhpGenerator\PhpNamespace;

class InterfaceFile extends ClassFile
{
    /** @var InterfaceType */
    protected ClassLike $classLike;

    protected function createClassLikeClass(string $className, ?string $namespace = ''): ClassLike
    {
        return new InterfaceType($className, new PhpNamespace($namespace));
    }
}

<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator\CodeFile;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;

class EnumFile extends ClassFile
{
    /** @var EnumType */
    protected ClassLike $classLike;

    protected function createClassLikeClass(string $className, ?string $namespace = ''): ClassLike
    {
        return new EnumType($className, new PhpNamespace($namespace));
    }

    public function addConstant(string $name, $value): void
    {
        $this->classLike->addCase($name, $value);
    }
}

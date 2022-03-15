<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator\CodeFile;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Class ClassFile.
 */
class ClassFile extends TraitFile
{
    /** @var ClassType */
    protected ClassLike $classLike;

    protected function createClassLikeClass(string $className, ?string $namespace = ''): ClassLike
    {
        return new ClassType($className, new PhpNamespace($namespace));
    }
}

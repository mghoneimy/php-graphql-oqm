<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator\CodeFile;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\TraitType;

/**
 * Class TraitFile.
 */
class TraitFile extends AbstractCodeFile
{
    /** @var TraitType */
    protected ClassLike $classLike;

    protected function createClassLikeClass(string $className, ?string $namespace = ''): ClassLike
    {
        return new TraitType($className, new PhpNamespace($namespace));
    }

    public function addTrait(string $name)
    {
        $this->classLike->addTrait($name);
    }

    public function addProperty(string $propertyName, mixed $defaultValue = null, ?string $propertyType = null): void
    {
        $property = $this->classLike->addProperty($propertyName)->setType($propertyType)->setVisibility(ClassLike::VisibilityProtected);
        if ($defaultValue !== null) {
            $property->setValue($defaultValue);
        }
    }

    public function addMethod(string $methodName, bool $isDeprecated = false, ?string $deprecationReason = ''): Method
    {
        $method = $this->classLike->addMethod($methodName);
        if ($isDeprecated) {
            $method->addComment('@deprecated '.$deprecationReason);
        }

        return $method;
    }
}

<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator\CodeFile;

use JetBrains\PhpStorm\Pure;
use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\Method;
use RuntimeException;

/**
 * Class AbstractCodeFile.
 */
abstract class AbstractCodeFile implements CodeFileInterface
{
    /**
     * This string stores the name of this file.
     */
    protected string $className;

    private string $writeDir;

    protected ClassLike $classLike;

    /**
     * AbstractCodeFile constructor.
     */
    public function __construct(string $writeDir, string $className, ?string $namespace = '')
    {
        $this->validateDirectory($writeDir);
        $this->writeDir = $writeDir;

        $this->classLike = $this->createClassLikeClass($className, $namespace);
    }

    abstract protected function createClassLikeClass(string $className, ?string $namespace = ''): ClassLike;

    #[Pure]
    protected function generateFileContents(): string
    {
        return '<?php'.PHP_EOL.PHP_EOL.$this->classLike->getNamespace().' '.$this->classLike;
    }

    public function extendsClass(string $className): void
    {
        $this->classLike->setExtends($className);
    }

    public function addConstant(string $name, $value): void
    {
        $this->classLike->addConstant($name, $value);
    }

    public function addMethod(string $methodName, bool $isDeprecated = false, ?string $deprecationReason = ''): Method
    {
        $method = $this->classLike->addMethod($methodName);
        if ($isDeprecated) {
            $method->addComment('@deprecated '.$deprecationReason);
        }

        return $method;
    }

    public function addProperty(string $propertyName, ?string $propertyType = null): void
    {
        $this->classLike->addProperty($propertyName)->setType($propertyType);
    }

    public function addImport(string $className): void
    {
        $namespace = $this->classLike->getNamespace();
        $namespace?->addUse($className);
    }

    private function validateDirectory(string $dirName): void
    {
        if (!is_dir($dirName)) {
            throw new RuntimeException("$dirName is not a valid directory");
        }
        if (!is_writable($dirName)) {
            throw new RuntimeException("$dirName is not writable");
        }
    }

    /**
     * {@inheritdoc}
     */
    final public function writeFile(): bool
    {
        $fileContents = $this->generateFileContents();

        $filePath = $this->writeDir;
        if (!str_ends_with($filePath, '/')) {
            $filePath .= '/';
        }
        $filePath .= $this->classLike->getName().'.php';

        return $this->writeFileToPath($fileContents, $filePath);
    }

    private function writeFileToPath(string $fileContents, string $filePath): bool
    {
        return file_put_contents($filePath, $fileContents) !== false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getWriteDir(): string
    {
        return $this->writeDir;
    }

    public function changeWriteDir(string $writeDir)
    {
        $this->validateDirectory($writeDir);

        $this->writeDir = $writeDir;
    }

    public function getWritePath(): string
    {
        return $this->writeDir."/$this->className.php";
    }
}

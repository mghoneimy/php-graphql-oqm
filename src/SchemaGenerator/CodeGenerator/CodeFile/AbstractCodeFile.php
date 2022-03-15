<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator\CodeFile;

use JetBrains\PhpStorm\Pure;
use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use RuntimeException;

/**
 * Class AbstractCodeFile.
 */
abstract class AbstractCodeFile implements CodeFileInterface
{
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
        $file = new PhpFile();
        $printer = new PsrPrinter();
        $namespace = $this->classLike->getNamespace();
        $namespace?->add($this->classLike);
        $file->addNamespace($namespace);

        return $printer->printFile($file);
    }

    public function extendsClass(string $className): void
    {
        $this->classLike->setExtends($className);
    }

    public function addConstant(string $name, $value): void
    {
        $this->classLike->addConstant($name, $value);
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
        return $this->classLike->getName();
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

    #[Pure]
    public function getWritePath(): string
    {
        return $this->writeDir."/{$this->classLike->getName()}.php";
    }
}

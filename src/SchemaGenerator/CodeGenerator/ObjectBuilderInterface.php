<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator\CodeGenerator;

/**
 * Interface ObjectBuilderInterface.
 */
interface ObjectBuilderInterface
{
    public const DEFAULT_NAMESPACE = 'GraphQL\\SchemaObject';

    public function build(): void;
}

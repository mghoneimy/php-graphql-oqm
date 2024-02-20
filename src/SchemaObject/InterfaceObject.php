<?php

namespace GraphQL\SchemaObject;

use GraphQL\InlineFragment;

/**
 * Class InterfaceObject
 *
 * @package GraphQL\SchemaObject
 */
abstract class InterfaceObject extends QueryObject
{
    private $implementations = [];

    protected function addImplementation(string $implementationTypeClassName)
    {
        if (!isset($this->implementations[$implementationTypeClassName])) {
            $implementationType = new $implementationTypeClassName();
            $fragment = new InlineFragment($implementationType::OBJECT_NAME, $implementationType);
            $this->selectField($fragment);
            $this->implementations[$implementationTypeClassName] = $implementationType;
        }

        return $this->implementations[$implementationTypeClassName];
    }
}

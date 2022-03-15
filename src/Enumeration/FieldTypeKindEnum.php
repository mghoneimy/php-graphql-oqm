<?php

declare(strict_types=1);

namespace GraphQL\Enumeration;

/**
 * Class FieldTypeKindEnum.
 */
enum FieldTypeKindEnum: string
{
    case SCALAR = 'SCALAR';
    case LIST = 'LIST';
    case NON_NULL = 'NON_NULL';
    case OBJECT = 'OBJECT';
    case INPUT_OBJECT = 'INPUT_OBJECT';
    case ENUM_OBJECT = 'ENUM';
    case UNION_OBJECT = 'UNION';
    case INTERFACE_OBJECT = 'INTERFACE';
}

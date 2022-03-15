<?php

namespace GraphQL\Tests\SchemaObject;

enum WithMultipleConstantsEnumObject: string
{
    case SOME_VALUE = 'some_value';
    case ANOTHER_VALUE = 'another_value';
    case ONEMOREVALUE = 'oneMoreValue';
}

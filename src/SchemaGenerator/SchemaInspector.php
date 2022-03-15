<?php

declare(strict_types=1);

namespace GraphQL\SchemaGenerator;

use GraphQL\Client;

/**
 * Class SchemaInspector.
 *
 * @codeCoverageIgnore
 */
class SchemaInspector
{
    private const TYPE_SUB_QUERY = <<<QUERY
type{
  name
  kind
  description
  ofType{
    name
    kind
    ofType{
      name
      kind
      ofType{
        name
        kind
        ofType{
          name
          kind
        }
      }
    }
  }
}
QUERY;

    /**
     * @var Client
     */
    protected $client;

    /**
     * SchemaInspector constructor.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getQueryTypeSchema(): array
    {
        $schemaQuery = '
query IntrospectionQuery {
  __schema {
    queryType {
      ...FullType
    }
    mutationType {
      ...FullType
    }
    subscriptionType {
      ...FullType
    }
    types {
      ...FullType
    }
    directives {
      name
      description
      locations
      args {
        ...InputValue
      }
    }
  }
}
fragment FullType on __Type {
  kind
  name
  description
  fields(includeDeprecated: true) {
    name
    description
    args {
      ...InputValue
    }
    type {
      ...TypeRef
    }
    isDeprecated
    deprecationReason
  }
  inputFields {
    ...InputValue
  }
  interfaces {
    ...TypeRef
  }
  enumValues(includeDeprecated: true) {
    name
    description
    isDeprecated
    deprecationReason
  }
  possibleTypes {
    ...TypeRef
  }
}

fragment InputValue on __InputValue {
  name
  description
  type {
    ...TypeRef
  }
  defaultValue
}

fragment TypeRef on __Type {
  kind
  name
  ofType {
    kind
    name
    ofType {
      kind
      name
      ofType {
        kind
        name
        ofType {
          kind
          name
          ofType {
            kind
            name
            ofType {
              kind
              name
              ofType {
                kind
                name
              }
            }
          }
        }
      }
    }
  }
}';
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__schema']['queryType'];
    }

    public function getObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    fields(includeDeprecated: true){
      name
      description
      isDeprecated
      deprecationReason
      ".static::TYPE_SUB_QUERY.'
      args{
        name
        description
        defaultValue
        '.static::TYPE_SUB_QUERY.'
      }
    }
  }
}';
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];
    }

    public function getInputObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    inputFields {
      name
      description
      defaultValue
      ".static::TYPE_SUB_QUERY.'
    }
  }
}';
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];
    }

    public function getEnumObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    enumValues {
      name
      description
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];
    }

    public function getUnionObjectSchema(string $objectName): array
    {
        $schemaQuery = "{
  __type(name: \"$objectName\") {
    name
    kind
    possibleTypes {
      kind
      name
    }
  }
}";
        $response = $this->client->runRawQuery($schemaQuery, true);

        return $response->getData()['__type'];
    }
}

# PHP GraphQL OQM
![Build Status](https://github.com/mghoneimy/php-graphql-oqm/actions/workflows/php.yml/badge.svg)

This package utilizes the introspection feature of GraphQL APIs to generate a set of classes that map to the structure
of the API schema. The generated classes can then be used in a very simple and intuitive way to query the API server.

Interacting with GraphQL API's using PHP has never been easier!

# Installation
Run the following command to install the package using composer:
```
composer require gmostafa/php-graphql-oqm
```

# Generating The Schema Objects
After installing the package, the first step is to generate the schema objects. This can be easily achieved by executing
the following command:
```
php vendor/bin/generate_schema_objects
```
This script will retrieve the API schema types using the introspection feature in GraphQL, then generate the schema
objects from the types, and save them in the `schema_object` directory in the root directory of the package. You can
override the default write directory by providing the "Custom classes writing dir" value when running the command.

# Usage
In all the examples below I'm going to use the super cool public Pokemon GraphQL API as an illustration.

Check out the API at: https://graphql-pokemon.now.sh/

And Github Repo: https://github.com/lucasbento/graphql-pokemon

After generating the schema objects for the public Pokemon API, we can easily query the API by using the
`RootQueryObject`. Here's an example:
```
$rootObject = new RootQueryObject();
$rootObject
    ->selectPokemons((new RootPokemonsArgumentsObject())->setFirst(5))
        ->selectName()
        ->selectId()
        ->selectFleeRate()
        ->selectAttacks()
            ->selectFast()
                ->selectName();
```
What this query does is that it selects the first 5 pokemons returning their names, ids, flee rates, fast attacks with
their names. Easy right!?

All what remains is that we actually run the query to obtain results:
```
$results = $client->runQuery($rootObject->getQuery());
``` 
For more on how to use the client class refer to:
- https://github.com/mghoneimy/php-graphql-client#constructing-the-client
- https://github.com/mghoneimy/php-graphql-client#running-queries

## Notes
A couple of notes about schema objects to make your life easier when using the generating classes:

### Dealing With Object Selectors
Whilst scalar field setters return an instance of the current query object, object field selectors return objects of
the nested query object. This means that setting the `$rootObject` reference to the result returned by an object
selector means that the root query object reference is gone.

Don't:
```
$rootObject = (new RootQueryObject())->selectAttacks()->selectSpecial()->selectName();
```
This way you end up with reference to the `PokemonAttackQueryObject`, and the reference to the `RootQueryObject` is gone.

Do:
```
$rootObjet = new RootQueryObject();
$rootObject->selectAttacks()->selectSpecial()->selectName();
```
This way you can keep track of the `RootQueryObject` reference and develop your query safely.

### Dealing With Multiple Object Selectors
Suppose we want to get the pokemon "Charmander", retrieve his evolutions, evolution requirements, and evolution
requirements of his evolutions, how can we do that?

We can't do this:
```
$rootObject = new RootQueryObject();
$rootObject->selectPokemon(
    (new RootPokemonArgumentsObject())->setName('charmander')
)
    ->selectEvolutions()
        ->selectName()
        ->selectNumber()
        ->selectEvolutionRequirements()
            ->selectName()
            ->selectAmount()
    ->selectEvolutionRequirements()
        ->selectName()
        ->selectAmount();
```
This is because the reference is now pointing to the evolution requirements of the evolutions of charmander and not
charmander himself.

The best way to do this is by structuring the query like this:
```
$rootObject = new RootQueryObject();
$charmander = $rootObject->selectPokemon(
    (new RootPokemonArgumentsObject())->setName('charmander')
);
$charmander->selectEvolutions()
    ->selectName()
    ->selectNumber()
    ->selectEvolutionRequirements()
        ->selectName()
        ->selectAmount();
$charmander->selectEvolutionRequirements()
    ->selectName()
    ->selectAmount();
```
This way we have kept the reference to charmander safe and constructed our query in an intuitive way.

Generally, whenever there's a branch off (just like in the case of getting evolutions and evolution requirements of the
same object) the best way to do it is to structure the query like a tree, where the root of the tree becomes the
reference to the object being branch off from. In this case, charmander is the root and evolutions and evolution
requirements are 2 sub-trees branched off it.

### Improving Query Objects Readability
A couple of hints on how to keep your query objects more readable:
1. Store nodes that will be used as roots in branch offs in meaningful variables, just like the case with charmander.
2. Write each selector on a separate line.
3. Every time you use an object selector, add an extra indentation to the next selectors.
4. Move construction of a new object in the middle of a query (such as an ArgumentsObject construction) to a new line.

# Schema Objects Generation
After running the generation script, the SchemaInspector will run queries on the GraphQL server to retrieve the API
schema. After that, the SchemaClassGenerator will traverse the schema from the root queryType recursively, creating
a class for every object in the schema spec.

The SchemaClassGenerator will generate a different schema object depending on the type of object being scanned using the
following mapping from GraphQL types to SchemaObject types:
- OBJECT: `QueryObject`
- INPUT_OBJECT: `InputObject`
- ENUM: `EnumObject`

Additionally, an `ArgumentsObject` will be generated for the arguments on each field in every object. The arguments
object naming convention is:

`{CURRENT_OBJECT}{FIELD_NAME}ArgumentsObject`

## The QueryObject
The object generator will start traversing the schema from the root `queryType`, creating a class for each query object
it encounters according to the following rules:
- The `RootQueryObject` is generated for the type corresponding to the `queryType` in the schema declaration, this
object is the start of all GraphQL queries.
- For a query object of name {OBJECT_NAME}, a class with name `{OBJECT_NAME}QueryObject` will be created.
- For each selection field in the selection set of the query object, a corresponding selector method will be created,
according to the following rules:
  - Scalar fields will have a simple selector created for them, which will add the field name to the selection set.
  The simple selector will return a reference to the query object being created (this).
  - Object fields will have an object selector created for them, which will create a new query object internally and
  nest it inside the current query. The object selector will return instance of the new query object created.
- For every list of arguments tied to an object field an `ArgumentsObject` will be created with a setter corresponding
to every argument value according to the following rules:
  - Scalar arguments: will have a simple setter created for them to set the scalar argument value.
  - List arguments: will have a list setter created for them to set the argument value with an `array`
  - Input object arguments: will have an input object setter created for them to set the argument value with an object
  of type `InputObject`

## The InputObject
For every input object the object generator encounters while traversing the schema, it will create a corresponding class
according to the following rules:
- For an input object of name {OBJECT_NAME}, a class with name `{OBJECT_NAME}InputObject` will be created
- For each field in the input object declaration, a setter will be created according to the following rules:
  - Scalar fields: will have a simple setter created for them to set the scalar value.
  - List fields: will have a list setter created for them to set the value with an `array`
  - Input object arguments: will have an input object setter created for them to set the value with an object of type
  `InputObject`

## The EnumObject
For every enum the object generator encounters while traversing the schema, it will create a corresponding ENUM class
according to the following rules:
- For an enum object of name {OBJECT_NAME}, a class with name `{OBJECT_NAME}EnumObject` will be created
- For each EnumValue in the ENUM declaration, a const will be created to hold its value in the class

# Live API Example
Looking at the schema of the Pokemon GraphQL API from the root queryType, that' how it looks like:
```
"queryType": {
  "name": "Query",
  "kind": "OBJECT",
  "description": "Query any Pokémon by number or name",
  "fields": [
    {
      "name": "query",
      "type": {
        "name": "Query",
        "kind": "OBJECT",
        "ofType": null
      },
      "args": []
    },
    {
      "name": "pokemons",
      "type": {
        "name": null,
        "kind": "LIST",
        "ofType": {
          "name": "Pokemon",
          "kind": "OBJECT"
        }
      },
      "args": [
        {
          "name": "first",
          "description": null
        }
      ]
    },
    {
      "name": "pokemon",
      "type": {
        "name": "Pokemon",
        "kind": "OBJECT",
        "ofType": null
      },
      "args": [
        {
          "name": "id",
          "description": null
        },
        {
          "name": "name",
          "description": null
        }
      ]
    }
  ]
}
```
What we basically have is a root query object with 2 fields:
1. pokemons: Retrieves a list of Pokemon objects. It has one argument: first.
2. pokemon: Retrieves one Pokemon object. It has two arguments:: id and name.

Translating this small part of the schema leads to 3 objects:
1. RootQueryObject: Represents the entry to point to traversing the API graph
2. RootPokemonsArgumentsObject: Represents the arguments list on the "pokemons" field in the RootQueryObject
3. RootPokemonArgumentsObject: Represents the arguments list on the "pokemon" field in the RootQueryObject

Here are the 3 classes generated:
```
<?php

namespace GraphQL\SchemaObject;

class RootQueryObject extends QueryObject
{
    const OBJECT_NAME = "query";

    public function selectPokemons(RootPokemonsArgumentsObject $argsObject = null)
    {
        $object = new PokemonQueryObject("pokemons");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
    
        return $object;
    }

    public function selectPokemon(RootPokemonArgumentsObject $argsObject = null)
    {
        $object = new PokemonQueryObject("pokemon");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
    
        return $object;
    }
}
```
The `RootQueryObject` contains 2 selector methods, one for each field, and an optional argument containing the
ArgumentsObjects required.

```
<?php

namespace GraphQL\SchemaObject;

class RootPokemonsArgumentsObject extends ArgumentsObject
{
    protected $first;

    public function setFirst($first)
    {
        $this->first = $first;
    
        return $this;
    }
}
```
The `RootPokemonsArgumentsObject` contains the only argument in the list for the "pokemons" field as a property with a
setter for altering its value. 

```
<?php

namespace GraphQL\SchemaObject;

class RootPokemonArgumentsObject extends ArgumentsObject
{
    protected $id;
    protected $name;

    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }
}
```
The `RootPokemonArgumentsObject` contains the 2 arguments in the list for the "pokemon" field as properties with setters
to alter their values.

## Extra
Additionally, `PokemonQueryObject` will be created while traversing the schema recursively. It is not needed to complete
this demo, but I will add it below to make things clearer in case someone wants to see more of the generation in action:
```
<?php

namespace GraphQL\SchemaObject;

class PokemonQueryObject extends QueryObject
{
    const OBJECT_NAME = "Pokemon";

    public function selectId()
    {
        $this->selectField("id");
    
        return $this;
    }

    public function selectNumber()
    {
        $this->selectField("number");
    
        return $this;
    }

    public function selectName()
    {
        $this->selectField("name");
    
        return $this;
    }

    public function selectWeight(PokemonWeightArgumentsObject $argsObject = null)
    {
        $object = new PokemonDimensionQueryObject("weight");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
    
        return $object;
    }

    public function selectHeight(PokemonHeightArgumentsObject $argsObject = null)
    {
        $object = new PokemonDimensionQueryObject("height");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
    
        return $object;
    }

    public function selectClassification()
    {
        $this->selectField("classification");
    
        return $this;
    }

    public function selectTypes()
    {
        $this->selectField("types");
    
        return $this;
    }

    public function selectResistant()
    {
        $this->selectField("resistant");
    
        return $this;
    }

    public function selectAttacks(PokemonAttacksArgumentsObject $argsObject = null)
    {
        $object = new PokemonAttackQueryObject("attacks");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
    
        return $object;
    }

    public function selectWeaknesses()
    {
        $this->selectField("weaknesses");
    
        return $this;
    }

    public function selectFleeRate()
    {
        $this->selectField("fleeRate");
    
        return $this;
    }

    public function selectMaxCP()
    {
        $this->selectField("maxCP");
    
        return $this;
    }

    public function selectEvolutions(PokemonEvolutionsArgumentsObject $argsObject = null)
    {
        $object = new PokemonQueryObject("evolutions");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
    
        return $object;
    }

    public function selectEvolutionRequirements(PokemonEvolutionRequirementsArgumentsObject $argsObject = null)
    {
        $object = new PokemonEvolutionRequirementQueryObject("evolutionRequirements");
        if ($argsObject !== null) {
            $object->appendArguments($argsObject->toArray());
        }
        $this->selectField($object);
    
        return $object;
    }

    public function selectMaxHP()
    {
        $this->selectField("maxHP");
    
        return $this;
    }

    public function selectImage()
    {
        $this->selectField("image");
    
        return $this;
    }
}
```
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GraphQL\Client;
use GraphQL\Exception\QueryError;
use GraphQL\SchemaObject\RootPokemonArgumentsObject;
use GraphQL\SchemaObject\RootQueryObject;

// Create Client object to contact the GraphQL endpoint
$client = new Client(
    'https://graphql-pokemon.now.sh/',
    []  // Replace with array of extra headers to be sent with request for auth or other purposes
);

// Create root query object
$pokemon = (new RootQueryObject())
    ->selectPokemon((new RootPokemonArgumentsObject())->setName('Pikachu'))
        ->selectId()
        ->selectNumber()
        ->selectName()
        ->selectAttacks()
            ->selectSpecial()
                ->selectName()
                ->selectType()
                ->selectDamage()
            ->end()
        ->end()
        ->selectEvolutions()
            ->selectId()
            ->selectNumber()
            ->selectName()
            ->selectAttacks()
                ->selectFast()
                    ->selectName()
                    ->selectType()
                    ->selectDamage()
                ->end()
            ->end()
        ->end();

// Run query to get results
try {
    $results = $client->runQuery($pokemon->getQuery());
}
catch (QueryError $exception) {

    // Catch query error and desplay error details
    print_r($exception->getErrorDetails());
    exit;
}

// Display original response from endpoint
var_dump($results->getResponseObject());

// Display part of the returned results of the object
var_dump($results->getData()->pokemon);

// Reformat the results to an array and get the results of part of the array
$results->reformatResults(true);
print_r($results->getData()['pokemon']);

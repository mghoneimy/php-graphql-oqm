<?php

namespace GraphQL\Test;

use GraphQL\Client;
use GraphQL\Query;

trait TraitWithEverything
{
    protected $propOne;
    protected $propTwo = 'bool';

    public function getProperties()
    {
        return [$this->propOne, $this->propTwo];
    }

    public function clearProperties()
    {
        $this->propOne = 1;
        $this->propTwo = 2;
    }
}

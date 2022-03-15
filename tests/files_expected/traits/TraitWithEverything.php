<?php

declare(strict_types=1);

namespace GraphQL\Test;

trait TraitWithEverything
{
    protected $propOne;
    protected $propTwo = true;

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

<?php

declare(strict_types=1);

namespace GraphQl\Test;

use GraphQl\Base\Base;
use GraphQl\Base\Trait1;
use GraphQl\Base\Trait2;
use GraphQl\Interfaces\Intr1;
use GraphQl\Interfaces\Intr2;

class ClassWithEverything extends Base implements Intr1, Intr2
{
    use Trait1;
    use Trait2;

    public const CONST_ONE = 1;
    public const CONST_TWO = '';

    protected $propertyOne;
    protected $propertyTwo = '';

    public function dumpAll()
    {
        echo 'dumping';
    }

    protected function internalStuff($i)
    {
        return ++$i;
    }
}

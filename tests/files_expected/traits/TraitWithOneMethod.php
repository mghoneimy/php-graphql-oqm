<?php

declare(strict_types=1);

trait TraitWithOneMethod
{
    public function testTheTrait()
    {
        echo 'test!';
        exit();
    }
}

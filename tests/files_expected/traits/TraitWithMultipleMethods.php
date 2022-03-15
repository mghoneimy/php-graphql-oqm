<?php

declare(strict_types=1);

trait TraitWithMultipleMethods
{
    public function testTheTrait()
    {
        $this->innerTest();
        exit();
    }

    /**
     * @deprecated is deprecated
     */
    private function innerTest()
    {
        echo 'test!';

        return 0;
    }
}

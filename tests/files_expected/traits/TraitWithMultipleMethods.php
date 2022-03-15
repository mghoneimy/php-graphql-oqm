<?php

trait TraitWithMultipleMethods
{
    public function testTheTrait()
    {
        $this->innerTest();
        die();
    }

    /**
     * @deprecated is deprecated
     */
    private function innerTest()
    {
        print "test!";
        die();
    }
}

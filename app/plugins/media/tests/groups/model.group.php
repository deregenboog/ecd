<?php

class AllModelGroupTest extends GroupTest
{
    public $label = 'All model and behavior related test cases';

    public function AllModelGroupTest()
    {
        TestManager::addTestCasesFromDirectory($this, dirname(__FILE__).DS.'..'.DS.'cases'.DS.'models');
    }
}

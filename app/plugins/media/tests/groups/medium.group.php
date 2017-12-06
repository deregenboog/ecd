<?php

class AllMediumGroupTest extends GroupTest
{
    public $label = 'All medium related (incl. adapters) test cases';

    public function AllMediumGroupTest()
    {
        TestManager::addTestCasesFromDirectory($this, dirname(__FILE__).DS.'..'.DS.'cases'.DS.'vendors'.DS.'medium');
    }
}

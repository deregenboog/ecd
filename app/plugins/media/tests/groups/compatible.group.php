<?php

class AllCompatibleGroupTest extends GroupTest
{
    public $label = 'All test cases which can run in a sequence';

    public function AllCompatibleGroupTest()
    {
        $cases = dirname(__FILE__).DS.'..'.DS.'cases'.DS;
        TestManager::addTestCasesFromDirectory($this, $cases.DS.'models'.DS.'behaviors');
        TestManager::addTestCasesFromDirectory($this, $cases.DS.'vendors');
    }
}

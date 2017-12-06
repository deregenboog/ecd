<?php

class AllMimeGroupTest extends GroupTest
{
    public $label = 'All MIME related test cases';

    public function AllMimeGroupTest()
    {
        TestManager::addTestFile($this, dirname(__FILE__).DS.'..'.DS.'cases'.DS.'vendors'.DS.'mime_glob');
        TestManager::addTestFile($this, dirname(__FILE__).DS.'..'.DS.'cases'.DS.'vendors'.DS.'mime_magic');
        TestManager::addTestFile($this, dirname(__FILE__).DS.'..'.DS.'cases'.DS.'vendors'.DS.'mime_type');
    }
}

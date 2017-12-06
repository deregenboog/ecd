<?php

class Movie extends CakeTestModel
{
    public $name = 'Movie';
    public $useTable = 'movies';
    public $hasMany = ['Actor'];
}

class Actor extends CakeTestModel
{
    public $name = 'Actor';
    public $useTable = 'actors';
    public $belongsTo = ['Movie'];
}

class Unicorn extends CakeTestModel
{
    public $name = 'Unicorn';
    public $useTable = false;
    public $beforeMakeArgs = [];

    public function beforeMake()
    {
        $this->beforeMakeArgs[] = func_get_args();

        return false;
    }
}

class Pirate extends CakeTestModel
{
    public $name = 'Pirate';
    public $useTable = 'pirates';
}

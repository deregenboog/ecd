<?php

namespace CakeBundle\Service;

class CakeConfiguration
{
    private $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function set($key, $value = null)
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function all()
    {
        return $this->data;
    }
}

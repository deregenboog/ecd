<?php

namespace Tests\InloopBundle\Service;

class StrategyContainer
{
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        $this->strategies = $strategies;
    }

    public function getStrategies(): iterable
    {
        return $this->strategies;
    }
}

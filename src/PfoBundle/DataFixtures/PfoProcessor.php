<?php

namespace PfoBundle\DataFixtures;


use Fidry\AliceDataFixtures\ProcessorInterface;

final class PfoProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function preProcess(string $fixtureId, $object): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $fixtureId, $object): void
    {
    }
}

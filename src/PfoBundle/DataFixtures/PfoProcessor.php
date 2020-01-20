<?php

namespace PfoBundle\DataFixtures;


use Fidry\AliceDataFixtures\ProcessorInterface;

final class PfoProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function preProcess($fixtureId, $object): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public function postProcess($fixtureId, $object): void
    {
    }
}

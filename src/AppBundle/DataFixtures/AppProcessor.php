<?php

namespace AppBundle\DataFixtures;


use Fidry\AliceDataFixtures\ProcessorInterface;

final class AppProcessor implements ProcessorInterface
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

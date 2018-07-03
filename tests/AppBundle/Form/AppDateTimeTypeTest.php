<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\AppDateTimeType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;

class AppDateTimeTypeTest extends KernelTestCase
{
    public function testTwoDigitYearResultsInCorrectDate()
    {
        self::bootKernel();
        $container = static::$kernel->getContainer();

        /** @var $form FormInterface */
        $form = $container->get('form.factory')->create(AppDateTimeType::class);
        $form->submit([
            'date' => '23-11-17',
            'time' => '10:12',
        ]);

        $this->assertEquals(new \DateTime('2017-11-23 10:12:00'), $form->getData());
    }

    public function testFourDigitYearResultsInCorrectDate()
    {
        self::bootKernel();
        $container = static::$kernel->getContainer();

        /** @var $form FormInterface */
        $form = $container->get('form.factory')->create(AppDateTimeType::class);
        $form->submit([
            'date' => '23-11-1917',
            'time' => '10:12',
        ]);

        $this->assertEquals(new \DateTime('1917-11-23 10:12:00'), $form->getData());
    }
}

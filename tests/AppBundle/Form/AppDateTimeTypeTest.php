<?php

declare(strict_types=1);

namespace Tests\AppBundle\Form;

use AppBundle\Form\AppDateTimeType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;

class AppDateTimeTypeTest extends TestCase
{
    public function testTwoDigitYearResultsInCorrectDate()
    {
        /** @var $form FormInterface */
        $form = Forms::createFormFactory()->create(AppDateTimeType::class);
        $form->submit([
            'date' => '23-11-17',
            'time' => '10:12',
        ]);

        $this->assertEquals(new \DateTime('2017-11-23 10:12:00'), $form->getData());
    }

    public function testFourDigitYearResultsInCorrectDate()
    {
        /** @var $form FormInterface */
        $form = Forms::createFormFactory()->create(AppDateTimeType::class);
        $form->submit([
            'date' => '23-11-1917',
            'time' => '10:12',
        ]);

        $this->assertEquals(new \DateTime('1917-11-23 10:12:00'), $form->getData());
    }
}

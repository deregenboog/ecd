<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\AppDateType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;

class AppDateTypeTest extends TestCase
{
    public function testTwoDigitYearResultsInCorrectDate()
    {
        /** @var $form FormInterface */
        $form = Forms::createFormFactory()->create(AppDateType::class);
        $form->submit('23-11-17');

        $this->assertEquals(new \DateTime('2017-11-23 00:00:00'), $form->getData());
    }

    public function testFourDigitYearResultsInCorrectDate()
    {
        /** @var $form FormInterface */
        $form = Forms::createFormFactory()->create(AppDateType::class);
        $form->submit('23-11-1917');

        $this->assertEquals(new \DateTime('1917-11-23 00:00:00'), $form->getData());
    }
}

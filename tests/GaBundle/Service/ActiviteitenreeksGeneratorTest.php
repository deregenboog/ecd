<?php

namespace Tests\GaBundle\Service;

use AppBundle\Form\Model\AppDateRangeModel;
use GaBundle\Entity\Activiteit;
use GaBundle\Form\ActiviteitenReeksModel;
use GaBundle\Service\ActiviteitenreeksGenerator;
use PHPUnit\Framework\TestCase;

class ActiviteitenreeksGeneratorTest extends TestCase
{
    public function testGeneratorGeneratesCorrectNames()
    {
        $activiteit = new Activiteit();
        $activiteit->setNaam('Activiteit xyz');
        $data = new ActiviteitenReeksModel($activiteit);
        $data
            ->setPeriode(new AppDateRangeModel(new \DateTime('2018-01-01'), new \DateTime('2018-12-31')))
            ->setFrequentie(4)
            ->setWeekdag('Thursday');

        $activiteiten = ActiviteitenreeksGenerator::generate($data);

        $expected = [
            'Activiteit xyz [25-01-2018]',
            'Activiteit xyz [22-02-2018]',
            'Activiteit xyz [22-03-2018]',
            'Activiteit xyz [26-04-2018]',
            'Activiteit xyz [24-05-2018]',
            'Activiteit xyz [28-06-2018]',
            'Activiteit xyz [26-07-2018]',
            'Activiteit xyz [23-08-2018]',
            'Activiteit xyz [27-09-2018]',
            'Activiteit xyz [25-10-2018]',
            'Activiteit xyz [22-11-2018]',
            'Activiteit xyz [27-12-2018]',
        ];

        $actual = array_map(function ($activiteit) {
            return $activiteit->getNaam();
        }, $activiteiten);

        $this->assertEquals($expected, $actual);
    }

    public function testGeneratorGeneratesCorrectDates()
    {
        $activiteit = new Activiteit();
        $data = new ActiviteitenReeksModel($activiteit);
        $data
            ->setPeriode(new AppDateRangeModel(new \DateTime('2018-01-01'), new \DateTime('2018-12-31')))
            ->setFrequentie(4)
            ->setWeekdag('Thursday');

        $activiteiten = ActiviteitenreeksGenerator::generate($data);

        $expected = [
            new \DateTime('2018-01-25'),
            new \DateTime('2018-02-22'),
            new \DateTime('2018-03-22'),
            new \DateTime('2018-04-26'),
            new \DateTime('2018-05-24'),
            new \DateTime('2018-06-28'),
            new \DateTime('2018-07-26'),
            new \DateTime('2018-08-23'),
            new \DateTime('2018-09-27'),
            new \DateTime('2018-10-25'),
            new \DateTime('2018-11-22'),
            new \DateTime('2018-12-27'),
        ];

        $actual = array_map(function ($activiteit) {
            return $activiteit->getDatum();
        }, $activiteiten);

        $this->assertEquals($expected, $actual);

        $data
            ->setFrequentie(0)
            ->setWeekdag('Monday');

        $activiteiten = ActiviteitenreeksGenerator::generate($data);
        $actual = array_map(function ($activiteit) {
            return $activiteit->getDatum();
        }, $activiteiten);

        $this->assertCount(53, $actual);
        $this->assertEquals(new \DateTime('2018-01-01'), array_shift($actual));
        $this->assertEquals(new \DateTime('2018-12-31'), array_pop($actual));

        $data
            ->setFrequentie(0)
            ->setWeekdag('Tuesday');

        $activiteiten = ActiviteitenreeksGenerator::generate($data);
        $actual = array_map(function ($activiteit) {
            return $activiteit->getDatum();
        }, $activiteiten);

        $this->assertCount(52, $actual);
        $this->assertEquals(new \DateTime('2018-01-02'), array_shift($actual));
        $this->assertEquals(new \DateTime('2018-12-25'), array_pop($actual));
    }

    public function testGeneratorGeneratesNothingWhenNothingInDateRange()
    {
        $activiteit = new Activiteit();
        $data = new ActiviteitenReeksModel($activiteit);
        $data
            ->setPeriode(new AppDateRangeModel(new \DateTime('2018-02-20'), new \DateTime('2018-02-19')))
            ->setFrequentie(0)
            ->setWeekdag('Monday');

        $activiteiten = ActiviteitenreeksGenerator::generate($data);

        $actual = array_map(function ($activiteit) {
            return $activiteit->getDatum();
        }, $activiteiten);

        $this->assertEquals([], $actual);
    }
}

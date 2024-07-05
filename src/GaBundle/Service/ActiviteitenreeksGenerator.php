<?php

namespace GaBundle\Service;

use GaBundle\Form\ActiviteitenReeksModel;
use NumberToWords\NumberToWords;

class ActiviteitenreeksGenerator
{
    public static function generate(ActiviteitenReeksModel $data)
    {
        $activiteiten = [];

        // get first activity date
        $datum = clone $data->getPeriode()->getStart();

        $nth = self::Nth($data->getFrequentie()); // change frequency to 'first' 'second' etc.
        $month = $datum->format('M');
        switch ($data->getFrequentie()) {
            case 0:
                $datum->modify('-1 day'); // to assert the 'next' includes also the date itself.
                $datum->modify(sprintf('next %s', $data->getWeekdag()));
                break;
            case 1:
            case 2:
            case 3:
            case 4:
                $datum->modify(sprintf('%s %s of %s', $nth, $data->getWeekdag(), $month));
                if ($datum <= $data->getPeriode()->getStart()) {
                    $datum->modify(sprintf('%s %s of next month', $nth, $data->getWeekdag()));
                }
                //                $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                break;
            case 5:
                $month = $datum->format('m-Y');
                $datum->modify(sprintf('next %s', $data->getWeekdag()));
                $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                if ($month !== $datum->format('m-Y')) {
                    var_dump('');
                    exit;
                }
                break;
            default:
                throw new \InvalidArgumentException('Invalid frequency');
        }

        //        $datum->modify("-1 day");//to assert the 'next' includes also the date itself.
        while ($datum >= $data->getPeriode()->getStart() && $datum <= $data->getPeriode()->getEnd()) {
            $activiteit = clone $data->getActiviteit();
            $activiteit
                ->setDatum((clone $datum)->setTime($data->getTijd()->format('H'), $data->getTijd()->format('i')))
                ->setNaam(sprintf('%s [%s]', $activiteit->getNaam(), $activiteit->getDatum()->format('d-m-Y')))
            ;
            $activiteiten[] = $activiteit;

            switch ($data->getFrequentie()) {
                case 0:
                    $datum->modify(sprintf('next %s', $data->getWeekdag()));
                    break;
                case 5:
                    $month = $datum->format('m-Y');
                    $datum->modify(sprintf('first %s of next month', $data->getWeekdag()));
                    $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));

                    while ($month !== $datum->format('m-Y') && $datum <= $data->getPeriode()->getEnd()) {
                        $month = $datum->format('m-Y');
                        $datum->modify(sprintf('first %s of this month', $data->getWeekdag()));
                        $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                    }
                    break;
                default:
                    $datum->modify(sprintf('%s %s of next month', $nth, $data->getWeekdag()));
                    // $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                    break;
            }
        }

        return $activiteiten;
    }

    public static function Nth($n)
    {
        $nw = new NumberToWords();
        $numberTransformer = $nw->getNumberTransformer('en');
        $s = $numberTransformer->toWords($n);
        $replacements = [
            'one' => 'first',
            'two' => 'second',
            'three' => 'third',
            've' => 'fth',
            't' => 'th',
            'e' => 'th',
            'y' => 'ieth',
            '' => 'th',
        ];
        foreach ($replacements as $from => $to) {
            $count = 0;
            $r = preg_replace('/'.$from.'$/', $to, $s, 1, $count);
            if ($count) {
                return $r;
            }
        }
    }

    public static function addOrdinalNumberSuffix($num)
    {
        if (!in_array($num % 100, [11, 12, 13])) {
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1:  return $num.'st';
                case 2:  return $num.'nd';
                case 3:  return $num.'rd';
            }
        }

        return $num.'th';
    }
}

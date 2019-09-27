<?php

namespace GaBundle\Service;

use GaBundle\Form\ActiviteitenReeksModel;

class ActiviteitenreeksGenerator
{
    public static function generate(ActiviteitenReeksModel $data)
    {
        $activiteiten = [];

        // get first activity date
        $datum = clone $data->getPeriode()->getStart();
        switch ($data->getFrequentie()) {
            case 0:
                $datum->modify(sprintf('first %s of this month', $data->getWeekdag()));
                break;
            case 1:
            case 2:
            case 3:
            case 4:
                $datum->modify(sprintf('first %s of this month', $data->getWeekdag()));
                $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                break;
            case 5:
                $month = $datum->format('m-Y');
                $datum->modify(sprintf('first %s of this month', $data->getWeekdag()));
                $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                if ($month !== $datum->format('m-Y')) {
                    var_dump('');
                    die;
                }
                break;
            default:
                throw new \InvalidArgumentException('Invalid frequency');
        }

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
                case 1:
                    $datum->modify(sprintf('first %s of next month', $data->getWeekdag()));
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
                    $datum->modify(sprintf('first %s of next month', $data->getWeekdag()));
                    $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                    break;
            }
        }

        return $activiteiten;
    }
}

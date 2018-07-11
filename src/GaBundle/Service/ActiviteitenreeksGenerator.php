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
        if ($data->getFrequentie() > 1) {
            $datum->modify(sprintf('first %s of this month', $data->getWeekdag()));
            $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
        } else {
            $datum->modify(sprintf('first %s of this month', $data->getWeekdag()));
        }

        while ($datum >= $data->getPeriode()->getStart() && $datum <= $data->getPeriode()->getEnd()) {
            $activiteit = clone $data->getActiviteit();
            $activiteit
                ->setDatum(clone $datum)
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
                default:
                    $datum->modify(sprintf('first %s of next month', $data->getWeekdag()));
                    $datum->modify(sprintf('+%d weeks', $data->getFrequentie() - 1));
                    break;
            }
        }

        return $activiteiten;
    }
}

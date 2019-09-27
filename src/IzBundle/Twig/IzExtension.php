<?php

namespace IzBundle\Twig;

use Doctrine\ORM\EntityNotFoundException;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;

class IzExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('naam_klant', [$this, 'naamKlant']),
            new \Twig_SimpleFilter('naam_vrijwilliger', [$this, 'naamVrijwilliger']),
        ];
    }

    public function naamKlant(Hulpvraag $hulpvraag)
    {
        try {
            return (string) $hulpvraag->getDeelnemer();
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function naamVrijwilliger(Hulpaanbod $hulpaanbod)
    {
        try {
            return (string) $hulpaanbod->getDeelnemer();
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }
}

<?php

namespace IzBundle\Twig;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Persoon;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Hulpaanbod;

class IzExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
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

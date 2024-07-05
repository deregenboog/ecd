<?php

namespace IzBundle\Twig;

use Doctrine\ORM\EntityNotFoundException;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\Hulpvraag;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IzExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('naam_klant', [$this, 'naamKlant']),
            new TwigFilter('naam_vrijwilliger', [$this, 'naamVrijwilliger']),
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

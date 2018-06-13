<?php

namespace AppBundle\Service;

use AppBundle\Entity\Persoon;

class NameFormatter
{
    static public function format(Persoon $persoon, $formal = true)
    {
        if (false === $formal) {
            return self::formatInformal($persoon);
        }

        return self::formatFormal($persoon);
    }

    static public function formatInformal(Persoon $persoon)
    {
        $parts = [];

        if ($persoon->getVoornaam()) {
            $parts[] = $persoon->getVoornaam();
        }
        if ($persoon->getRoepnaam()) {
            $parts[] = "({$persoon->getRoepnaam()})";
        }
        if ($persoon->getTussenvoegsel()) {
            $parts[] = $persoon->getTussenvoegsel();
        }
        if ($persoon->getAchternaam()) {
            $parts[] = $persoon->getAchternaam();
        }

        return implode(' ', $parts);
    }

    static public function formatFormal(Persoon $persoon)
    {
        $parts = [];

        if ($persoon->getAchternaam()) {
            if ($persoon->getVoornaam() || $persoon->getTussenvoegsel()) {
                $parts[] = $persoon->getAchternaam().',';
            } else {
                $parts[] = $persoon->getAchternaam();
            }
        }
        if ($persoon->getVoornaam()) {
            $parts[] = $persoon->getVoornaam();
        }
        if ($persoon->getTussenvoegsel()) {
            $parts[] = $persoon->getTussenvoegsel();
        }

        return implode(' ', $parts);
    }
}

<?php

namespace AppBundle\Service;

use AppBundle\Entity\Persoon;

class NameFormatter
{
    public static function format(?Persoon $persoon = null, $formal = true)
    {
        if (!$persoon) {
            return '';
        }

        if (false === $formal) {
            return self::formatInformal($persoon);
        }

        return self::formatFormal($persoon);
    }

    public static function formatInformal(?Persoon $persoon = null)
    {
        if (!$persoon) {
            return '';
        }

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

    public static function formatFormal(?Persoon $persoon = null)
    {
        if (!$persoon) {
            return '';
        }

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

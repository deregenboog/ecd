<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GaBundle\Repository\GroepRepository")
 * @Gedmo\Loggable
 */
class GroepKwartiermaken extends Groep
{
    public static function getType(): string
    {
        return "Kwartiermaken";
    }

}

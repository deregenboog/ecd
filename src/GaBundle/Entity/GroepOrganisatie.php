<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GaBundle\Repository\GroepRepository")
 * @Gedmo\Loggable
 */
class GroepOrganisatie extends Groep
{
    public static function getType(): string
    {
        return "Organisatie";
    }

}

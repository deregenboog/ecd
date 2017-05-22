<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GaBundle\Repository\GaGroepRepository")
 * @Gedmo\Loggable
 */
class GaGroepBuurtmaatjes extends GaGroep
{
}

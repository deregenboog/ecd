<?php

namespace TwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="TwBundle\Repository\VerslagRepository")
 *
 * @Gedmo\Loggable()
 */
class Verslag extends SuperVerslag
{
}

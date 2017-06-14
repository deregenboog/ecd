<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Trajectafsluiting extends Afsluiting
{
}

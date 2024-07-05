<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 *
 * @Vich\Uploadable
 */
class Overeenkomst extends Document
{
}

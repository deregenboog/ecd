<?php

namespace TwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @Vich\Uploadable()
 */
class Document extends SuperDocument
{
}

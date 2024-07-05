<?php

namespace OekraineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Aanmelding extends DossierStatus
{
    public function __toString()
    {
        return sprintf(
            'Aanmelding op %s',
            $this->datum->format('d-m-Y')
        );
    }
}

<?php

namespace VillaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluiting extends DossierStatus
{
    public function __toString()
    {
        return sprintf(
            'Afsluiting op %s door %s',
            $this->datum->format('d-m-Y'),
            $this->medewerker,

        );
    }
}

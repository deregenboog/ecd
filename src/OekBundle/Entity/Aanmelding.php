<?php

namespace OekBundle\Entity;

use AppBundle\Model\InitialStateInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Aanmelding extends DossierStatus implements InitialStateInterface
{
    public function __toString()
    {
        return sprintf(
            'Aanmelding op %s door %s (verwezen door %s)',
            $this->datum->format('d-m-Y'),
            $this->medewerker,
            $this->verwijzing
        );
    }

    public function setVerwijzing(VerwijzingDoor $verwijzing)
    {
        $this->verwijzing = $verwijzing;

        return $this;
    }
}

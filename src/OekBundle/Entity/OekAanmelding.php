<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\InitialStateInterface;

/**
 * @ORM\Entity(repositoryClass="OekBundle\Repository\OekKlantRepository")
 * @ORM\HasLifecycleCallbacks
 */
class OekAanmelding extends OekDossierStatus implements InitialStateInterface
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

    public function setVerwijzing(OekVerwijzingDoor $verwijzing)
    {
        $this->verwijzing = $verwijzing;

        return $this;
    }
}

<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluiting extends DossierStatus
{
    /**
     * @ORM\ManyToOne(targetEntity="RedenAfsluiting")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     * @Assert\NotNull
     */
    protected $reden;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $toelichting;

    public function __toString()
    {
        return sprintf(
            'Afsluiting op %s (%s)',
            $this->datum->format('d-m-Y'),
            $this->reden
        );
    }

    public function getReden()
    {
        return $this->reden;
    }

    public function setReden(RedenAfsluiting $reden)
    {
        $this->reden = $reden;

        return $this;
    }

    public function getToelichting()
    {
        return $this->toelichting;
    }

    public function setToelichting($toelichting)
    {
        $this->toelichting = $toelichting;
        return $this;
    }

}

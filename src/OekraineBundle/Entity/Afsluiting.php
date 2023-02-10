<?php

namespace OekraineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluiting extends DossierStatus
{
    /**
     * @var RedenAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="RedenAfsluiting")
     * @Gedmo\Versioned
     * @Assert\NotNull
     */
    protected $reden;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $toelichting;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Land")
     * @Gedmo\Versioned
     */
    protected $land;

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

    public function getLand()
    {
        return $this->land;
    }

    public function setLand($land = null)
    {
        $this->land = $land;

        return $this;
    }
}

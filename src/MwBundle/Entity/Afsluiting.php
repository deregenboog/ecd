<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MwBundle\Entity\Resultaat;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Afsluiting extends MwDossierStatus
{
    /**
     * @var AfsluitredenKlant
     *
     * @ORM\ManyToOne(targetEntity="AfsluitredenKlant")
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
     * @var Resultaat
     *
     * @ORM\ManyToMany(targetEntity="Resultaat")
     * @ORM\JoinTable(name="mw_afsluiting_resultaat")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    protected $resultaat;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Land")
     * @Gedmo\Versioned
     */
    protected $land;

    /**
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Locatie")
     * @Assert\NotNull
     */
    protected $locatie;

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

    public function setReden(AfsluitredenKlant $reden)
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

    /**
     * @return mixed
     */
    public function getResultaat()
    {
        return $this->resultaat;
    }

    /**
     * @param mixed $resultaat
     * @return Afsluiting
     */
    public function setResultaat($resultaat)
    {
        $this->resultaat = $resultaat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocatie()
    {
        return $this->locatie;
    }

    /**
     * @param mixed $locatie
     * @return Afsluiting
     */
    public function setLocatie($locatie)
    {
        $this->locatie = $locatie;
        return $this;
    }
}

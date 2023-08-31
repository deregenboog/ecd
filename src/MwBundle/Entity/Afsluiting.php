<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MwBundle\Entity\Resultaat;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $zachteLanding;

    /**
     * @var int
     * @ORM\Column(nullable=true)
     */
    protected $kosten;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable="true")
     */
    protected $datumRepatriering;

    public function __toString()
    {
        return sprintf(
            'Afsluiting op %s (%s) door %s op %s',
            $this->datum->format('d-m-Y'),
            $this->reden,
            $this->medewerker,
            $this->locatie,

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

    /**
     * @return bool
     */
    public function isZachteLanding(): ?bool
    {
        return $this->zachteLanding;
    }

    /**
     * @param bool $zachteLanding
     */
    public function setZachteLanding(?bool $zachteLanding): void
    {
        $this->zachteLanding = $zachteLanding;
    }

    /**
     * @return int
     */
    public function getKosten(): ?int
    {
        return $this->kosten;
    }

    /**
     * @param int $kosten
     */
    public function setKosten(?int $kosten): void
    {
        $this->kosten = $kosten;
    }

    /**
     * @return \DateTime
     */
    public function getDatumRepatriering(): ?\DateTime
    {
        return $this->datumRepatriering;
    }

    /**
     * @param \DateTime $datumRepatriering
     */
    public function setDatumRepatriering(?\DateTime $datumRepatriering): void
    {
        $this->datumRepatriering = $datumRepatriering;
    }


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

        //make sure that if afsluitreen is changed or is not a land, the other fields get unset.
        //otherwise it would screw up statistics.
        if(!$this->reden->isLand())
        {
            $this->setZachteLanding(null);
            $this->setKosten(null);
            $this->setLand(null);
            $this->setDatumRepatriering(null);

        }
    }


}

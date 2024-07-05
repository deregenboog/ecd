<?php

namespace MwBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Afsluiting extends MwDossierStatus
{
    /**
     * @var AfsluitredenKlant
     *
     * @ORM\ManyToOne(targetEntity="AfsluitredenKlant")
     *
     * @Gedmo\Versioned
     *
     * @Assert\NotNull
     */
    protected $reden;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $toelichting;

    /**
     * @var Resultaat
     *
     * @ORM\ManyToMany(targetEntity="Resultaat")
     *
     * @ORM\JoinTable(name="mw_afsluiting_resultaat")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Expression(
     *     "!this.getRedenHeeftResultaatNodig()",
     *     message="Als de begeleiding is afgerond of overgedragen is er een resultaat nodig."
     * )
     */
    protected $resultaat;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Land")
     *
     * @Gedmo\Versioned
     */
    protected $land;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $zachteLanding;

    /**
     * @var int
     *
     * @ORM\Column(nullable=true)
     */
    protected $kosten;

    /**
     * @var \DateTime
     *
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

    public function getRedenHeeftResultaatNodig(): bool
    {
        /**
         * Afsluitredenen die over begeleiding gaan (afgerond, overgedragen) hebben een resultaat nodig. Andere afsluitredenen niet.
         */
        $pos = strpos($this->reden, 'Begeleiding');

        if (false !== $pos && count($this->getResultaat()) > 0) {
            return false;
        } elseif (false === $pos) {
            return false;
        }

        return true;
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

    public function getResultaat()
    {
        return $this->resultaat;
    }

    /**
     * @return Afsluiting
     */
    public function setResultaat($resultaat)
    {
        $this->resultaat = $resultaat;

        return $this;
    }

    public function isZachteLanding(): ?bool
    {
        return $this->zachteLanding;
    }

    public function setZachteLanding(?bool $zachteLanding): void
    {
        $this->zachteLanding = $zachteLanding;
    }

    public function getKosten(): ?int
    {
        return $this->kosten;
    }

    public function setKosten(?int $kosten): void
    {
        $this->kosten = $kosten;
    }

    public function getDatumRepatriering(): ?\DateTime
    {
        return $this->datumRepatriering;
    }

    public function setDatumRepatriering(?\DateTime $datumRepatriering): void
    {
        $this->datumRepatriering = $datumRepatriering;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // make sure that if afsluitreen is changed or is not a land, the other fields get unset.
        // otherwise it would screw up statistics.
        if (!$this->reden->isLand()) {
            $this->setZachteLanding(null);
            $this->setKosten(null);
            $this->setLand(null);
            $this->setDatumRepatriering(null);
        }
        parent::parentValidate($context, $payload);
    }
}

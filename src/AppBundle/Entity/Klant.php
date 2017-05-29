<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use InloopBundle\Entity\Intake;
use Doctrine\Common\Collections\ArrayCollection;
use InloopBundle\Entity\DossierStatus;

/**
 * @ORM\Entity
 * @ORM\Table(name="klanten")
 * @Gedmo\Loggable
 */
class Klant extends Persoon
{
    /**
     * @ORM\Column(name="MezzoID", type="integer")
     * @Gedmo\Versioned
     */
    private $mezzoId = 0;

    /**
     * @var Intake[]
     *
     * @ORM\OneToMany(targetEntity="InloopBundle\Entity\Intake", mappedBy="klant")
     */
    private $intakes;

    /**
     * @ORM\Column(name="laatste_TBC_controle", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $laatsteTbcControle;

    /**
     * @var Intake
     *
     * @ORM\OneToOne(targetEntity="InloopBundle\Entity\Intake")
     * @ORM\JoinColumn(name="laste_intake_id")
     * @Gedmo\Versioned
     */
    private $laatsteIntake;

    /**
     * @var DossierStatus
     *
     * @ORM\OneToOne(targetEntity="InloopBundle\Entity\DossierStatus")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $huidigeStatus;

    /**
     * @ORM\Column(name="laatste_registratie_id", type="integer")
     * @Gedmo\Versioned
     */
    private $laatsteRegistratieId;

    /**
     * @ORM\Column(name="last_zrm", type="date")
     * @Gedmo\Versioned
     */
    private $laatsteZrm;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $overleden = false;

    public function __construct()
    {
        $this->intakes = new ArrayCollection();
    }

    public function getLaatsteZrm()
    {
        return $this->laatsteZrm;
    }

    public function setLaastseZrm(\DateTime $laatsteZrm)
    {
        $this->laatsteZrm = $laatsteZrm;

        return $this;
    }

    public function getLaatsteTbcControle()
    {
        return $this->laatsteTbcControle;
    }

    public function setLaatsteTbcControle($laatsteTbcControle = null)
    {
        $this->laatsteTbcControle = $laatsteTbcControle;

        return $this;
    }

    public function getIntakes()
    {
        return $this->intakes;
    }

    public function addIntake(Intake $intake)
    {
        $this->intakes->add($intake);
        $intake->setKlant($this);
        $this->laatsteIntake = $intake;

        return $this;
    }

    public function getHuidigeStatus()
    {
        return $this->huidigeStatus;
    }

    public function setHuidigeStatus(DossierStatus $huidigeStatus)
    {
        $this->huidigeStatus = $huidigeStatus;
        return $this;
    }

}

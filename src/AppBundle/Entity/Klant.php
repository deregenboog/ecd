<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use InloopBundle\Entity\DossierStatus;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Registratie;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="klanten",
 *     indexes={
 *         @ORM\Index(name="idx_klanten_werkgebied", columns={"werkgebied"}),
 *         @ORM\Index(name="idx_klanten_postcodegebied", columns={"postcodegebied"})
 *     }
 * )
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
     * @ORM\OrderBy({"intakedatum" = "DESC", "id" = "DESC"})
     */
    private $intakes;

    /**
     * @var Zrm[]
     *
     * @ORM\OneToMany(targetEntity="Zrm", mappedBy="klant",cascade={"persist"})
     * @ORM\OrderBy({"created" = "DESC", "id" = "DESC"})
     */
    private $zrms;

    /**
     * @var Verslag[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Verslag", mappedBy="klant")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var Registratie[]
     *
     * @ORM\OneToMany(targetEntity="InloopBundle\Entity\Registratie", mappedBy="klant")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $registraties;

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
     * @var Registratie
     *
     * @ORM\OneToOne(targetEntity="InloopBundle\Entity\Registratie")
     * @ORM\JoinColumn(name="laatste_registratie_id")
     * @Gedmo\Versioned
     */
    private $laatsteRegistratie;

    /**
     * @ORM\Column(name="last_zrm", type="date", nullable=true)
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
        $this->registraties = new ArrayCollection();
        $this->zrms = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
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

    public function getRegistraties()
    {
        return $this->registraties;
    }

    public function getRecenteRegistraties()
    {
        $criteria = Criteria::create()
            ->orderBy(['id' => 'DESC'])
            ->setMaxResults(20)
        ;

        return $this->registraties->matching($criteria);
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

    public function getLaatsteIntake()
    {
        return $this->laatsteIntake;
    }

    public function setLaatsteIntake(Intake $laatsteIntake)
    {
        $this->laatsteIntake = $laatsteIntake;

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

    public function getLaatsteRegistratie()
    {
        return $this->laatsteRegistratie;
    }

    public function getZrms()
    {
        return $this->zrms;
    }

    public function addZrm(Zrm $zrm)
    {
        $this->zrms[] = $zrm;
        $zrm->setKlant($this);

        return $this;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
        $verslag->setKlant($this);

        return $this;
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use InloopBundle\Entity\DossierStatus;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\Schorsing;
use MwBundle\Entity\Verslag;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="klanten",
 *     indexes={
 *         @ORM\Index(name="idx_klanten_werkgebied", columns={"werkgebied"}),
 *         @ORM\Index(name="idx_klanten_postcodegebied", columns={"postcodegebied"}),
 *         @ORM\Index(name="idx_klanten_geboortedatum", columns={"geboortedatum"}),
 *         @ORM\Index(name="idx_klanten_first_intake_date", columns={"first_intake_date"})
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
     * @ORM\OneToMany(targetEntity="MwBundle\Entity\Verslag", mappedBy="klant")
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
     * @var Schorsing[]
     *
     * @ORM\OneToMany(targetEntity="InloopBundle\Entity\Schorsing", mappedBy="klant")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $schorsingen;

    /**
     * @ORM\Column(name="laatste_TBC_controle", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $laatsteTbcControle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_intake_date", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $eersteIntakeDatum;

    /**
     * @var Intake
     *
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Intake")
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

    /**
     * @var bool
     *
     * @ORM\Column(name="doorverwijzen_naar_amoc", type="boolean")
     * @Gedmo\Versioned
     */
    private $doorverwijzenNaarAmoc = false;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="Klant")
     * @Gedmo\Versioned
     */
    private $merged;

    /**
     * @var Opmerking[]
     *
     * @ORM\OneToMany(targetEntity="Opmerking", mappedBy="klant")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $opmerkingen;

    /**
     * @return bool
     */
    public function isDoorverwijzenNaarAmoc()
    {
        return $this->doorverwijzenNaarAmoc;
    }

    /**
     * @param bool $doorverwijzenNaarAmoc
     */
    public function setDoorverwijzenNaarAmoc($doorverwijzenNaarAmoc)
    {
        $this->doorverwijzenNaarAmoc = $doorverwijzenNaarAmoc;

        return $this;
    }

    public function __construct()
    {
        $this->intakes = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->zrms = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
        $this->opmerkingen = new ArrayCollection();
    }

    /**
     * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.5/cookbook/implementing-wakeup-or-clone.html#safely-implementing-clone
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
        }
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

    public function getRecenteRegistraties($n = 50)
    {
        $criteria = Criteria::create()
            ->orderBy(['id' => 'DESC'])
            ->setMaxResults((int) $n)
        ;

        return $this->registraties->matching($criteria);
    }

    public function getLaatsteRegistratie()
    {
        $registraties = $this->getRecenteRegistraties(1);

        return count($registraties) > 0 ? $registraties[0] : null;
    }

    public function getSchorsingen()
    {
        return $this->schorsingen;
    }

    public function getHuidigeSchorsingen()
    {
        $today = new \DateTime('today');
        $criteria = Criteria::create()
            ->where(Criteria::expr()->lte('datumVan', $today))
            ->andWhere(Criteria::expr()->gte('datumTot', $today))
            ->orderBy(['id' => 'DESC'])
        ;

        return $this->schorsingen->matching($criteria);
    }

    public function getVerlopenSchorsingen()
    {
        $today = new \DateTime('today');
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gt('datumVan', $today))
            ->orWhere(Criteria::expr()->lt('datumTot', $today))
            ->orderBy(['id' => 'DESC'])
        ;

        return $this->schorsingen->matching($criteria);
    }

    public function getRecenteSchorsingen($n = 50)
    {
        $criteria = Criteria::create()
            ->orderBy(['id' => 'DESC'])
            ->setMaxResults((int) $n)
        ;

        return $this->schorsingen->matching($criteria);
    }

    public function getLaatsteSchorsing()
    {
        $registraties = $this->getRecenteSchorsingen(1);

        return count($registraties) > 0 ? $registraties[0] : null;
    }

    public function getOngezieneSchorsingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('gezien', false))
            ->orderBy(['id' => 'DESC'])
        ;

        return $this->schorsingen->matching($criteria);
    }

    public function getSchorsingenVoorLocatie(Locatie $locatie)
    {
        $schorsingen = [];

        foreach ($this->schorsingen as $schorsing) {
            if ($schorsing->getLocaties()->contains($locatie)) {
                $schorsingen[] = $schorsing;
            }
        }

        return new ArrayCollection($schorsingen);
    }

    public function getIntakes()
    {
        return $this->intakes;
    }

    public function addIntake(Intake $intake)
    {
        if (0 === count($this->intakes)) {
            $this->eersteIntakeDatum = $intake->getIntakedatum();
        }
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

//     public function addRegistratie(Registratie $registratie)
//     {
//         $this->registraties->add($registratie);
//         $registratie->setKlant($this);
//         $this->laatsteRegistratie = $registratie;

//         return $this;
//     }

//     public function removeRegistratie(Registratie $registratie)
//     {
//         $this->registraties->removeElement($registratie);
//         $this->laatsteRegistratie = count($this->registraties) > 0 ? $this->registraties[0] : null;

//         return $this;
//     }

//     public function setLaatsteRegistratie(Registratie $laatsteRegistratie)
//     {
//         $this->laatsteRegistratie = $laatsteRegistratie;

//         return $this;
//     }

    public function getHuidigeStatus()
    {
        return $this->huidigeStatus;
    }

    public function setHuidigeStatus(DossierStatus $huidigeStatus)
    {
        $this->huidigeStatus = $huidigeStatus;

        return $this;
    }

    public function getZrms()
    {
        return $this->zrms;
    }

    public function addZrm(Zrm $zrm)
    {
        $this->zrms[] = $zrm;
        $this->laatsteZrm = $zrm->getCreated();
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

    public function getOpmerkingen()
    {
        return $this->opmerkingen;
    }

    public function getOpenstaandeOpmerkingen()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('gezien', false))
            ->orderBy(['id' => 'DESC'])
        ;

        return $this->opmerkingen->matching($criteria);
    }

    public function setMerged(Klant $newKlant)
    {
        $this->merged = $newKlant;
        $this->disabled = true;
        $this->deletedAt = new \DateTime();
    }

    public function getEersteIntakeDatum()
    {
        return $this->eersteIntakeDatum;
    }

    public function updateCalculatedFields()
    {
        if (count($this->registraties) > 0) {
            $this->laatsteRegistratie = $this->registraties[0];
        }
        if (count($this->intakes) > 0) {
            $this->laatsteIntake = $this->intakes[0];
            $this->eersteIntakeDatum = $this->intakes[count($this->intakes)-1]->getIntakeDatum();
        }
    }
}

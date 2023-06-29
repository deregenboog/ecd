<?php

namespace AppBundle\Entity;

use AppBundle\Model\DocumentSubjectTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use InloopBundle\Entity\DossierStatus;
use InloopBundle\Entity\Incident;
use InloopBundle\Entity\Intake;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\Schorsing;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\BinnenViaOptieKlant;
use MwBundle\Entity\Info;
use MwBundle\Entity\MwDossierStatus;
use MwBundle\Entity\Verslag;
use Symfony\Component\HttpFoundation\Request;
use TheIconic\NameParser\Parser;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="klanten",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"huidigeStatus_id", "deleted"})
 *      },
 *     indexes={
 *         @ORM\Index(name="idx_klanten_werkgebied", columns={"werkgebied"}),
 *         @ORM\Index(name="idx_klanten_postcodegebied", columns={"postcodegebied"}),
 *         @ORM\Index(name="idx_klanten_geboortedatum", columns={"geboortedatum"}),
 *         @ORM\Index(name="idx_klanten_first_intake_date", columns={"first_intake_date"}),
 *         @ORM\Index(name="achternaam", columns={"achternaam", "id", "geslacht_id", "laste_intake_id", "huidigeMwStatus_id"}),
 *         @ORM\Index(name="corona_besmet_idx", columns={"corona_besmet_vanaf"})
 *     }
 * )
 * @Gedmo\Loggable
 */
class Klant extends Persoon
{
    use DocumentSubjectTrait;

    /**
     * @var \DateTime Moment tot wanneer dit adres een briefadres is
     * @ORM\Column(nullable=true, type="date")
     * @Gedmo\Versioned()
     */
    protected $briefadres;

    /**
     * @ORM\Column(name="MezzoID", type="integer")
     * @Gedmo\Versioned
     */
    private $mezzoId = 0;

    /**
     * @var ArrayCollection<Intake>
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
     * @var ArrayCollection<Registratie>
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
     * @var Incident[]
     *
     * @ORM\OneToMany(targetEntity="InloopBundle\Entity\Incident", mappedBy="klant", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $incidenten;

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
     * @ORM\ManyToOne(targetEntity="InloopBundle\Entity\Intake", cascade={"persist"})
     * @ORM\JoinColumn(name="first_intake_id")
     * @Gedmo\Versioned
     */
    private $eersteIntake;

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
     * @ORM\OneToOne(targetEntity="InloopBundle\Entity\DossierStatus", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $huidigeStatus;

    /**
     * @var DossierStatus[]
     *
     * @ORM\OneToMany(targetEntity="InloopBundle\Entity\DossierStatus", mappedBy="klant")
     */
    private $statussen;

    /**
     * @var MwDossierStatus[]
     *
     * @ORM\OneToMany(targetEntity="MwBundle\Entity\MwDossierStatus", mappedBy="klant")
     */
    private $mwStatussen;

    /**
     * @var MwDossierStatus
     *
     * @ORM\OneToOne(targetEntity="MwBundle\Entity\MwDossierStatus", cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $huidigeMwStatus;

    /**
     * @var BinnenViaOptieKlant
     * @ORM\ManyToOne(targetEntity="MwBundle\Entity\BinnenViaOptieKlant", cascade={"persist"})
     */
    private $mwBinnenViaOptieKlant;

    /**
     * @var Info
     *
     * @ORM\OneToOne(targetEntity="MwBundle\Entity\Info", cascade={"persist"})
     * @ORM\JoinColumn(name="info_id")
     */
    private $info;

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
    protected $laatsteZrm;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $overleden = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="doorverwijzen_naar_amoc", type="boolean", nullable=true, options={"default":0})
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
     * @ORM\Column(name="corona_besmet_vanaf", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $coronaBesmetVanaf;

    /**
     * @return bool
     */

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @Gedmo\Versioned
     */
    protected $partner;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="Medewerker", cascade={"persist"} )
     * @Gedmo\Versioned
     */
    protected $maatschappelijkWerker;

    /**
     * @var KlantTaal[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="KlantTaal", mappedBy="klant", cascade={"persist"})
     */
    protected $klantTalen;

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

    public function __construct(?Request $request)
    {
        $this->intakes = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->zrms = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
        $this->opmerkingen = new ArrayCollection();
        $this->incidenten = new ArrayCollection();
        $this->klantTalen = new ArrayCollection();

        if($request)
        {

           $naamParts = $this::parseNaam($request->get('naam'));

            $this->setVoornaam($naamParts['voornaam']);
            $this->setTussenvoegsel($naamParts['tussenvoegsel']);
            $this->setAchternaam($naamParts['achternaam']);
        }
    }

    public static function parseNaam(string $name = ""): array
    {
        $parser = new Parser();
        $parsedName = $parser->parse($name);


        $naamParts['voornaam'] = '';
        $naamParts['roepnaam'] = '';
        $naamParts['tussenvoegsel'] = '';
        $naamParts['achternaam'] = '';

        $parser = new Parser();

        $parsedName = $parser->parse($name);

        $naamParts['voornaam'] = $parsedName->getFirstName();
        $naamParts['roepnaam'] = $parsedName->getGivenName();
        $naamParts['tussenvoegsel'] = $parsedName->getLastnamePrefix();
        $naamParts['achternaam'] = $parsedName->getLastName(true);

        return $naamParts;


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

    public function setLaatsteZrm($zrm)
    {
        $this->laatseZrm = $zrm;

        return $this;
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

    public function getRegistratiesSinds(\DateTime $date)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gte('binnen', $date))
            ->orderBy(['id' => 'DESC'])
        ;

        return $this->registraties->matching($criteria);
    }

    public function getLaatsteRegistratie(): ?Registratie
    {
        /**
         Waarom dit zo is en niet gewoon de laatste registratie... geen idee. dit is robuust als de ;aatste registratie niet klopt,
         maar ja.
         *
         */
        $registraties = $this->getRecenteRegistraties(1);

        return (is_array($registraties) || $registraties instanceof \Countable ? count($registraties) : 0) > 0 ? $registraties[0] : null;
    }

    public function setLaatsteRegistratie(Registratie $registratie)
    {
        $this->laatsteRegistratie = $registratie;
    }

    public function getSchorsingen()
    {
        return $this->schorsingen;
    }

    public function getHuidigeSchorsingen(?Locatie $locatie = null)
    {
        $today = new \DateTime('today');
        $criteria = Criteria::create()
            ->where(Criteria::expr()->lte('datumVan', $today))
            ->andWhere(Criteria::expr()->gte('datumTot', $today))
            ->orderBy(['id' => 'DESC'])
        ;
        $huidigeSchorsingen = $this->schorsingen->matching($criteria);

        if ($locatie) {
            $schorsingenLocatie = $this->getSchorsingenVoorLocatie($locatie);

            return new ArrayCollection(array_intersect($schorsingenLocatie->toArray(), $huidigeSchorsingen->toArray()));
        }

        return $huidigeSchorsingen;
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

        return (is_array($registraties) || $registraties instanceof \Countable ? count($registraties) : 0) > 0 ? $registraties[0] : null;
    }

    public function getOngezieneSchorsingen(?Locatie $locatie = null)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('gezien', false))
            ->orderBy(['id' => 'DESC'])
        ;
        $ongezieneSchorsingen = $this->schorsingen->matching($criteria);

        if ($locatie) {
            $schorsingenLocatie = $this->getSchorsingenVoorLocatie($locatie);

            return new ArrayCollection(array_intersect($schorsingenLocatie->toArray(), $ongezieneSchorsingen->toArray()));
        }

        return $ongezieneSchorsingen;
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

    /**
     * @return Incident[]
     */
    public function getIncidenten()
    {
        return $this->incidenten;
    }

    /**
     * @param Incident[] $incidenten
     */
    public function setIncidenten(array $incidenten): Klant
    {
        $this->incidenten = $incidenten;
        return $this;
    }

    public function addIncident(Incident $incident): Klant
    {
        $this->incidenten->add($incident);
        return $this;
    }
    public function getIntakes()
    {
        return $this->intakes;
    }

    public function addIntake(Intake $intake)
    {
        /**
         * dd 20191203 dit ging fout want intakedatum was nog niet gevuld omdat vanuit nieuwe intake constructor addIntake werd aangeroepen en
         * pas daarna de intakedatum werd gevuld.
         *
         * Niet meer eersteIntakeDatum vullen want dat gaat niet goed, op moment van koppelen is datum nog niet in entity.
         * Dus gewoon intake koppelen en queries daarop aanpassen
         *
         */
        if (0 === count($this->intakes)) {
            $this->eersteIntake = $intake;
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


    public function getHuidigeStatus()
    {
        return $this->huidigeStatus;
    }

    public function setHuidigeStatus(DossierStatus $huidigeStatus)
    {
        $this->huidigeStatus = $huidigeStatus;

        return $this;
    }

    public function getStatussen()
    {
        return $this->statussen;
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

    public function getAantalVerslagen(): int
    {
        return count((array) $this->verslagen);
    }
    public function getEersteVerslag()
    {
        return $this->verslagen->first();
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

    /**
     * @return Intake
     */
    public function getEersteIntake(): ?Intake
    {
        return $this->eersteIntake;
    }

    /**
     * @param Intake $eersteIntake
     */
    public function setEersteIntake(Intake $eersteIntake): void
    {
        $this->eersteIntake = $eersteIntake;
    }

    public function updateCalculatedFields()
    {
        $laatsteRegistratie = null;
        foreach ($this->registraties as $registratie) {
            if ($laatsteRegistratie == null || $registratie->getBinnen() > $laatsteRegistratie->getBinnen()) {
                $laatsteRegistratie = $registratie;
            }
        }
        $this->laatsteRegistratie = $laatsteRegistratie;

        $eersteIntake = null;
        $laatsteIntake = null;
        foreach ($this->intakes as $intake) {
            if ($eersteIntake == null || $intake->getIntakedatum() < $eersteIntake->getIntakedatum()) {
                $eersteIntake = $intake;
            }
            if ($laatsteIntake == null || $intake->getIntakedatum() > $laatsteIntake->getIntakedatum()) {
                $laatsteIntake = $intake;
            }
        }
        $this->laatsteIntake = $laatsteIntake;
        $this->eersteIntakeDatum = $eersteIntake->getIntakeDatum();
    }

    public function isOverleden()
    {
        return $this->overleden;
    }

    public function setOverleden($overleden)
    {
        $this->overleden = (bool) $overleden;

        return $this;
    }

    public function getToestemmingsformulier(): ?Toestemmingsformulier
    {
        foreach ($this->documenten as $document) {
            if ($document instanceof Toestemmingsformulier) {
                return $document;
            }
        }

        return null;
    }

    public function setToestemmingsformulier(Toestemmingsformulier $toestemmingsformulier): self
    {
        return $this->addDocument($toestemmingsformulier);
    }

    public function isToestemmingsformulierAanwezig(): bool
    {
        if (null !== $this->getToestemmingsformulier()) {
            return true;
        }
        return false;
    }

    /**
     * @return MwDossierStatus[]
     */
    public function getMwStatussen()
    {
        return $this->mwStatussen;
        $prevDosStat = null;
        $t = [];
//        removal of duplicate entries.
        foreach ($this->mwStatussen as $mwDosStat) {
            if (!$prevDosStat instanceof $mwDosStat) {
                $t[] = $mwDosStat;
            }
            $prevDosStat = $mwDosStat;
        }
        return $t;
    }

    /**
     * @param MwDossierStatus[] $mwStatussen
     * @return Klant
     */
    public function setMwStatussen(array $mwStatussen): Klant
    {
        $this->mwStatussen = $mwStatussen;
        return $this;
    }

    /**
     * @return MwDossierStatus
     */
    public function getHuidigeMwStatus(): ?MwDossierStatus
    {
        return $this->huidigeMwStatus;
    }

    public function getLaatsteBinnenViaOptieKlant(): ?BinnenViaOptieKlant
    {
        $binnenViaOptieKlant = null;
        foreach ($this->getMwStatussen() as $mwStatus) {
            if ($mwStatus instanceof Aanmelding) {
                $binnenViaOptieKlant = $mwStatus->getBinnenViaOptieKlant();
            }
        }
        return $binnenViaOptieKlant;
    }

    public function getMwStatus($id)
    {
        foreach ($this->getMwStatussen() as $mwStatus) {
            if ($mwStatus->getId() == $id) {
                return $mwStatus;
            }
        }
    }
    /**
     * @param MwDossierStatus $huidigeMwStatus
     * @return Klant
     */
    public function setHuidigeMwStatus(MwDossierStatus $huidigeMwStatus): Klant
    {
        $this->huidigeMwStatus = $huidigeMwStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoronaBesmetVanaf()
    {
        return $this->coronaBesmetVanaf;
    }

    /**
     * @param mixed $coronaBesmetVanaf
     * @return Klant
     */
    public function setCoronaBesmetVanaf($coronaBesmetVanaf)
    {
        $this->coronaBesmetVanaf = $coronaBesmetVanaf;
        return $this;
    }


    /**
     * @return Klant
     */
    public function getPartner(): ?Klant
    {
        return $this->partner;
    }

    /**
     * @param Klant $partner
     * @return Persoon
     */
    public function setPartner(?Klant $partner)
    {
        $this->partner = $partner;
        return $this;
    }

    /**
     * @return Medewerker
     */
    public function getMaatschappelijkWerker(): ?Medewerker
    {
        return $this->maatschappelijkWerker;
    }

    /**
     * @param Medewerker $maatschappelijkWerker
     * @return Klant
     */
    public function setMaatschappelijkWerker(?Medewerker $maatschappelijkWerker): Klant
    {
        $this->maatschappelijkWerker = $maatschappelijkWerker;
        return $this;
    }

    public function getVoorkeurstaal()
    {
        foreach ($this->klantTalen as $klantTaal) {
            if ($klantTaal->isVoorkeur()) {
                return $klantTaal->getTaal();
            }
        }
    }

    public function setVoorkeurstaal(Taal $taal)
    {
        $found = false;
        foreach ($this->klantTalen as $klantTaal) {
            if ($klantTaal->getTaal() == $taal) {
                $found = true;
                $klantTaal->setVoorkeur(true);
            } else {
                $klantTaal->setVoorkeur(false);
            }
        }

        if (!$found) {
            $klantTaal = new KlantTaal($this, $taal);
            $klantTaal->setVoorkeur(true);
            $this->klantTalen->add($klantTaal);
        }

        return $this;
    }

    public function getOverigeTalen()
    {
        $talen = new ArrayCollection();
        foreach ($this->klantTalen as $klantTaal) {
            if (!$klantTaal->isVoorkeur()) {
                $talen->add($klantTaal->getTaal());
            }
        }

        return $talen;
    }

    public function addOverigeTaal(Taal $taal)
    {
        $found = false;
        foreach ($this->klantTalen as $klantTaal) {
            if ($klantTaal->getTaal() == $taal) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $klantTaal = new KlantTaal($this, $taal);
            $this->klantTalen->add($klantTaal);
        }

        return $this;
    }

    /**
     * Proxy method used by form.
     */
    public function addOverigeTalen(Taal $taal)
    {
        return $this->addOverigeTaal($taal);
    }

    public function removeOverigeTaal(Taal $taal)
    {
        foreach ($this->klantTalen as $klantTaal) {
            if ($klantTaal->getTaal() == $taal) {
                $this->klantTalen->removeElement($klantTaal);
                break;
            }
        }

        return $this;
    }

    /**
     * Proxy method used by form.
     */
    public function removeOverigeTalen(Taal $taal)
    {
        return $this->removeOverigeTaal($taal);
    }

    /**
     * @return Info
     */
    public function getInfo(): ?Info
    {
        return $this->info;
    }

    /**
     * @param Info $info
     */
    public function setInfo(?Info $info): void
    {
        $this->info = $info;
    }

    /**
     * @return \DateTime
     */
    public function getBriefadres(): ?\DateTime
    {
        return $this->briefadres;
    }

    /**
     * @param \DateTime $briefadres
     */
    public function setBriefadres(?\DateTime $briefadres): void
    {
        $this->briefadres = $briefadres;
    }

    /**
     * @return bool
     */
    public function isBriefadres(): bool
    {
        return (bool)$this->briefadres;
    }
}

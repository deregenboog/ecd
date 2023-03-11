<?php

namespace GaBundle\Entity;

use AppBundle\Entity\Werkgebied;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GaBundle\Repository\GroepRepository")
 * @ORM\Table(name="ga_groepen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "ErOpUit" = "GroepErOpUit",
 *     "Buurtmaatjes" = "GroepBuurtmaatjes",
 *     "Kwartiermaken" = "GroepKwartiermaken",
 *     "OpenHuis" = "GroepOpenHuis",
 *     "Organisatie" = "GroepOrganisatie",
 *     "IZ" = "GroepIZ"
 * })
 * @Gedmo\Loggable
 * @TODO De view van add moet dynamisch ipv statisch deze types listen.
 */
abstract class Groep
{
    use TimestampableTrait;
    use NotDeletableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(length=100)
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @var Werkgebied
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinColumn(name="werkgebied", referencedColumnName="naam")
     * @Gedmo\Versioned
     */
    protected $werkgebied;

    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    protected $activiteitenRegistreren = true;

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $einddatum;

    /**
     * @var Lidmaatschap[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Lidmaatschap", mappedBy="groep", cascade={"persist"})
     */
    protected $lidmaatschappen;

    /**
     * @var Activiteit[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Activiteit", mappedBy="groep", cascade={"persist"})
     * @ORM\OrderBy({"datum": "desc"})
     */
    protected $activiteiten;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct()
    {
        $this->startdatum = new \DateTime('today');
        $this->activiteiten = new ArrayCollection();
        $this->lidmaatschappen = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->naam;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getWerkgebied()
    {
        return $this->werkgebied;
    }

    public function setWerkgebied(Werkgebied $werkgebied)
    {
        $this->werkgebied = $werkgebied;

        return $this;
    }

    public function getActiviteitenRegistreren()
    {
        return $this->activiteitenRegistreren;
    }

    public function setActiviteitenRegistreren($activiteitenRegistreren)
    {
        $this->activiteitenRegistreren = $activiteitenRegistreren;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum($startdatum)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function getEinddatum()
    {
        return $this->einddatum;
    }

    public function setEinddatum($einddatum)
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getActiviteiten()
    {
        return $this->activiteiten;
    }

    public function addActiviteit(Activiteit $activiteit)
    {
        $this->activiteiten[] = $activiteit;
        $activiteit->setGroep($this);

        return $this;
    }

    public function getLidmaatschappen()
    {
        return $this->lidmaatschappen;
    }

    public function getActieveLidmaatschappen()
    {
        return array_filter($this->lidmaatschappen->toArray(), function (Lidmaatschap $lidmaatschap) {
            return $lidmaatschap->isActief();
        });
    }

    public function getKlantLidmaatschappen()
    {
        return array_filter(
            $this->lidmaatschappen->toArray(),
            function (Lidmaatschap $lidmaatschap) {
                return $lidmaatschap->getDossier() instanceof Klantdossier;
            }
        );
    }

    public function getVrijwilligerLidmaatschappen()
    {
        return array_filter(
            $this->lidmaatschappen->toArray(),
            function (Lidmaatschap $lidmaatschap) {
                return $lidmaatschap->getDossier() instanceof Vrijwilligerdossier;
            }
        );
    }

    public function addLidmaatschap(Lidmaatschap $lidmaatschap)
    {
        $this->lidmaatschappen[] = $lidmaatschap;
        $lidmaatschap->setGroep($this);

        return $this;
    }

    public function getDossiers()
    {
        $dossiers = [];
        foreach ($this->lidmaatschappen as $lidmaatschap) {
            $dossiers[] = $lidmaatschap->getDossier();
        }
        $dossiers = array_filter($dossiers);

        return new ArrayCollection($dossiers);
    }

    public function isAfgesloten()
    {
        return $this->einddatum instanceof \DateTime
            && $this->einddatum <= new \DateTime('today');
    }

    public function getType()
    {
        $class =  (new \ReflectionClass($this))->getShortName();
        switch ($class) {
            case "GroepErOpUit":
                return "Er Op Uit";
            case "GroepBuurtmaatjes":
                return "Buurtmaatjes";
            case "GroepKwartiermaken":
                return "Kwartiermaken";
            case "GroepOpenHuis":
                return "Open Huis";
            case "GroepOrganisatie":
                return "Organisatie";
            default:
                return "Onbekend";
        }
    }
}

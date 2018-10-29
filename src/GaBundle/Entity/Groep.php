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
 * @ORM\Table(name="groepsactiviteiten_groepen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "ErOpUit" = "GroepErOpUit",
 *     "Buurtmaatjes" = "GroepBuurtmaatjes",
 *     "Kwartiermaken" = "GroepKwartiermaken",
 *     "OpenHuis" = "GroepOpenHuis",
 *     "Organisatie" = "GroepOrganisatie"
 * })
 * @Gedmo\Loggable
 */
abstract class Groep
{
    use TimestampableTrait, NotDeletableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(length=100, nullable=false)
     * @Gedmo\Versioned
     */
    protected $naam;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Werkgebied")
     * @ORM\JoinColumn(name="werkgebied", referencedColumnName="naam", nullable=true)
     * @Gedmo\Versioned
     */
    protected $werkgebied;

    /**
     * @ORM\Column(name="activiteiten_registreren", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $activiteitenRegistreren;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    protected $startdatum;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    protected $einddatum;

    /**
     * @var KlantLidmaatschap[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="KlantLidmaatschap", mappedBy="groep")
     */
    protected $klantlidmaatschappen;

    /**
     * @var VrijwilligerLidmaatschap[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="VrijwilligerLidmaatschap", mappedBy="groep")
     */
    protected $vrijwilligerlidmaatschappen;

    /**
     * @var Activiteit[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Activiteit", mappedBy="groep", cascade={"persist"})
     * @ORM\OrderBy({"datum": "desc"})
     */
    protected $activiteiten;

    public function __construct()
    {
        $this->startdatum = new \DateTime('today');
        $this->activiteiten = new ArrayCollection();
        $this->klantlidmaatschappen = new ArrayCollection();
        $this->vrijwilligerlidmaatschappen = new ArrayCollection();
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

    public function getKlantLidmaatschappen()
    {
        return $this->klantlidmaatschappen;
    }

    public function addKlantLidmaatschap(KlantLidmaatschap $lidmaatschap)
    {
        $this->klantlidmaatschappen[] = $lidmaatschap;
        $lidmaatschap->setGroep($this);

        return $this;
    }

    public function getVrijwilligerLidmaatschappen()
    {
        return $this->vrijwilligerlidmaatschappen;
    }

    public function addVrijwilligerLidmaatschap(VrijwilligerLidmaatschap $lidmaatschap)
    {
        $this->vrijwilligerlidmaatschappen[] = $lidmaatschap;
        $lidmaatschap->setGroep($this);

        return $this;
    }

    public function getKlanten()
    {
        $dossiers = [];
        foreach ($this->klantLidmaatschappen as $lidmaatschap) {
            $dossiers[] = $lidmaatschap->getKlant();
        }
        $dossiers = array_filter($dossiers);

        return new ArrayCollection($dossiers);
    }

    public function getVrijwilligers()
    {
        $dossiers = [];
        foreach ($this->vrijwilligerLidmaatschappen as $lidmaatschap) {
            $dossiers[] = $lidmaatschap->getVrijwilliger();
        }
        $dossiers = array_filter($dossiers);

        return new ArrayCollection($dossiers);
    }
}

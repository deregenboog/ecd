<?php

namespace GaBundle\Entity;

use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GaBundle\Repository\DeelnemerRepository")
 *
 * @ORM\Table("ga_dossiers")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 *
 * @ORM\DiscriminatorMap({
 *     "Klantdossier" = "Klantdossier",
 *     "Vrijwilligerdossier" = "Vrijwilligerdossier"
 * })
 *
 * @Gedmo\Loggable
 */
abstract class Dossier implements DocumentSubjectInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use DocumentSubjectTrait;
    use NotDeletableTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    protected $aanmelddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $afsluitdatum;

    /**
     * @var DossierAfsluitreden
     *
     * @ORM\ManyToOne(targetEntity="DossierAfsluitreden")
     *
     * @Gedmo\Versioned
     */
    protected $afsluitreden;

    /**
     * @var Intake
     *
     * @ORM\OneToOne(targetEntity="Intake", mappedBy="dossier", cascade={"persist"})
     */
    protected $intake;

    /**
     * @var ArrayCollection|Lidmaatschap[]
     *
     * @ORM\OneToMany(targetEntity="Lidmaatschap", mappedBy="dossier", cascade={"persist"})
     */
    protected $lidmaatschappen;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="dossier", cascade={"persist"})
     */
    protected $deelnames;

    /**
     * @var ArrayCollection|Verslag[]
     *
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="dossier", cascade={"persist"})
     *
     * @ORM\OrderBy({"id": "desc"})
     */
    protected $verslagen;

    /**
     * @var DocumentInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Document", cascade={"persist","remove"}, fetch="EXTRA_LAZY")
     *
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     */
    protected $documenten;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    /**
     * @return \DateTime
     */
    public function getAanmelddatum()
    {
        return $this->aanmelddatum;
    }

    public function setAanmelddatum(\DateTime $aanmelddatum)
    {
        $this->aanmelddatum = $aanmelddatum;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(\DateTime $afsluitdatum)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function open()
    {
        $this->aanmelddatum = new \DateTime();
        $this->afsluitdatum = null;
        $this->afsluitreden = null;

        return $this;
    }

    public function close()
    {
        $this->afsluitdatum = new \DateTime();

        return $this;
    }

    public function __construct()
    {
        $this->open();

        $this->lidmaatschappen = new ArrayCollection();
        $this->deelnames = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
    }

    public function getGroepen()
    {
        $groepen = [];
        foreach ($this->lidmaatschappen as $lidmaatschap) {
            $groepen[] = $lidmaatschap->getGroep();
        }
        $groepen = array_filter($groepen);

        return new ArrayCollection($groepen);
    }

    public function getActiviteiten()
    {
        $activiteiten = [];
        foreach ($this->deelnames as $deelname) {
            $activiteiten[] = $deelname->getActiviteit();
        }
        $activiteiten = array_filter($activiteiten);

        return new ArrayCollection($activiteiten);
    }

    public function getDeelnames()
    {
        return $this->deelnames;
    }

    public function getDeelname(Activiteit $activiteit)
    {
        foreach ($this->deelnames as $deelname) {
            if ($deelname->getActiviteit() == $activiteit) {
                return $deelname;
            }
        }
    }

    public function addDeelname(Deelname $deelname)
    {
        $this->deelnames[] = $deelname;
        $deelname->setDossier($this);

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

    public function addLidmaatschap(Lidmaatschap $lidmaatschap)
    {
        $this->lidmaatschappen[] = $lidmaatschap;
        $lidmaatschap->setDossier($this);

        return $this;
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function addVerslag(Verslag $verslag)
    {
        $this->verslagen[] = $verslag;
        $verslag->setDossier($this);

        return $this;
    }

    public function getIntake()
    {
        return $this->intake;
    }

    public function setIntake(Intake $intake)
    {
        $this->intake = $intake;

        return $this;
    }

    public function getAfsluitreden()
    {
        return $this->afsluitreden;
    }

    public function setAfsluitreden(DossierAfsluitreden $afsluitreden)
    {
        $this->afsluitreden = $afsluitreden;

        return $this;
    }

    public function isAfgesloten()
    {
        return $this->afsluitdatum instanceof \DateTime
            && $this->afsluitdatum <= new \DateTime('today');
    }
}

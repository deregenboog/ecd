<?php

namespace OekBundle\Entity;

use AppBundle\Entity\Klant;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\KlantRelationInterface;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="OekBundle\Repository\DeelnemerRepository")
 *
 * @ORM\Table(name="oek_klanten")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Deelnemer implements KlantRelationInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * History of states.
     *
     * @var DossierStatus[]
     *
     * @ORM\ManyToMany(targetEntity="DossierStatus", cascade={"persist"})
     *
     * @ORM\JoinTable(
     *     name="oekklant_oekdossierstatus",
     *     joinColumns={@ORM\JoinColumn(name="oekklant_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="oekdossierstatus_id")}
     * )
     *
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $dossierStatussen;

    /**
     * Current state.
     *
     * @var DossierStatus
     *
     * @ORM\ManyToOne(targetEntity="DossierStatus", cascade={"persist"})
     *
     * @ORM\JoinColumn(name="oekDossierStatus_id")
     *
     * @Gedmo\Versioned
     */
    private $dossierStatus;

    /**
     * Current aanmelding.
     *
     * @var Aanmelding
     *
     * @ORM\ManyToOne(targetEntity="Aanmelding")
     *
     * @ORM\JoinColumn(name="oekAanmelding_id")
     *
     * @Gedmo\Versioned
     */
    private $aanmelding;

    /**
     * Current afsluiting.
     *
     * @var Afsluiting
     *
     * @ORM\ManyToOne(targetEntity="Afsluiting")
     *
     * @ORM\JoinColumn(name="oekAfsluiting_id")
     *
     * @Gedmo\Versioned
     */
    private $afsluiting;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     *
     * @ORM\JoinColumn(name="klant_id", nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var ArrayCollection|Lidmaatschap[]
     *
     * @ORM\OneToMany(targetEntity="Lidmaatschap", mappedBy="deelnemer")
     */
    private $lidmaatschappen;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="deelnemer")
     */
    private $deelnames;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $voedselbankklant = false;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $opmerking;

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

    public function __construct(?Klant $klant = null)
    {
        $this->klant = $klant;

        $this->lidmaatschappen = new ArrayCollection();
        $this->deelnames = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatFormal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getKlant(): Klant
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getGroepen()
    {
        $groepen = new ArrayCollection();
        foreach ($this->lidmaatschappen as $lidmaatschap) {
            $groepen[] = $lidmaatschap->getGroep();
        }

        return $groepen;
    }

    public function getTrainingen()
    {
        $trainingen = new ArrayCollection();
        foreach ($this->deelnames as $deelname) {
            $trainingen[] = $deelname->getTraining();
        }

        return $trainingen;
    }

    public function isDeletable()
    {
        // @todo: implement for real
        return false;
    }

    public function getDossierStatussen()
    {
        return $this->dossierStatussen;
    }

    public function setAanmelding(Aanmelding $aanmelding)
    {
        return $this->addAanmelding($aanmelding);
    }

    public function addAanmelding(Aanmelding $aanmelding)
    {
        if ($this->dossierStatus instanceof Aanmelding) {
            throw new \RuntimeException('Er is een fout opgetreden bij het aanpassen van de dossierstatus.');
        }

        $this->dossierStatussen[] = $aanmelding;
        $this->dossierStatus = $aanmelding;

        $this->aanmelding = $aanmelding;
        $this->afsluiting = null;
        $aanmelding->setDeelnemer($this);

        return $this;
    }

    public function addAfsluiting(Afsluiting $afsluiting)
    {
        if (!$this->dossierStatus instanceof Aanmelding) {
            throw new \RuntimeException('Er is een fout opgetreden bij het aanpassen van de dossierstatus.');
        }

        $this->dossierStatussen[] = $afsluiting;
        $this->dossierStatus = $afsluiting;

        $this->afsluiting = $afsluiting;
        $afsluiting->setDeelnemer($this);

        return $this;
    }

    public function getDossierStatus()
    {
        return $this->dossierStatus;
    }

    /**
     * Returns the current Aanmelding instance.
     *
     * @return Aanmelding
     */
    public function getAanmelding()
    {
        return $this->aanmelding;
    }

    /**
     * Returns the current Afsluiting instance.
     *
     * @return Afsluiting
     */
    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function getOpmerking()
    {
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking, 'ISO-8859-1','UTF-8');
    }

    public function setOpmerking($opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');

        return $this;
    }

    public function getDeelname(Training $training)
    {
        foreach ($this->deelnames as $deelname) {
            if ($deelname->getTraining() == $training) {
                return $deelname;
            }
        }
    }

    public function getLidmaatschappen()
    {
        return $this->lidmaatschappen;
    }

    public function getDeelnames()
    {
        return $this->deelnames;
    }

    /**
     * @param bool $voedselbankklant
     *
     * @return self
     */
    public function setVoedselbankklant($voedselbankklant)
    {
        $this->voedselbankklant = $voedselbankklant;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVoedselbankklant()
    {
        return $this->voedselbankklant;
    }

    public function getAfgerondeTrainingen()
    {
        $trainingen = [];
        foreach ($this->deelnames as $deelname) {
            if (DeelnameStatus::STATUS_AFGEROND === $deelname->getStatus()) {
                $trainingen[] = $deelname->getTraining();
            }
        }

        return $trainingen;
    }

    public function getKlantFieldName(): string
    {
        return 'klant';
    }
}

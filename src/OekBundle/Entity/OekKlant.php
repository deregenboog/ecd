<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity(repositoryClass="OekBundle\Repository\OekKlantRepository")
 * @ORM\Table(name="oek_klanten")
 * @ORM\HasLifecycleCallbacks
 */
class OekKlant
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * History of states.
     *
     * @var OekDossierStatus[]
     *
     * @ORM\ManyToMany(targetEntity="OekDossierStatus", cascade={"persist"})
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $oekDossierStatussen;

    /**
     * Current state.
     *
     * @var OekDossierStatus
     *
     * @ORM\ManyToOne(targetEntity="OekDossierStatus", cascade={"persist"})
     */
    private $oekDossierStatus;

    /**
     * Current aanmelding.
     *
     * @var OekAanmelding
     *
     * @ORM\ManyToOne(targetEntity="OekAanmelding")
     */
    private $oekAanmelding;

    /**
     * Current afsluiting.
     *
     * @var OekAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="OekAfsluiting")
     */
    private $oekAfsluiting;

    /**
     * @var Klant
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $klant;

    /**
     * @var ArrayCollection|OekLidmaatschap[]
     * @ORM\OneToMany(targetEntity="OekLidmaatschap", mappedBy="oekKlant")
     */
    private $oekLidmaatschappen;

    /**
     * @var ArrayCollection|OekDeelname[]
     * @ORM\OneToMany(targetEntity="OekDeelname", mappedBy="oekKlant")
     */
    private $oekDeelnames;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $voedselbankKlant = false;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $opmerking;

    public function __construct()
    {
        $this->oekLidmaatschappen = new ArrayCollection();
        $this->oekDeelnames = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->klant;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKlant()
    {
        return $this->klant;
    }

    public function setKlant(Klant $klant)
    {
        $this->klant = $klant;

        return $this;
    }

    public function getOekGroepen()
    {
        $oekGroepen = new ArrayCollection();
        foreach ($this->oekLidmaatschappen as $oekLidmaatschap) {
            $oekGroepen[] = $oekLidmaatschap->getOekGroep();
        }

        return $oekGroepen;
    }

    public function getOekTrainingen()
    {
        $oekTrainingen = new ArrayCollection();
        foreach ($this->oekDeelnames as $oekDeelname) {
            $oekTrainingen[] = $oekDeelname->getOekTraining();
        }

        return $oekTrainingen;
    }

    public function isDeletable()
    {
        // @todo: implement for real
        return false;
    }

    public function getOekDossierStatussen()
    {
        return $this->oekDossierStatussen;
    }

    public function setOekAanmelding(OekAanmelding $oekAanmelding)
    {
        return $this->addOekAanmelding($oekAanmelding);
    }

    public function addOekAanmelding(OekAanmelding $oekAanmelding)
    {
        if ($this->oekDossierStatus instanceof OekAanmelding) {
            throw new \RuntimeException('Er is een fout opgetreden bij het aanpassen van de dossierstatus.');
        }

        $this->oekDossierStatussen[] = $oekAanmelding;
        $this->oekDossierStatus = $oekAanmelding;
        $this->oekAanmelding = $oekAanmelding;
        $this->oekAfsluiting = null;
        $oekAanmelding->setOekKlant($this);

        return $this;
    }

    public function addOekAfsluiting(OekAfsluiting $oekAfsluiting)
    {
        if (!$this->oekDossierStatus instanceof OekAanmelding) {
            throw new \RuntimeException('Er is een fout opgetreden bij het aanpassen van de dossierstatus.');
        }

        $this->oekDossierStatussen[] = $oekAfsluiting;
        $this->oekDossierStatus = $oekAfsluiting;
        $this->oekAfsluiting = $oekAfsluiting;
        $oekAfsluiting->setOekKlant($this);

        return $this;
    }

//     public function addOekDossierStatus(OekDossierStatus $oekDossierStatus)
//     {
//         // initial state must be of type OekInitialDossierStatus
//         if (!$this->oekDossierStatus && !$oekDossierStatus instanceof InitialStateInterface) {
//             throw new \RuntimeException('Er is een fout opgetreden bij het aanpassen van de dossierstatus.');
//         }

//         // state change must involve two different states
//         if (get_class($this->oekDossierStatus) === get_class($oekDossierStatus)) {
//             throw new \RuntimeException('Er is een fout opgetreden bij het aanpassen van de dossierstatus.');
//         }

//         $this->oekDossierStatussen[] = $oekDossierStatus;
//         $this->oekDossierStatus = $oekDossierStatus;
//         $oekDossierStatus->setOekKlant($this);

//         return $this;
//     }

    public function getOekDossierStatus()
    {
        return $this->oekDossierStatus;
    }

    /**
     * Returns the current OekAanmelding instance.
     *
     * @return OekAanmelding
     */
    public function getOekAanmelding()
    {
        return $this->oekAanmelding;
    }

    /**
     * Returns the current OekAfsluiting instance.
     *
     * @return OekAfsluiting
     */
    public function getOekAfsluiting()
    {
        return $this->oekAfsluiting;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking = null)
    {
        $this->opmerking = $opmerking;

        return $this;
    }

    public function getOekDeelname(OekTraining $oekTraining)
    {
        foreach ($this->oekDeelnames as $oekDeelname) {
            if ($oekDeelname->getOekTraining() == $oekTraining) {
                return $oekDeelname;
            }
        }
    }

    public function getOekLidmaatschappen()
    {
        return $this->oekLidmaatschappen;
    }

    public function getOekDeelnames()
    {
        return $this->oekDeelnames;
    }

    /**
     * @param bool $voedselbankKlant
     * @return self
     */
    public function setVoedselbankKlant($voedselbankKlant)
    {
        $this->voedselbankKlant = $voedselbankKlant;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVoedselbankKlant()
    {
        return $this->voedselbankKlant;
    }
}

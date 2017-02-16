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
     * @var ArrayCollection|OekGroep[]
     * @ORM\ManyToMany(targetEntity="OekGroep", mappedBy="oekKlanten")
     */
    private $oekGroepen;

    /**
     * @var ArrayCollection|OekTraining[]
     * @ORM\ManyToMany(targetEntity="OekTraining", mappedBy="oekKlanten")
     */
    private $oekTrainingen;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $opmerking;

    public function __construct()
    {
        $this->oekGroepen = new ArrayCollection();
        $this->oekTrainingen = new ArrayCollection();
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
        return $this->oekGroepen;
    }

    public function addOekGroep(OekGroep $oekGroep)
    {
        $this->oekGroepen->add($oekGroep);

        return $this;
    }

    public function removeOekGroep(OekGroep $oekGroep)
    {
        if ($this->oekGroepen->contains($oekGroep)) {
            $this->oekGroepen->removeElement($oekGroep);
        }

        return $this;
    }

    public function getOekTrainingen()
    {
        return $this->oekTrainingen;
    }

    public function addOekTraining(OekTraining $oekTraining)
    {
        $this->oekTrainingen->add($oekTraining);

        return $this;
    }

    public function removeOekTraining(OekTraining $oekTraining)
    {
        if ($this->oekTrainingen->contains($oekTraining)) {
            $this->oekTrainingen->removeElement($oekTraining);
        }

        return $this;
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
}

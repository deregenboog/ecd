<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Medewerker;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredKlantTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ReflectionClass;

/**
 * @ORM\Entity(repositoryClass="MwBundle\Repository\KlantRepository")
 * @ORM\Table(name="mw_klanten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Klant implements Workflow
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredKlantTrait;
    use RequiredMedewerkerTrait;

    /**
     * Current marking (used by workflow component).
     *
     * @ORM\Column(options={"default": "aangemaakt"})
     */
    private $marking = 'aangemaakt';

    /**
     * History of states.
     *
     * @var DossierStatus[]
     *
     * @ORM\ManyToMany(targetEntity="DossierStatus", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="mw_klant_dossierstatus",
     *     joinColumns={@ORM\JoinColumn(name="mw_klant_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mw_dossierstatus_id")}
     * )
     * @ORM\OrderBy({"datum": "desc", "id": "desc"})
     */
    private $dossierStatussen;

    /**
     * Current state.
     *
     * @var DossierStatus
     *
     * @ORM\ManyToOne(targetEntity="DossierStatus", cascade={"persist"})
     * @ORM\JoinColumn(name="mw_dossierstatus_id")
     * @Gedmo\Versioned
     */
    private $dossierStatus;

    /**
     * @var Intake[]
     *
     * @ORM\OneToMany(targetEntity="Intake", mappedBy="klant")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $intakes;

    /**
     * @var Verslag[]
     *
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="klant")
     */
    private $verslagen;

    public function __construct(AppKlant $klant, Medewerker $medewerker)
    {
        $this->klant = $klant;
        $this->medewerker = $medewerker;
        $this->dossierStatussen = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatFormal($this->klant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getDossierStatussen()
    {
        return $this->dossierStatussen;
    }

    public function getDossierStatus()
    {
        return $this->dossierStatus;
    }

    public function addDossierStatus(DossierStatus $dossierStatus)
    {
        $this->dossierStatussen[] = $dossierStatus;
        $this->dossierStatus = $dossierStatus;
        $this->updateMarking($dossierStatus);

        return $this;
    }

    public function setDossierStatus(DossierStatus $dossierStatus)
    {
        return $this->addDossierStatus($dossierStatus);
    }

    public function getMarking(): string
    {
        return $this->marking;
    }

    private function updateMarking(DossierStatus $dossierStatus): void
    {
        switch (true) {
            case $dossierStatus instanceof Afsluiting:
                $this->marking = 'afgesloten';
                break;
            case $dossierStatus instanceof IntakeGezin:
                $this->marking = 'in_traject';
                break;
            case $dossierStatus instanceof Intake:
                // convert class short name to snake case
                $name = (new ReflectionClass($dossierStatus))->getShortName();
                $name = strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($name)));

                $this->marking = $name.'_afgerond';
                break;
            case $dossierStatus instanceof Aanmelding:
                $this->marking = 'aangemeld';
                break;
            default:
                $this->marking = 'aangemaakt';
                break;
        }
    }

    public function getVerslagen()
    {
        return $this->verslagen;
    }

    public function getLaatsteVerslag(): ?Verslag
    {
        $latest = null;
        foreach ($this->verslagen as $verslag) {
            if (null == $latest || $verslag->getDatum() > $latest->getDatum()) {
                $latest = $verslag;
            }
        }

        return $latest;
    }
}

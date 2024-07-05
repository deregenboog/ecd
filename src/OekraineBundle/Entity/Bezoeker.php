<?php

namespace OekraineBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NotDeletableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="oekraine_bezoekers")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Bezoeker implements DocumentSubjectInterface
{
    use IdentifiableTrait;
    use DocumentSubjectTrait;
    use TimestampableTrait;
    use NotDeletableTrait;

    /**
     * @var AppKlant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    private $appKlant;

    /**
     * @var DossierStatus
     *
     * @ORM\OneToOne(targetEntity="DossierStatus", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $dossierStatus;

    /**
     * @var DossierStatus[]
     *
     * @ORM\OneToMany(targetEntity="DossierStatus", mappedBy="bezoeker")
     *
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $dossierStatussen;

    /**
     * @var Intake
     *
     * @ORM\OneToOne(targetEntity="Intake", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    private $intake;

    /**
     * @var Intake[]
     *
     * @ORM\OneToMany(targetEntity="Intake", mappedBy="bezoeker", fetch="EAGER")
     *
     * @ORM\OrderBy({"intakedatum" = "DESC", "id" = "DESC"})
     */
    private $intakes;

    /**
     * @var Registratie[]
     *
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="bezoeker")
     *
     * @ORM\OrderBy({"binnen" = "DESC", "id" = "DESC"})
     */
    private $registraties;

    /**
     * @var Verslag[]
     *
     * @ORM\OneToMany(targetEntity="Verslag", mappedBy="bezoeker", cascade={"persist"})
     *
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    /**
     * @var Incident[]
     *
     * @ORM\OneToMany(targetEntity="Incident", mappedBy="bezoeker", cascade={"persist"})
     *
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $incidenten;

    public function __construct(?AppKlant $klant = null)
    {
        if ($klant) {
            $this->appKlant = $klant;
        }
        $this->dossierStatussen = new ArrayCollection();
        $this->intakes = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->verslagen = new ArrayCollection();
        $this->incidenten = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->appKlant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getAppKlant(): AppKlant
    {
        return $this->appKlant;
    }

    public function setAppKlant(AppKlant $appKlant): Bezoeker
    {
        $this->appKlant = $appKlant;

        return $this;
    }

    public function getDossierStatus(): ?DossierStatus
    {
        return $this->dossierStatus;
    }

    public function setDossierStatus(DossierStatus $dossierStatus): Bezoeker
    {
        $this->dossierStatus = $dossierStatus;

        return $this;
    }

    public function setHuidigeStatus(DossierStatus $dossierStatus): Bezoeker
    {
        return $this->setDossierStatus($dossierStatus);
    }

    public function getHuidigeStatus(): DossierStatus
    {
        return $this->dossierStatus;
    }

    public function getStatussen()
    {
        return $this->dossierStatussen;
    }

    public function getIntake(): ?Intake
    {
        return $this->intake;
    }

    public function addIntake(Intake $intake): Bezoeker
    {
        $this->intakes->add($intake);
        $this->intake = $intake;

        return $this;
    }

    public function getLaatsteIntake()
    {
        $e = $this->intakes->last();
        if (false == $e) {
            $e = null;
        }

        return $e;
    }

    public function getIntakes()
    {
        return $this->intakes;
    }

    public function getLaatsteRegistratie(): ?Registratie
    {
        $r = end($this->registraties);
        if (false == $r) {
            $r = null;
        }

        return $r;
    }

    public function addRegistratie(Registratie $registratie): Bezoeker
    {
        $this->registraties->add($registratie);

        return $this;
    }

    public function getRecenteRegistraties($num = 5)
    {
        return $this->registraties;
    }

    /**
     * @return Verslag[]
     */
    public function getVerslagen()
    {
        return $this->verslagen;
    }

    /**
     * @param Verslag[] $verslagen
     */
    public function setVerslagen(array $verslagen): void
    {
        $this->verslagen = $verslagen;
    }

    public function addVerslag(Verslag $verslag): void
    {
        $this->verslagen[] = $verslag;
    }

    public function getIncidenten()
    {
        return $this->incidenten;
    }

    public function addIncident(Incident $incident): self
    {
        $this->incidenten->add($incident);

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }
}

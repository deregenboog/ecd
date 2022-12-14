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
 * @ORM\Table(name="oekraine_bezoekers")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $appKlant;

    /**
     * @var DossierStatus
     *
     * @ORM\OneToOne(targetEntity="OekraineBundle\Entity\DossierStatus", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $dossierStatus;

    /**
     * @var DossierStatus[]
     *
     * @ORM\OneToMany(targetEntity="OekraineBundle\Entity\DossierStatus", mappedBy="bezoeker")
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $dossierStatussen;

    /**
     * @var Intake
     *
     * @ORM\OneToOne(targetEntity="OekraineBundle\Entity\Intake", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    private $intake;

    /**
     * @var Intake[]
     *
     * @ORM\OneToMany(targetEntity="OekraineBundle\Entity\Intake", mappedBy="bezoeker", fetch="EAGER")
     * @ORM\OrderBy({"intakedatum" = "DESC", "id" = "DESC"})
     */
    private $intakes;


    /**
     * @var Registratie[]
     *
     * @ORM\OneToMany(targetEntity="OekraineBundle\Entity\Registratie", mappedBy="bezoeker")
     * @ORM\OrderBy({"binnen" = "DESC", "id" = "DESC"})
     */
    private $registraties;

    /**
     * @var Verslag[]
     *
     * @ORM\OneToMany(targetEntity="OekraineBundle\Entity\Verslag", mappedBy="bezoeker", cascade={"persist"})
     * @ORM\OrderBy({"datum" = "DESC", "id" = "DESC"})
     */
    private $verslagen;

    public function __construct(AppKlant $klant = null)
    {
//        $this->verslagen = new ArrayCollection();
        if ($klant) {
            $this->appKlant = $klant;
        }

    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->appKlant);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    /**
     * @return AppKlant
     */
    public function getAppKlant(): AppKlant
    {
        return $this->appKlant;
    }

    /**
     * @param AppKlant $appKlant
     * @return Bezoeker
     */
    public function setAppKlant(AppKlant $appKlant): Bezoeker
    {
        $this->appKlant = $appKlant;
        return $this;
    }

    /**
     * @return DossierStatus
     */
    public function getDossierStatus(): ?DossierStatus
    {
        return $this->dossierStatus;
    }

    /**
     * @param DossierStatus $dossierStatus
     * @return Bezoeker
     */
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
    /**
     * @return Intake
     */
    public function getIntake(): ?Intake
    {
        return $this->intake;
    }

    /**
     * @param Intake $intake
     * @return Bezoeker
     */
    public function addIntake(Intake $intake): Bezoeker
    {
        $this->intakes->add($intake);
        $this->intake = $intake;
        return $this;
    }

    public function getLaatsteIntake()
    {
        $e = $this->intakes->last();
        if($e == false) $e = null;
        return $e;
    }

    public function getIntakes()
    {
        return $this->intakes;
    }
    /**
     * @return Registratie
     */
    public function getLaatsteRegistratie(): ?Registratie
    {
        $r = end($this->registraties);
        if($r == false) $r = null;

        return $r;
    }


    /**
     * @param Registratie $registratie
     * @return Bezoeker
     */
    public function addRegistratie(Registratie $registratie): Bezoeker
    {
        $this->registraties->add($registratie);
        return $this;
    }

    public function getRecenteRegistraties($num=5)
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

    /**
     * @param Verslag $verslag
     */
    public function addVerslag(Verslag $verslag): void
    {
        $this->verslagen[] = $verslag;
    }



    public function isDeletable()
    {
        return false;
    }
}

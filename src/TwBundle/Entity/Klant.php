<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Zrm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use TwBundle\Entity\Project;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Klant extends Deelnemer
{
    /**
     * @var KlantAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="KlantAfsluiting", inversedBy="huurders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $afsluiting;

    /**
     * @var ArrayCollection|Huurverzoek[]
     *
     * @ORM\OneToMany(targetEntity="Huurverzoek", mappedBy="klant", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC", "id" = "DESC"})
     */
    private $huurverzoeken;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $automatischeIncasso;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $inschrijvingWoningnet;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $waPolis;

    /**
     * @var Huurbudget
     * @ORM\ManyToOne(targetEntity="Huurbudget",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"maxValue" = "ASC"})
     */
    private $huurbudget;

    /**
     * @var DuurThuisloos
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\DuurThuisloos",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"maxValue" = "ASC"})
     */
    private $duurThuisloos;

    /**
     * @var Werk
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Werk",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $werk;

    /**
     * @var Project[]
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(
     *     name="tw_huurders_tw_projecten",
     *     joinColumns={@ORM\JoinColumn(name="tw_huurder_id",nullable=true)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tw_project_id")}
     * )
     */
    protected $projecten;

    /**
     * @var Zrm
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Zrm", cascade={"persist"})
     */
    private $zrm;


    /**
     * @var Regio
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Regio",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $bindingRegio;


    /**
     * @var MoScreening
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\MoScreening",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $moScreening;




    public function __construct(AppKlant $klant = null)
    {
        if(null !== $klant){
            $this->appKlant = $klant;
        }
        parent::__construct();

        $this->huurverzoeken = new ArrayCollection();
    }

    public function isActief()
    {
        return null === $this->afsluiting;
    }

    public function isClosable()
    {
        $today = new \DateTime('today');

        $actieveHuurverzoeken = array_filter($this->huurverzoeken->toArray(), function (Huurverzoek $huurverzoek) use ($today) {
            if ($huurverzoek->getAfsluitdatum() && $huurverzoek->getAfsluitdatum() <= $today) {
                return false;
            }
            $huurovereenkomst = $huurverzoek->getHuurovereenkomst();
            if ($huurovereenkomst && $huurovereenkomst->getAfsluitdatum() && $huurovereenkomst->getAfsluitdatum() <= $today) {
                return false;
            }

            return true;
        });

        return 0 === count($actieveHuurverzoeken);
    }

    public function isDeletable()
    {
        return 0 === count($this->huurverzoeken)
            && 0 === count($this->documenten)
            && 0 === count($this->verslagen);
    }

    public function getHuurverzoeken()
    {
        return $this->huurverzoeken;
    }

    public function addHuurverzoek(Huurverzoek $huurverzoek)
    {
        $this->huurverzoeken[] = $huurverzoek;
        $huurverzoek->setKlant($this);

        return $this;
    }

    public function getHuurovereenkomsten()
    {
        $huurovereenkomsten = [];
        foreach ($this->huurverzoeken as $huurverzoek) {
            if ($huurverzoek->getHuurovereenkomst()) {
                $huurovereenkomsten[] = $huurverzoek->getHuurovereenkomst();
            }
        }

        usort($huurovereenkomsten, function ($huurovereenkomst1, $huurovereenkomst2) {
            if ($huurovereenkomst1->getStartdatum() < $huurovereenkomst2->getStartdatum()) {
                return 1;
            } elseif ($huurovereenkomst1->getStartdatum() > $huurovereenkomst2->getStartdatum()) {
                return -1;
            } else {
                return 0;
            }
        });

        return $huurovereenkomsten;
    }


    public function getAfsluiting()
    {
        return $this->afsluiting;
    }

    public function setAfsluiting(KlantAfsluiting $afsluiting)
    {
        $this->afsluiting = $afsluiting;

        return $this;
    }


    public function getAutomatischeIncasso()
    {
        return $this->automatischeIncasso;
    }
    public function isAutomatischeIncasso()
    {
        return $this->automatischeIncasso;
    }

    public function setAutomatischeIncasso($automatischeIncasso)
    {
        $this->automatischeIncasso = (bool) $automatischeIncasso;

        return $this;
    }

    public function isInschrijvingWoningnet()
    {
        return $this->inschrijvingWoningnet;
    }

    public function setInschrijvingWoningnet($inschrijvingWoningnet)
    {
        $this->inschrijvingWoningnet = (bool) $inschrijvingWoningnet;

        return $this;
    }

    public function isWaPolis()
    {
        return $this->waPolis;
    }

    public function setWaPolis($waPolis)
    {
        $this->waPolis = (bool) $waPolis;

        return $this;
    }

    /**
     * @return Huurbudget
     */
    public function getHuurbudget(): ?Huurbudget
    {
        return $this->huurbudget;
    }

    /**
     * @param Huurbudget $huurbudget
     */
    public function setHuurbudget(Huurbudget $huurbudget): void
    {
        $this->huurbudget = $huurbudget;
    }

    /**
     * @return DuurThuisloos
     */
    public function getDuurThuisloos(): ?DuurThuisloos
    {
        return $this->duurThuisloos;
    }

    /**
     * @param DuurThuisloos $duurThuisloos
     */
    public function setDuurThuisloos(DuurThuisloos $duurThuisloos): void
    {
        $this->duurThuisloos = $duurThuisloos;
    }

    /**
     * @return Werk
     */
    public function getWerk(): ?Werk
    {
        return $this->werk;
    }

    /**
     * @param Werk $werk
     */
    public function setWerk(Werk $werk): void
    {
        $this->werk = $werk;
    }

    /**
     * @return Project[]
     */
    public function getProjecten()//: ?arraPersistentCollection
    {
        return $this->projecten;
    }

    /**
     * @param Project[] $projecten
     * @return Klant
     */
    public function setProjecten($projecten): Klant
    {
        $this->projecten = $projecten;
        return $this;
    }


    /**
     * @return Zrm
     */
    public function getZrm(): ?Zrm
    {
        return $this->zrm;
    }

    /**
     * @param Zrm $zrm
     */
    public function setZrm(Zrm $zrm)
    {
        $zrm->setRequestModule("TwHuurder");
        $zrm->setKlant($this->getAppKlant());
        $this->zrm = $zrm;
    }

    /**
     * @return Regio
     */
    public function getBindingRegio(): ?Regio
    {
        return $this->bindingRegio;
    }

    /**
     * @param Regio $bindingRegio
     * @return Klant
     */
    public function setBindingRegio(Regio $bindingRegio): Klant
    {
        $this->bindingRegio = $bindingRegio;
        return $this;
    }


    /**
     * @return MoScreening
     */
    public function getMoScreening(): ?MoScreening
    {
        return $this->moScreening;
    }

    /**
     * @param MoScreening $moScreening
     * @return Klant
     */
    public function setMoScreening(MoScreening $moScreening): Klant
    {
        $this->moScreening = $moScreening;
        return $this;
    }





    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ((!$this->moScreening || $this->moScreening->getLabel() =="Niet gescreend") && !$this->zrm->getJustitie()) {
            $context->buildViolation('Zrm is verplicht wanneer er geen MO screening is geweest. Maak de Zrm of pas de screening aan.')
                ->atPath('moScreening')
                ->addViolation();

        }
    }
}

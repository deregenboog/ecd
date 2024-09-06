<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Zrm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Klant extends Deelnemer
{
    /**
     * @var KlantAfsluiting
     *
     * @ORM\ManyToOne(targetEntity="KlantAfsluiting", inversedBy="huurders", cascade={"persist"})
     *
     * @Gedmo\Versioned
     */
    protected $afsluiting;

    /**
     * @var ArrayCollection|Huurverzoek[]
     *
     * @ORM\OneToMany(targetEntity="Huurverzoek", mappedBy="klant", cascade={"persist"})
     *
     * @ORM\OrderBy({"startdatum" = "DESC", "id" = "DESC"})
     */
    private $huurverzoeken;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $automatischeIncasso;

    /**
     * @var InschrijvingWoningnet
     *
     * @ORM\ManyToOne (targetEntity="TwBundle\Entity\InschrijvingWoningnet")
     *
     * @ORM\JoinColumn (nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $inschrijvingWoningnet;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $waPolis;

    /**
     * @var Huurbudget
     *
     * @ORM\ManyToOne(targetEntity="Huurbudget",cascade={"persist"})
     *
     * @ORM\OrderBy({"maxValue" = "ASC"})
     */
    private $huurbudget;

    /**
     * @var DuurThuisloos
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\DuurThuisloos",cascade={"persist"})
     *
     * @ORM\OrderBy({"maxValue" = "ASC"})
     */
    private $duurThuisloos;

    /**
     * @var Werk
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Werk",cascade={"persist"})
     */
    private $werk;

    /**
     * @var bool
     *
     * @ORM\Column (nullable=true)
     */
    private $inkomensverklaring;

    /**
     * @var Project[]
     *
     * @ORM\ManyToMany(targetEntity="Project")
     *
     * @ORM\JoinTable(
     *     name="tw_huurders_tw_projecten",
     *     joinColumns={@ORM\JoinColumn(name="tw_huurder_id")},
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
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\Regio",cascade={"persist"})
     */
    private $bindingRegio;

    /**
     * @var MoScreening
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\MoScreening",cascade={"persist"})
     */
    private $moScreening;

    /**
     * @var IntakeStatus
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\IntakeStatus",cascade={"persist"})
     */
    private $intakeStatus;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne (targetEntity="AppBundle\Entity\Medewerker",cascade={"persist"})
     */
    private $shortlist;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $toelichtingInkomen;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="Klant")
     *
     * @ORM\JoinColumn(name="huisgenoot_id", referencedColumnName="id")
     *
     * @Gedmo\Versioned
     */
    private $huisgenoot;

    public function __construct(?AppKlant $klant = null)
    {
        if (null !== $klant) {
            $this->appKlant = $klant;
        }
        parent::__construct();

        $this->huurverzoeken = new ArrayCollection();
    }

    public function isActief()
    {
        return null === $this->afsluiting;
    }

    public function reopen()
    {
        $verslag = new Verslag($this);
        $verslag->setDatum(new \DateTime());
        $verslag->setMedewerker($this->getMedewerker());
        $verslag->setOpmerking(sprintf("Dossier heropend. Eerder afgesloten met als reden: '%s', op %s",$this->getAfsluiting(), $this->getAfsluitdatum()->format("d-m-Y")));

        $this->setAfsluitdatum(null);
        $this->setAfsluiting(null);

        $this->addVerslag($verslag);
        return $this;
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

    public function setAfsluiting(?KlantAfsluiting $afsluiting)
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

    public function getInschrijvingWoningnet(): ?InschrijvingWoningnet
    {
        return $this->inschrijvingWoningnet;
    }

    public function setInschrijvingWoningnet(InschrijvingWoningnet $inschrijvingWoningnet)
    {
        $this->inschrijvingWoningnet = $inschrijvingWoningnet;

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

    public function getHuurbudget(): ?Huurbudget
    {
        return $this->huurbudget;
    }

    public function setHuurbudget(Huurbudget $huurbudget): void
    {
        $this->huurbudget = $huurbudget;
    }

    public function getDuurThuisloos(): ?DuurThuisloos
    {
        return $this->duurThuisloos;
    }

    public function setDuurThuisloos(?DuurThuisloos $duurThuisloos): void
    {
        $this->duurThuisloos = $duurThuisloos;
    }

    public function getWerk(): ?Werk
    {
        return $this->werk;
    }

    public function setWerk(?Werk $werk): void
    {
        $this->werk = $werk;
    }

    /**
     * @return Project[]
     */
    public function getProjecten()// : ?arraPersistentCollection
    {
        return $this->projecten;
    }

    /**
     * @param Project[] $projecten
     */
    public function setProjecten($projecten): Klant
    {
        $this->projecten = $projecten;

        return $this;
    }

    public function getZrm(): ?Zrm
    {
        return $this->zrm;
    }

    public function setZrm(Zrm $zrm)
    {
        $zrm->setRequestModule('TwHuurder');
        $zrm->setKlant($this->getKlant());
        $this->zrm = $zrm;
    }

    public function getBindingRegio(): ?Regio
    {
        return $this->bindingRegio;
    }

    public function setBindingRegio(Regio $bindingRegio): Klant
    {
        $this->bindingRegio = $bindingRegio;

        return $this;
    }

    public function getMoScreening(): ?MoScreening
    {
        return $this->moScreening;
    }

    public function setMoScreening(MoScreening $moScreening): Klant
    {
        $this->moScreening = $moScreening;

        return $this;
    }

    public function getIntakeStatus(): ?IntakeStatus
    {
        return $this->intakeStatus;
    }

    public function setIntakeStatus(IntakeStatus $intakeStatus): Klant
    {
        $this->intakeStatus = $intakeStatus;

        return $this;
    }

    public function isInkomensverklaring(): ?bool
    {
        return $this->inkomensverklaring;
    }

    public function setInkomensverklaring(?bool $inkomensverklaring): Deelnemer
    {
        $this->inkomensverklaring = $inkomensverklaring;

        return $this;
    }

    public function getToelichtingInkomen(): ?string
    {
        return $this->toelichtingInkomen;
    }

    public function setToelichtingInkomen(?string $toelichtingInkomen): Klant
    {
        $this->toelichtingInkomen = $toelichtingInkomen;

        return $this;
    }

    public function getShortlist(): ?Medewerker
    {
        return $this->shortlist;
    }

    public function setShortlist(?Medewerker $shortlist): Klant
    {
        $this->shortlist = $shortlist;

        return $this;
    }

    public function getHuisgenoot(): ?Klant
    {
        return $this->huisgenoot;
    }

    public function setHuisgenoot(?Klant $huisgenoot): ?Klant
    {
        $this->huisgenoot = $huisgenoot;

        return $this;
    }

    public function isGekoppeld()
    {
        $today = new \DateTime('today');
        $hoes = $this->getHuurovereenkomsten();
        foreach ($hoes as $hoe) {
            /** @var Huurovereenkomst $hoe */
            if (false == $hoe->isReservering()
                && true == $hoe->isActief()
                && (null == $hoe->getAfsluitdatum() || $hoe->getAfsluitdatum() > $today)
                && null != $hoe->getStartdatum()

                // && $this->getAfsluitdatum() == null
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        return;
        // Wijziging: MO screening toch niet verplicht als geen ZRM.
        if ((!$this->moScreening || 'Niet gescreend' == $this->moScreening->getLabel()) && !$this->zrm->getJustitie()) {
            $context->buildViolation('Zrm is verplicht wanneer er geen MO screening is geweest. Maak de Zrm of pas de screening aan.')
                ->atPath('moScreening')
                ->addViolation();
        }
    }
}

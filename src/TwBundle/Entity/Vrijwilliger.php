<?php

namespace TwBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MemoSubjectInterface;
use AppBundle\Model\MemoSubjectTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table("tw_vrijwilligers")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Vrijwilliger implements MemoSubjectInterface, DocumentSubjectInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;
    use MemoSubjectTrait;
    use DocumentSubjectTrait;

    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var Locatie
     *
     * @ORM\ManyToMany(targetEntity="TwBundle\Entity\Locatie")
     */
    protected $locaties;

    /**
     * @var BinnenVia
     *
     * @ORM\ManyToOne(targetEntity="TwBundle\Entity\BinnenVia")
     *
     * @ORM\JoinColumn(name="binnen_via_id")
     */
    protected $binnenVia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    protected $aanmelddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $afsluitdatum;

    /**
     * @var Afsluitreden
     *
     * @ORM\ManyToOne(targetEntity="Afsluitreden")
     */
    protected $afsluitreden;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $stagiair = false;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    protected $notitieIntake;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $datumNotitieIntake;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    protected $trainingOverig;

    /**
     * @var \DateTime
     *
     * @ORM\Column(nullable=true)
     */
    protected $trainingOverigDatum;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname",mappedBy="vrijwilliger", cascade={"persist"})
     */
    protected $trainingDeelnames;

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

    public function __construct(?AppVrijwilliger $vrijwilliger = null)
    {
        if ($vrijwilliger) {
            $this->vrijwilliger = $vrijwilliger;
        }
        $this->locaties = new ArrayCollection();
        $this->trainingDeelnames = new ArrayCollection();
        $this->datumNotitieIntake = new \DateTime('now');
    }

    public function __toString()
    {
        return (string) $this->vrijwilliger;
    }

    public function isDeletable()
    {
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(AppVrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    public function getAanmelddatum()
    {
        return $this->aanmelddatum;
    }

    public function setAanmelddatum(\DateTime $aanmelddatum)
    {
        $this->aanmelddatum = $aanmelddatum;

        return $this;
    }

    public function getAfsluitdatum()
    {
        return $this->afsluitdatum;
    }

    public function setAfsluitdatum(?\DateTime $afsluitdatum)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function getAfsluitreden()
    {
        return $this->afsluitreden;
    }

    public function setAfsluitreden(?Afsluitreden $afsluitreden)
    {
        $this->afsluitreden = $afsluitreden;

        return $this;
    }

    public function getBinnenVia()
    {
        return $this->binnenVia;
    }

    public function setBinnenVia(BinnenVia $binnenVia)
    {
        $this->binnenVia = $binnenVia;

        return $this;
    }

    public function getLocaties()
    {
        return $this->locaties;
    }

    public function addLocatie(Locatie $locatie)
    {
        $this->locaties->add($locatie);

        return $this;
    }

    public function removeLocatie(Locatie $locatie)
    {
        $this->locaties->removeElement($locatie);

        return $this;
    }

    public function isActief()
    {
        return null === $this->afsluitdatum || $this->afsluitdatum > new \DateTime('today');
    }

    public function getMedewerker(): ?Medewerker
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker): void
    {
        $this->medewerker = $medewerker;
    }

    public function isStagiair(): bool
    {
        return (bool) $this->stagiair;
    }

    public function setStagiair(bool $stagiair): void
    {
        $this->stagiair = $stagiair;
    }

    public function getNotitieIntake(): ?string
    {
        return $this->notitieIntake;
    }

    public function setNotitieIntake(?string $notitieIntake): void
    {
        $this->notitieIntake = $notitieIntake;
    }

    public function getDatumNotitieIntake(): ?\DateTime
    {
        return $this->datumNotitieIntake;
    }

    public function setDatumNotitieIntake(\DateTime $datumNotitieIntake): void
    {
        $this->datumNotitieIntake = $datumNotitieIntake;
    }

    public function getTrainingOverig(): ?string
    {
        return $this->trainingOverig;
    }

    public function setTrainingOverig(string $trainingOverig): void
    {
        $this->trainingOverig = $trainingOverig;
    }

    public function getTrainingOverigDatum(): ?\DateTime
    {
        return $this->trainingOverigDatum;
    }

    public function setTrainingOverigDatum(\DateTime $trainingOverigDatum): void
    {
        $this->trainingOverigDatum = $trainingOverigDatum;
    }

    /**
     * @return ArrayCollection|Deelname[]
     */
    public function getTrainingDeelnames()
    {
        return $this->trainingDeelnames;
    }

    /**
     * @param ArrayCollection|Deelname[] $trainingDeelnames
     */
    public function setTrainingDeelnames($trainingDeelnames): void
    {
        $this->trainingDeelnames = $trainingDeelnames;
    }

    public function addDeelname(Deelname $deelname)
    {
        $this->trainingDeelnames[] = $deelname;
    }

    public function removeDeelname(Deelname $deelname)
    {
        $this->trainingDeelnames->removeElement($deelname);
    }
}

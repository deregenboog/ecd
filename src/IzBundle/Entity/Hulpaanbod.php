<?php

namespace IzBundle\Entity;

use AppBundle\Exception\UserException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpaanbodRepository")
 *
 * @ORM\Table(name="iz_koppelingen")
 *
 * @Gedmo\Loggable
 */
class Hulpaanbod extends Hulp
{
    public const TABLE_NAME = 'iz_koppelingen'; // nessecary for INDEX OUTPUT WALKER see KoppelingenDao.
    public const IDX_DEELNEMER_DISCR_DELETED_EINDDATUM_KOPPELING = 'idx_deelnemer_discr_deleted_einddatum_koppeling';

    /**
     * @var IzVrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="hulpaanbiedingen")
     *
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     *
     * @Gedmo\Versioned
     */
    private $izVrijwilliger;

    /**
     * @var Hulpvraag
     *
     * @ORM\OneToOne(targetEntity="Hulpvraag")
     *
     * @ORM\JoinColumn(name="iz_koppeling_id")
     *
     * @Gedmo\Versioned
     */
    private $hulpvraag;

    /**
     * @var Hulpvraagsoort[]
     *
     * @ORM\ManyToMany(targetEntity="Hulpvraagsoort")
     */
    protected $hulpvraagsoorten;

    /**
     * @var bool
     *
     * @ORM\Column(name="expat", type="boolean")
     */
    private $expat = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="maatje_minder_fysiek", type="boolean")
     */
    private $maatjeMinderFysiek = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="maatje_minder_frequent", type="boolean")
     */
    private $maatjeMinderFrequent = false;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="maatje_minder_lang", type="boolean")
     */
    private $maatjeMinderLang = false;

    /**
     * @deprecated
     *
     * @ORM\Column(name="voorkeur_voor_nederlands", type="boolean", nullable=true)
     */
    private $voorkeurVoorNederlands;

    /**
     * @var bool
     *
     * @deprecated
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $coachend;

    /**
     * @var ArrayCollection|Reservering[]
     *
     * @ORM\OneToMany(targetEntity="Reservering", mappedBy="hulpaanbod")
     */
    protected $reserveringen;

    public function __construct()
    {
        parent::__construct();
        $this->hulpvraagsoorten = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s | %s | %s', (string) $this->izVrijwilliger, $this->project, $this->startdatum->format('d-m-Y'));
    }

    public function getIzVrijwilliger()
    {
        return $this->izVrijwilliger;
    }

    public function setIzVrijwilliger(IzVrijwilliger $izVrijwilliger)
    {
        $this->izVrijwilliger = $izVrijwilliger;

        return $this;
    }

    public function getHulpvraag()
    {
        return $this->hulpvraag;
    }

    public function setHulpvraag(Hulpvraag $hulpvraag)
    {
        Koppeling::create($hulpvraag, $this);

        return $this;
    }

    public function getKoppeling()
    {
        if ($this->hulpvraag) {
            return new Koppeling($this->hulpvraag, $this);
        }
    }

    public function setKoppeling(Koppeling $koppeling)
    {
        if ($this->getKoppeling()) {
            throw new UserException('Dit hulpaanbod is al gekoppeld.');
        }

        $this->hulpvraag = $koppeling->getHulpvraag();

        return $this;
    }

    public function getHulpvraagsoorten()
    {
        return $this->hulpvraagsoorten;
    }

    public function setHulpvraagsoorten($hulpvraagsoorten)
    {
        $this->hulpvraagsoorten = $hulpvraagsoorten;

        return $this;
    }

    public function getDoelgroepen()
    {
        return $this->doelgroepen;
    }

    public function setDoelgroepen($doelgroepen)
    {
        $this->doelgroepen = $doelgroepen;

        return $this;
    }

    public function isExpat()
    {
        return $this->expat;
    }

    public function setExpat($expat)
    {
        $this->expat = (bool) $expat;

        return $this;
    }

    public function setMaatjeMinderFysiek($maatjeMinderFysiek)
    {
        $this->maatjeMinderFysiek = (bool) $maatjeMinderFysiek;

        return $this;
    }

    public function isMaatjeMinderFysiek()
    {
        return $this->maatjeMinderFysiek;
    }

    public function setMaatjeMinderFrequent($maatjeMinderFrequent)
    {
        $this->maatjeMinderFrequent = (bool) $maatjeMinderFrequent;

        return $this;
    }

    public function isMaatjeMinderFrequent()
    {
        return $this->maatjeMinderFrequent;
    }

    public function setMaatjeMinderLang($maatjeMinderLang)
    {
        $this->maatjeMinderLang = (bool) $maatjeMinderLang;

        return $this;
    }

    public function isMaatjeMinderLang()
    {
        return $this->maatjeMinderLang;
    }

    /**
     * @deprecated
     */
    public function isCoachend()
    {
        return (bool) $this->coachend;
    }

    /**
     * @deprecated
     */
    public function setCoachend($coachend)
    {
        $this->coachend = (bool) $coachend;

        return $this;
    }

    public function getVerslagen($includeRelated = true)
    {
        if (!$includeRelated || !$this->isGekoppeld()) {
            return $this->verslagen;
        }

        $verslagen = array_merge(
            $this->verslagen->toArray(),
            $this->hulpvraag->getVerslagen(false)->toArray()
        );
        usort($verslagen, function (Verslag $a, Verslag $b) {
            $datumA = $a->getCreated();
            $datumB = $b->getCreated();

            return $datumA < $datumB ? 1 : -1;
        });

        return $verslagen;
    }

    public function setKoppelingStartdatum(?\DateTime $startdatum = null)
    {
        $this->koppelingStartdatum = $startdatum;
        $this->getKoppeling()->setStartdatum($startdatum);

        return $this;
    }
}

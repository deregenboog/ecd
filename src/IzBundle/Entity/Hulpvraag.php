<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Exception\UserException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagRepository")
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class Hulpvraag extends Hulp
{
    public const TABLE_NAME = 'iz_koppelingen'; //nessecary for INDEX OUTPUT WALKER see KoppelingenDao.
    public const IDX_DEELNEMER_DISCR_DELETED_EINDDATUM_KOPPELING = 'idx_deelnemer_discr_deleted_einddatum_koppeling';

    /**
     * @var IzKlant
     * @ORM\ManyToOne(targetEntity="IzKlant", inversedBy="hulpvragen")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     * @Gedmo\Versioned
     */
    protected $izKlant;

    /**
     * @var Hulpaanbod
     * @ORM\OneToOne(targetEntity="Hulpaanbod")
     * @ORM\JoinColumn(name="iz_koppeling_id")
     * @Gedmo\Versioned
     */
    protected $hulpaanbod;

    /**
     * @var Hulpvraagsoort
     * @ORM\ManyToOne(targetEntity="Hulpvraagsoort")
     * @Gedmo\Versioned
     */
    protected $hulpvraagsoort;

    /**
     * @var bool
     *
     * @ORM\Column(name="expat", type="boolean")
     */
    private $geschiktVoorExpat = false;

    /**
     * @deprecated
     * @ORM\Column(name="spreekt_nederlands", type="boolean", nullable=true, options={"default":1})
     */
    private $spreektNederlands;

    /**
     * @var ArrayCollection|Reservering[]
     * @ORM\OneToMany(targetEntity="Reservering", mappedBy="hulpvraag")
     */
    protected $reserveringen;

    /**
     * @var Succesindicator[]
     * @ORM\ManyToMany(targetEntity="Succesindicator")
     */
    private $succesindicatoren;

    public function __toString()
    {
        return sprintf('%s | %s | %s', $this->izKlant, $this->project, $this->startdatum->format('d-m-Y'));
    }

    public function getIzKlant()
    {
        return $this->izKlant;
    }

    public function setIzKlant(IzKlant $izKlant)
    {
        $this->izKlant = $izKlant;

        return $this;
    }

    public function getHulpaanbod()
    {
        return $this->hulpaanbod;
    }

    public function setHulpaanbod(Hulpaanbod $hulpaanbod)
    {
        Koppeling::create($this, $hulpaanbod);

        return $this;
    }

    public function getKoppeling()
    {
        if ($this->hulpaanbod) {
            return new Koppeling($this, $this->hulpaanbod);
        }
    }

    public function setKoppeling(Koppeling $koppeling)
    {
        if ($this->getKoppeling()) {
            throw new UserException('Deze hulpvraag is al gekoppeld.');
        }

        $this->hulpaanbod = $koppeling->getHulpaanbod();

        return $this;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;
        if ($this->isGekoppeld()) {
            $this->getKoppeling()->setMedewerker($medewerker);
        }

        return $this;
    }

    public function getHulpvraagsoort()
    {
        return $this->hulpvraagsoort;
    }

    public function setHulpvraagsoort(Hulpvraagsoort $hulpvraagsoort)
    {
        $this->hulpvraagsoort = $hulpvraagsoort;

        return $this;
    }

    public function getDoelgroep()
    {
        return count($this->doelgroepen) ? $this->doelgroepen[0] : null;
    }

    public function setDoelgroep(Doelgroep $doelgroep)
    {
        $this->doelgroepen = [$doelgroep];

        return $this;
    }

    public function isGeschiktVoorExpat()
    {
        return $this->geschiktVoorExpat;
    }

    public function setGeschiktVoorExpat($geschiktVoorExpat)
    {
        $this->geschiktVoorExpat = (bool) $geschiktVoorExpat;

        return $this;
    }

    public function getVerslagen($includeRelated = true)
    {
        if (!$includeRelated || !$this->isGekoppeld()) {
            return $this->verslagen;
        }

        $verslagen = array_merge(
            $this->verslagen->toArray(),
            $this->hulpaanbod->getVerslagen(false)->toArray()
        );
        usort($verslagen, function (Verslag $a, Verslag $b) {
            $datumA = $a->getCreated();
            $datumB = $b->getCreated();

            return $datumA < $datumB ? 1 : -1;
        });

        return $verslagen;
    }

    public function getTussenevaluatie(): ?Tussenevaluatie
    {
        foreach ($this->verslagen as $verslag) {
            if ($verslag instanceof Tussenevaluatie) {
                return $verslag;
            }
        }

        return null;
    }

    public function addTussenevaluatie(Tussenevaluatie $tussenevaluatie): self
    {
        return $this->addVerslag($tussenevaluatie);
    }

    public function getEindevaluatie(): ?Eindevaluatie
    {
        foreach ($this->verslagen as $verslag) {
            if ($verslag instanceof Eindevaluatie) {
                return $verslag;
            }
        }

        return null;
    }

    public function addEindevaluatie(Eindevaluatie $eindevaluatie): self
    {
        return $this->addVerslag($eindevaluatie);
    }

    public function setKoppelingStartdatum(\DateTime $startdatum = null)
    {
        $this->koppelingStartdatum = $startdatum;
        $this->getKoppeling()->setStartdatum($startdatum);

        return $this;
    }

    public function getSuccesindicatoren()
    {
        return $this->succesindicatoren;
    }

    public function setSuccesindicatoren($indicatoren)
    {
        $this->succesindicatoren = $indicatoren;
        $this->setKoppelingSuccesvol((is_array($this->succesindicatoren) || $this->succesindicatoren instanceof \Countable ? count($this->succesindicatoren) : 0) >= 2);

        return $this;
    }
}

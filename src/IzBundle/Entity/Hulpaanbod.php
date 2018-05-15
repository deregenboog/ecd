<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Exception\AppException;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpaanbodRepository")
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class Hulpaanbod extends Koppeling
{
    /**
     * @var IzVrijwilliger
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="hulpaanbiedingen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $izVrijwilliger;

    /**
     * @var Hulpvraag
     * @ORM\OneToOne(targetEntity="Hulpvraag")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
     */
    private $hulpvraag;

    /**
     * @var Hulpvraagsoort[]
     * @ORM\ManyToMany(targetEntity="Hulpvraagsoort")
     */
    protected $hulpvraagsoorten;

    /**
     * @var bool
     *
     * @ORM\Column(name="expat", type="boolean", nullable=false)
     */
    private $expat = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $coachend = false;

    /**
     * @var Reservering
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
        return sprintf('%s | %s | %s', $this->izVrijwilliger, $this->project, $this->startdatum->format('d-m-Y'));
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
        if ($hulpvraag->getHulpaanbod() && $hulpvraag->getHulpaanbod() != $this) {
            throw new AppException('Fout bij koppelen!');
        }

        $this->hulpvraag = $hulpvraag;
        if (null === $hulpvraag->getHulpaanbod()) {
            $hulpvraag->setHulpaanbod($this);
        }

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->hulpvraag instanceof Hulpvraag;
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

    public function getHulpvraagsoorten()
    {
        return $this->hulpvraagsoorten;
    }

    public function setHulpvraagsoorten($hulpvraagsoorten)
    {
        $this->hulpvraagsoorten = $hulpvraagsoorten;

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

    public function isCoachend()
    {
        return (bool) $this->coachend;
    }

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
}

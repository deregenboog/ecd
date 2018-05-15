<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Exception\AppException;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\HulpvraagRepository")
 * @ORM\Table(name="iz_koppelingen")
 * @Gedmo\Loggable
 */
class Hulpvraag extends Koppeling
{
    /**
     * @var IzKlant
     * @ORM\ManyToOne(targetEntity="IzKlant", inversedBy="hulpvragen")
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     * @Gedmo\Versioned
     */
    protected $izKlant;

    /**
     * @var Hulpaanbod
     * @ORM\OneToOne(targetEntity="Hulpaanbod")
     * @ORM\JoinColumn(name="iz_koppeling_id", nullable=true)
     * @Gedmo\Versioned
     */
    private $hulpaanbod;

    /**
     * @var Hulpvraagsoort
     * @ORM\ManyToOne(targetEntity="Hulpvraagsoort")
     * @Gedmo\Versioned
     */
    protected $hulpvraagsoort;

    /**
     * @var bool
     *
     * @ORM\Column(name="expat", type="boolean", nullable=false)
     */
    private $geschiktVoorExpat = false;

    /**
     * @var Reservering
     * @ORM\OneToMany(targetEntity="Reservering", mappedBy="hulpvraag")
     */
    protected $reserveringen;

    public function __construct()
    {
        parent::__construct();
    }

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

    public function setHulpaanbod(Hulpaanbod $hulpaanbod = null)
    {
        if ($hulpaanbod->getHulpvraag() && $hulpaanbod->getHulpvraag() != $this) {
            throw new AppException('Fout bij koppelen!');
        }

        $this->hulpaanbod = $hulpaanbod;
        if (null === $hulpaanbod->getHulpvraag()) {
            $hulpaanbod->setHulpvraag($this);
        }

        return $this;
    }

    public function isGekoppeld()
    {
        return $this->hulpaanbod instanceof Hulpaanbod;
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
}

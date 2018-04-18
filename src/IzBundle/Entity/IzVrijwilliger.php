<?php

namespace IzBundle\Entity;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="IzBundle\Repository\IzVrijwilligerRepository")
 * @Gedmo\Loggable
 */
class IzVrijwilliger extends IzDeelnemer
{
    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(name="foreign_key", nullable=true)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var ArrayCollection|Hulpaanbod[]
     * @ORM\OneToMany(targetEntity="Hulpaanbod", mappedBy="izVrijwilliger", cascade={"persist"})
     * @ORM\OrderBy({"startdatum" = "DESC"})
     */
    private $hulpaanbiedingen;

    /**
     * @var BinnengekomenVia
     *
     * @ORM\ManyToOne(targetEntity="BinnengekomenVia")
     * @ORM\JoinColumn(name="binnengekomen_via")
     * @Gedmo\Versioned
     */
    protected $binnengekomenVia;

    public function __construct(Vrijwilliger $vrijwilliger = null)
    {
        $this->vrijwilliger = $vrijwilliger;
        $this->datumAanmelding = new \DateTime('today');
        $this->hulpaanbiedingen = new ArrayCollection();
    }

    public function __toString()
    {
        try {
            return NameFormatter::formatInformal($this->vrijwilliger);
        } catch (EntityNotFoundException $e) {
            return '';
        }
    }

    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    public function setVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }

    public function getHulpaanbiedingen()
    {
        return $this->hulpaanbiedingen;
    }

    public function getKoppelingen()
    {
        return new ArrayCollection(array_map(
            function (Hulpaanbod $hulpaanbod) {
                return $hulpaanbod->getKoppeling();
            },
            $this->getGekoppeldeHulpaanbiedingen()->toArray()
            ));
    }

    public function addHulpaanbod(Hulpaanbod $hulpaanbod)
    {
        $this->hulpaanbiedingen[] = $hulpaanbod;
        $hulpaanbod->setIzVrijwilliger($this);

        return $this;
    }

    public function getNietGekoppeldeHulpaanbiedingen()
    {
        return new ArrayCollection(array_filter(
            $this->hulpaanbiedingen->toArray(),
            function (Hulpaanbod $hulpaanbod) {
                return !$hulpaanbod->isGekoppeld();
            }
        ));
    }

    public function getGekoppeldeHulpaanbiedingen()
    {
        return new ArrayCollection(array_filter(
            $this->hulpaanbiedingen->toArray(),
            function (Hulpaanbod $hulpaanbod) {
                return $hulpaanbod->isGekoppeld();
            }
        ));
    }

    public function getOpenHulpaanbiedingen()
    {
        return new ArrayCollection(array_filter(
            $this->getNietGekoppeldeHulpaanbiedingen()->toArray(),
            function (Hulpaanbod $hulpaanbod) {
                return !$hulpaanbod->isAfgesloten();
            }
        ));
    }

    public function hasOpenHulpaanbiedingen()
    {
        return count($this->getOpenHulpaanbiedingen()) > 0;
    }

    public function getAfgeslotenHulpaanbiedingen()
    {
        return new ArrayCollection(array_filter(
            $this->getNietGekoppeldeHulpaanbiedingen()->toArray(),
            function (Hulpaanbod $hulpaanbod) {
                return $hulpaanbod->isAfgesloten();
            }
        ));
    }

    public function getBinnengekomenVia()
    {
        return $this->binnengekomenVia;
    }

    public function setBinnengekomenVia(BinnengekomenVia $binnengekomenVia = null)
    {
        $this->binnengekomenVia = $binnengekomenVia;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }

    public function isCloseable()
    {
        return 0 === count($this->getOpenHulpaanbiedingen()) + count($this->getActieveKoppelingen());
    }
}

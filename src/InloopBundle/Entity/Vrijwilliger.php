<?php

namespace InloopBundle\Entity;

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
 * @ORM\Table("inloop_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Vrijwilliger implements MemoSubjectInterface, DocumentSubjectInterface
{
    use IdentifiableTrait, TimestampableTrait, RequiredMedewerkerTrait, MemoSubjectTrait, DocumentSubjectTrait;

    /**
     * @var Vrijwilliger
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    protected $vrijwilliger;

    /**
     * @var Vrijwilliger
     *
     * @ORM\ManyToMany(targetEntity="Locatie")
     */
    protected $locaties;

    /**
     * @var BinnenVia
     *
     * @ORM\ManyToOne(targetEntity="BinnenVia")
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
     * @var \DateTime
     *
     * @ORM\ManyToOne(targetEntity="Afsluitreden")
     */
    protected $afsluitreden;

    public function __construct(AppVrijwilliger $vrijwilliger = null)
    {
        if ($vrijwilliger) {
            $this->vrijwilliger = $vrijwilliger;
        }
        $this->locaties = new ArrayCollection();
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

    public function setAfsluitdatum(\DateTime $afsluitdatum)
    {
        $this->afsluitdatum = $afsluitdatum;

        return $this;
    }

    public function getAfsluitreden()
    {
        return $this->afsluitreden;
    }

    public function setAfsluitreden(Afsluitreden $afsluitreden)
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
}

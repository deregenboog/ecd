<?php

namespace InloopBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\MemoSubjectTrait;
use AppBundle\Model\DocumentSubjectTrait;
use AppBundle\Model\MemoSubjectInterface;
use AppBundle\Model\DocumentSubjectInterface;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

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
}

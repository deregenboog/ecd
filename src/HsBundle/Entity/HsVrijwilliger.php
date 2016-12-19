<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Vrijwilliger;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 */
class HsVrijwilliger
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $inschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $uitschrijving;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $dragend = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rijbewijs;

    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=false, unique=true)
     */
    private $vrijwilliger;

    /**
     * @var ArrayCollection|HsKlus[]
     * @ORM\ManyToMany(targetEntity="HsKlus", mappedBy="hsVrijwilligers")
     */
    private $hsKlussen;

    /**
     * @var ArrayCollection|HsRegistratie[]
     * @ORM\OneToMany(targetEntity="HsRegistratie", mappedBy="hsVrijwilliger")
     */
    private $hsRegistraties;

    /**
     * @var ArrayCollection|HsMemo[]
     * @ORM\OneToMany(targetEntity="HsMemo", mappedBy="hsVrijwilliger")
     */
    private $hsMemos;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function __construct()
    {
        $this->hsKlussen = new ArrayCollection();
        $this->hsRegistraties = new ArrayCollection();
        $this->hsMemos = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->vrijwilliger;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDragend()
    {
        return $this->dragend;
    }

    public function setDragend($dragend)
    {
        $this->dragend = $dragend;

        return $this;
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

    public function getInschrijving()
    {
        return $this->inschrijving;
    }

    public function setInschrijving(\DateTime $inschrijving)
    {
        $this->inschrijving = $inschrijving;

        return $this;
    }

    public function getHsKlussen()
    {
        return $this->hsKlussen;
    }

    public function getRegistraties(HsKlus $hsKlus)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('hsKlus', $hsKlus))
            ->orderBy([
                'datum' => Criteria::ASC,
                'start' => Criteria::ASC,
                'eind' => Criteria::ASC,
            ])
        ;

        return $this->hsRegistraties->matching($criteria);
    }

    public function getMemos()
    {
        return $this->hsMemos;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = $this->modified = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modified = new \DateTime();
    }

    public function isDeletable()
    {
        return count($this->hsKlussen) === 0
        && count($this->hsRegistraties) === 0;
    }

    public function getRijbewijs()
    {
        return $this->rijbewijs;
    }

    public function setRijbewijs($rijbewijs)
    {
        $this->rijbewijs = $rijbewijs;

        return $this;
    }
}

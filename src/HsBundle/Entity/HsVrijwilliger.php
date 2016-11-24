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
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $dragend = false;

    /**
     * @var Vrijwilliger
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=false, unique=true)
     */
    private $vrijwilliger;

    /**
     * @var HsKlus[]
     * @ORM\ManyToMany(targetEntity="HsKlus", mappedBy="hsVrijwilligers")
     */
    private $hsKlussen;

    /**
     * @var HsRegistratie
     * @ORM\OneToMany(targetEntity="HsRegistratie", mappedBy="hsVrijwilliger")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hsRegistraties;

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
        $this->hsRegistraties = new ArrayCollection();
        $this->hsKlussen = new ArrayCollection();
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
}

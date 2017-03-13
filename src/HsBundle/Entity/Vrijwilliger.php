<?php

namespace HsBundle\Entity;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
 */
class Vrijwilliger implements MemoSubjectInterface, DocumentSubjectInterface
{
    use MemoSubjectTrait, DocumentSubjectTrait;

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
     * @var ArrayCollection|Klus[]
     * @ORM\ManyToMany(targetEntity="Klus", mappedBy="vrijwilligers")
     */
    private $klussen;

    /**
     * @var ArrayCollection|Registratie[]
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="vrijwilliger")
     */
    private $registraties;

//     /**
//      * @var ArrayCollection|Memo[]
//      *
//      * @ORM\ManyToMany(targetEntity="Memo", cascade={"persist", "remove"})
//      * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
//      */
//     protected $memos;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function __construct(AppVrijwilliger $vrijwilliger = null)
    {
        if ($vrijwilliger) {
            $this->vrijwilliger = $vrijwilliger;
        }
        $this->klussen = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->memos = new ArrayCollection();
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

    public function setVrijwilliger(AppVrijwilliger $vrijwilliger)
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

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function getRegistraties(Klus $klus)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('klus', $klus))
            ->orderBy([
                'datum' => Criteria::ASC,
                'start' => Criteria::ASC,
                'eind' => Criteria::ASC,
            ])
        ;

        return $this->registraties->matching($criteria);
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
        return count($this->klussen) === 0
        && count($this->registraties) === 0;
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

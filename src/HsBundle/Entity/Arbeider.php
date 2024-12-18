<?php

namespace HsBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table("hs_arbeiders")
 *
 * @ORM\InheritanceType("JOINED")
 *
 * @ORM\DiscriminatorMap({"dienstverlener" = "Dienstverlener", "vrijwilliger" = "Vrijwilliger"})
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
abstract class Arbeider
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    protected $actief = true;

    /**
     * @ORM\Column(type="date")
     *
     * @Gedmo\Versioned
     */
    protected $inschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $uitschrijving;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $rijbewijs;

    protected $klussen;
    protected $documenten;
    protected $memos;

    /**
     * @var ArrayCollection|Registratie[]
     *
     * @ORM\OneToMany(targetEntity="Registratie", mappedBy="arbeider")
     *
     * @ORM\OrderBy({"datum": "desc", "start": "desc", "eind": "desc"})
     */
    protected $registraties;

    public function __construct()
    {
        $this->klussen = new ArrayCollection();
        $this->registraties = new ArrayCollection();
        $this->memos = new ArrayCollection();
        $this->documenten = new ArrayCollection();
        $this->inschrijving = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
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

    public function getUitschrijving()
    {
        return $this->uitschrijving;
    }

    /**
     * @return Arbeider
     */
    public function setUitschrijving($uitschrijving)
    {
        $this->uitschrijving = $uitschrijving;

        return $this;
    }

    public function getKlussen()
    {
        return $this->klussen;
    }

    public function getRegistraties(?Klus $klus = null)
    {
        $criteria = Criteria::create()->orderBy([
            'datum' => Criteria::ASC,
            'start' => Criteria::ASC,
            'eind' => Criteria::ASC,
        ]);

        if ($klus) {
            $criteria->where(Criteria::expr()->eq('klus', $klus));
        }

        return $this->registraties->matching($criteria);
    }

    public function addRegistratie(Registratie $registratie)
    {
        $this->registraties[] = $registratie;
        $registratie->setArbeider($this);

        return $this;
    }

    public function isDeletable()
    {
        return 0 === (is_array($this->klussen) || $this->klussen instanceof \Countable ? count($this->klussen) : 0)
            && 0 === count($this->registraties)
            && 0 === (is_array($this->memos) || $this->memos instanceof \Countable ? count($this->memos) : 0)
            && 0 === (is_array($this->documenten) || $this->documenten instanceof \Countable ? count($this->documenten) : 0)
        ;
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

    public function isActief()
    {
        return $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = (bool) $actief;

        return $this;
    }

    abstract public function __toString(): string;
}

<?php

namespace OekBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="oek_groepen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Groep
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(name="aantal_bijeenkomsten", type="integer")
     *
     * @Gedmo\Versioned
     *
     * @Assert\GreaterThan(0)
     */
    private $aantalBijeenkomsten;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Lidmaatschap", mappedBy="groep")
     */
    private $lidmaatschappen;

    /**
     * @var ArrayCollection|Training[]
     *
     * @ORM\OneToMany(targetEntity="Training", mappedBy="groep")
     */
    private $trainingen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct()
    {
        $this->lidmaatschappen = new ArrayCollection();
        $this->trainingen = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->naam;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getAantalBijeenkomsten()
    {
        return $this->aantalBijeenkomsten;
    }

    public function setAantalBijeenkomsten($aantalBijeenkomsten)
    {
        $this->aantalBijeenkomsten = $aantalBijeenkomsten;

        return $this;
    }

    public function getDeelnemers()
    {
        $deelnemers = new ArrayCollection();
        foreach ($this->lidmaatschappen as $lidmaatschap) {
            $deelnemers[] = $lidmaatschap->getDeelnemer();
        }

        return $deelnemers;
    }

    public function getTrainingen()
    {
        return $this->trainingen;
    }

    public function getTrainingenToekomstig()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gte('einddatum', new \DateTime()))
            ->orWhere(Criteria::expr()->gte('startdatum', new \DateTime()))
        ;
        return $this->trainingen->matching($criteria);
    }

    public function addTraining(Training $training)
    {
        $this->trainingen[] = $training;
        $training->setGroep($this);

        return $this;
    }

    public function removeTraining(Training $training)
    {
        if ($this->trainingen->contains($training)) {
            $training->setGroep(null);
            $this->trainingen->removeElement($training);
        }

        return $this;
    }

    public function isDeletable()
    {
        return 0 == $this->lidmaatschappen->count()
               && 0 == $this->trainingen->count();
    }
}

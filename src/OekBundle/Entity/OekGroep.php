<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_groepen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class OekGroep
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @ORM\Column(name="aantal_bijeenkomsten", type="integer")
     * @Gedmo\Versioned
     * @Assert\GreaterThan(0)
     */
    private $aantalBijeenkomsten;

    /**
     * @var ArrayCollection|OekDeelname[]
     * @ORM\OneToMany(targetEntity="OekLidmaatschap", mappedBy="oekGroep")
     */
    private $oekLidmaatschappen;

    /**
     * @var ArrayCollection|OekTraining[]
     * @ORM\OneToMany(targetEntity="OekTraining", mappedBy="oekGroep")
     */
    private $oekTrainingen;

    public function __construct()
    {
        $this->oekLidmaatschappen = new ArrayCollection();
        $this->oekTrainingen = new ArrayCollection();
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

    public function getOekKlanten()
    {
        $oekKlanten = new ArrayCollection();
        foreach ($this->oekLidmaatschappen as $oekLidmaatschap) {
            $oekKlanten[] = $oekLidmaatschap->getOekKlant();
        }

        return $oekKlanten;
    }

    public function getOekTrainingen()
    {
        return $this->oekTrainingen;
    }

    public function getOekTrainingenToekomstig()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->gte('einddatum', new \DateTime()));

        return $this->oekTrainingen->matching($criteria);
    }

    public function addOekTraining(OekTraining $oekTraining)
    {
        $this->oekTrainingen->add($oekTraining);

        return $this;
    }

    public function removeOekTraining(OekTraining $oekTraining)
    {
        if ($this->oekKlanten->contains($oekTraining)) {
            $oekTraining->removeOekGroep($this);
            $this->oekTrainingen->removeElement($oekTraining);
        }

        return $this;
    }

    public function isDeletable()
    {
        return 0 == $this->oekLidmaatschappen->count() &&
               0 == $this->oekTrainingen->count();
    }
}

<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_groepen")
 * @ORM\HasLifecycleCallbacks
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
     */
    private $naam;

    /**
     * @var ArrayCollection|OekKlant[]
     * @ORM\ManyToMany(targetEntity="OekKlant", inversedBy="oekGroepen")
     */
    private $oekKlanten;

    /**
     * @var ArrayCollection|OekTraining[]
     * @ORM\OneToMany(targetEntity="OekTraining", mappedBy="oekGroep")
     */
    private $oekTrainingen;

    public function __construct()
    {
        $this->oekKlanten = new ArrayCollection();
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

    public function getOekKlanten()
    {
        return $this->oekKlanten;
    }

    public function setOekKlanten($oekKlanten = [])
    {
        $this->oekKlanten = $oekKlanten;

        return $this;
    }

    public function addOekKlant(OekKlant $oekKlant)
    {
        $oekKlant->addOekGroep($this);
        $this->oekKlanten->add($oekKlant);

        return $this;
    }

    public function removeOekKlant(OekKlant $oekKlant)
    {
        if ($this->oekKlanten->contains($oekKlant)) {
            $oekKlant->removeOekGroep($this);
            $this->oekKlanten->removeElement($oekKlant);
        }

        return $this;
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
        return $this->oekKlanten->count() == 0 &&
               $this->oekTrainingen->count() == 0;
    }
}

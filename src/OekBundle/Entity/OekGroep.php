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
        return $this->oekLidmaatschappen->count() == 0 &&
               $this->oekTrainingen->count() == 0;
    }
}

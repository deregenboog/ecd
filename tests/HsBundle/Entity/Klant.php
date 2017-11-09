<?php

namespace Tests\HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Klant as AppKlant;
use Doctrine\Common\Collections\Criteria;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\NameTrait;
use AppBundle\Entity\AddressTrait;
use AppBundle\Model\RequiredMedewerkerTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_klanten")
 * @ORM\HasLifecycleCallbacks
 */
class Klant implements MemoSubjectInterface, DocumentSubjectInterface
{
    use NameTrait, AddressTrait, RequiredMedewerkerTrait, MemoSubjectTrait, DocumentSubjectTrait, TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $werkgebied;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $inschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $uitschrijving;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $laatsteContact;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $actief = true;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $onHold = false;

    /**
     * @var ArrayCollection|Klus[]
     * @ORM\OneToMany(targetEntity="Klus", mappedBy="klant")
     */
    private $klussen;

    public function __toString()
    {
        return (string) $this->klant;
    }

    public function isDeletable()
    {
        return count($this->klussen) === 0;
    }

    public function isActief()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('einddatum'))
            ->orWhere(Criteria::expr()->gte('einddatum', new \DateTime('today')));

        $actief = count($this->klussen->matching($criteria)) > 0;
        $this->setActief($actief);

        return $actief;
    }

    public function getGefactureerd()
    {
        $bedrag = 0.0;
        foreach ($this->klussen as $klus) {
            $bedrag += $klus->getGefactureerd();
        }

        return $bedrag;
    }

    public function getBetaald()
    {
        $bedrag = 0.0;
        foreach ($this->klussen as $klus) {
            $bedrag += $klus->getBetaald();
        }

        return $bedrag;
    }

    public function getOpenstaand()
    {
        return $this->getGefactureerd() - $this->getBetaald();
    }

    public function testgetFacturen()
    {
        $facturen = new ArrayCollection();
        foreach ($this->klussen as $klus) {
            foreach ($klus->getFacturen() as $factuur) {
                $facturen->add($factuur);
            }
        }

        return $facturen;
    }
}

<?php

namespace HsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ORM\Table(name="hs_klussen")
 */
class HsKlus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datum;

    /**
     * @var HsKlant
     * @ORM\ManyToOne(targetEntity="HsKlant", inversedBy="hsKlussen")
     */
    private $hsKlant;

    /**
     * @var HsActiviteit
     * @ORM\ManyToOne(targetEntity="HsActiviteit", inversedBy="hsKlussen")
     */
    private $hsActiviteit;

    private $hsVrijwilliger;

    /**
     * @var ArrayCollection|HsVrijwilliger[]
     * @ORM\ManyToMany(targetEntity="HsVrijwilliger", inversedBy="hsKlussen")
     */
    private $hsVrijwilligers;

    /**
     * @var ArrayCollection|HsRegistratie[]
     * @ORM\OneToMany(targetEntity="HsRegistratie", mappedBy="hsKlus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hsRegistraties;

    /**
     * @var ArrayCollection|HsFactuur[]
     * @ORM\OneToMany(targetEntity="HsFactuur", mappedBy="hsKlus")
     */
    private $hsFacturen;

    /**
     * @var Medewerker
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medewerker;

    public function __construct(HsKlant $hsKlant)
    {
        $this->hsKlant = $hsKlant;
        $this->hsVrijwilligers = new ArrayCollection();
        $this->hsRegistraties = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s bij %s op %s',
            $this->hsActiviteit->getNaam(),
            (string) $this->hsKlant,
            $this->datum->format('d-m-Y')
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDatum()
    {
        return $this->datum;
    }

    public function setDatum(\DateTime $datum)
    {
        $this->datum = $datum;

        return $this;
    }

    public function getHsKlant()
    {
        return $this->hsKlant;
    }

    public function setHsKlant(HsKlant $hsKlant)
    {
        $this->hsKlant = $hsKlant;

        return $this;
    }

    public function getHsActiviteit()
    {
        return $this->hsActiviteit;
    }

    public function setHsActiviteit(HsActiviteit $hsActiviteit)
    {
        $this->hsActiviteit = $hsActiviteit;

        return $this;
    }

    public function getHsVrijwilligers()
    {
        return $this->hsVrijwilligers;
    }

    public function getHsVrijwilliger()
    {
        return null;
    }

    public function setHsVrijwilliger(HsVrijwilliger $hsVrijwilliger)
    {
        return $this->addHsVrijwilliger($hsVrijwilliger);
    }

    public function addHsVrijwilliger($hsVrijwilliger)
    {
        $this->hsVrijwilligers->add($hsVrijwilliger);
        $hsVrijwilliger->getHsKlussen()->add($this);

        return $this;
    }

    public function removeHsVrijwilliger($hsVrijwilliger)
    {
        $this->hsVrijwilligers->removeElement($hsVrijwilliger);
        $hsVrijwilliger->getHsKlussen()->removeElement($this);

        return $this;
    }

    public function getHsRegistraties()
    {
        return $this->hsRegistraties;
    }

    public function getHsFacturen()
    {
        return $this->hsFacturen;
    }

    public function getMedewerker()
    {
        return $this->medewerker;
    }

    public function setMedewerker(Medewerker $medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }
}

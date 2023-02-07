<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mw_projecten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Project
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    protected $wachtlijst = true;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Medewerker")
     */
    protected $medewerkers;

    public function __construct()
    {
        $this->medewerkers = new ArrayCollection();
    }

    public function getMedewerkers()
    {
        return $this->medewerkers;
    }

    public function addMedewerker(Medewerker $medewerker)
    {
        $this->medewerkers->add($medewerker);

        return $this;
    }

    public function removeMedewerker(Medewerker $medewerker)
    {
        $this->medewerkers->removeElement($medewerker);

        return $this;
    }

    public function isWachtlijst()
    {
        return (bool) $this->wachtlijst;
    }

    public function setWachtlijst(bool $wachtlijst)
    {
        $this->wachtlijst = $wachtlijst;

        return $this;
    }
}

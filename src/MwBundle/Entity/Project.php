<?php

namespace MwBundle\Entity;

use AppBundle\Entity\Medewerker;
use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
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
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

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

    /**
     * @param $medewerkers
     */
    public function setMedewerkers($medewerkers): void
    {
        $this->medewerkers = $medewerkers;
    }


}

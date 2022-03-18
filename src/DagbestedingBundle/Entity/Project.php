<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_projecten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Project
{
    use IdentifiableTrait, NameableTrait, ActivatableTrait;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="deelnemer", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $deelnames;

    /**
     * @return Deelname[]|ArrayCollection
     */
    public function getDeelnames()
    {
        return $this->deelnames;
    }

    /**
     * @param Deelname[]|ArrayCollection $deelnames
     * @return Project
     */
    public function setDeelnames($deelnames)
    {
        $this->deelnames = $deelnames;
        return $this;
    }


    public function isDeletable()
    {
        return false;
    }


}

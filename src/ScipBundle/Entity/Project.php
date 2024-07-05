<?php

namespace ScipBundle\Entity;

use AppBundle\Model\ActivatableTrait;
use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="scip_projecten",
 *     indexes={}
 * )
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Project
{
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="project", cascade={"persist"})
     */
    private $deelnames;

    /**
     * @var ArrayCollection|Toegangsrecht[]
     *
     * @ORM\ManyToMany(targetEntity="Toegangsrecht", mappedBy="projecten")
     */
    private $toegangsrechten;

    /**
     * @var int
     *
     * @ORM\Column(nullable=true)
     */
    private $kpl;

    public function __construct()
    {
        $this->deelnames = new ArrayCollection();
        $this->toegangsrechten = new ArrayCollection();
    }

    /**
     * @return Collection|Toegangsrecht[]
     */
    public function getToegangsrechten()
    {
        return $this->toegangsrechten;
    }

    /**
     * @return Collection|Deelname[]
     */
    public function getDeelnames()
    {
        return $this->deelnames;
    }

    public function addDeelname(Deelname $deelname)
    {
        $this->deelnames[] = $deelname;

        return $this;
    }

    public function getDeelnemers()
    {
        foreach ($this->deelnames as $deelname) {
            yield $deelname->getDeelnemer();
        }
    }

    /**
     * @return number
     */
    public function getKpl()
    {
        return $this->kpl;
    }

    /**
     * @param number $kpl
     */
    public function setKpl($kpl)
    {
        $this->kpl = $kpl;

        return $this;
    }
}

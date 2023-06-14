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
    use IdentifiableTrait;
    use NameableTrait;
    use ActivatableTrait;

    /**
     * @var bool
     * @ORM\Column(name="`active`", type="boolean", options={"default":1})
     * @Gedmo\Versioned
     */
    protected $actief = true;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kpl;

    /**
     * @var ArrayCollection|Deelname[]
     *
     * @ORM\OneToMany(targetEntity="Deelname", mappedBy="project", cascade={"persist"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $deelnames;

    /**
     * @var ArrayCollection|Traject[]
     *
     * @ORM\ManyToMany(targetEntity="Traject", mappedBy="projecten", cascade={"persist"})
     * @ORM\JoinTable(name="dagbesteding_traject_project")
     */
    private $trajecten;

    /**
     * @var Locatie
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Locatie", inversedBy="projecten")
     * @Gedmo\Versioned
     */
    protected $locatie;

    public function __toString()
    {
        return $this->getNaam();
    }


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

    /**
     * @param Deelname[]|ArrayCollection $deelnames
     * @return Project
     */
    public function addDeelname($deelname)
    {
        $this->deelnames[] = $deelname;
        return $this;
    }


    public function isDeletable()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getKpl()
    {
        return $this->kpl;
    }

    /**
     * @param mixed $kpl
     * @return Project
     */
    public function setKpl($kpl)
    {
        $this->kpl = $kpl;
        return $this;
    }

    /**
     * @return Locatie
     */
    public function getLocatie(): ?Locatie
    {
        return $this->locatie;
    }

    /**
     * @param Locatie $locatie
     * @return Project
     */
    public function setLocatie(Locatie $locatie): Project
    {
        $this->locatie = $locatie;
        return $this;
    }
}

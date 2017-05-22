<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="dagbesteding_resultaatgebieden")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Resultaatgebied
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    const TYPES = [
        'nvt' => 'n.v.t.',
        '2' => '2 Meedoen',
        '3' => '3 Meewerken',
        '4' => '4 Arbeidsmatige activering',
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Traject
     * @ORM\ManyToOne(targetEntity="Traject", inversedBy="resultaatgebieden")
     * @Gedmo\Versioned
     */
    private $traject;

    /**
     * @ORM\Column
     * @Gedmo\Versioned
     */
    private $type = self::TYPES['nvt'];

    /**
     * @ORM\Column(type="date")
     * @Gedmo\Versioned
     */
    private $startdatum;

    public function __construct()
    {
        $this->setStartdatum(new \DateTime());
    }

    public function __toString()
    {
        return 'Resultaatgebied '.$this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTraject()
    {
        return $this->traject;
    }

    public function setTraject(Traject $traject)
    {
        $this->traject = $traject;

        return $this;
    }

    public function getStartdatum()
    {
        return $this->startdatum;
    }

    public function setStartdatum(\DateTime $startdatum = null)
    {
        $this->startdatum = $startdatum;

        return $this;
    }

    public function isDeletable()
    {
        return false;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}

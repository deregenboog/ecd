<?php

namespace OdpBundle\Entity;

use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="odp_superverslagen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string",length=12)
 * @ORM\DiscriminatorMap({"superverslag" = "SuperVerslag", "verslag" = "Verslag", "financieel" = "FinancieelVerslag"})
 * @Gedmo\Loggable()
 */
abstract class SuperVerslag
{
    use TimestampableTrait, RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $datum;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $opmerking;

    public function __construct()
    {
        $this->datum = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOpmerking()
    {
        return $this->opmerking;
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = $opmerking;

        return $this;
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
}

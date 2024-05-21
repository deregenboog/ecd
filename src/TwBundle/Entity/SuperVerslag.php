<?php

namespace TwBundle\Entity;

use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tw_superverslagen")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="class", type="string",length=12)
 * @ORM\DiscriminatorMap({"superverslag" = "SuperVerslag", "verslag" = "Verslag", "financieel" = "FinancieelVerslag"})
 * @Gedmo\Loggable()
 */
abstract class SuperVerslag
{
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;


    /**
     * @var ArrayCollection | Klant[]
     * @ORM\ManyToMany(targetEntity="TwBundle\Entity\Klant", mappedBy="verslagen")
     */
    private $klant;

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

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $delenMw = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct(?Klant $klant = null)
    {
        $this->klant = new ArrayCollection();
        if(null !== $klant)
        {
            $this->klant->add($klant);
        }
        $this->datum = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOpmerking()
    {
        return utf8_decode($this->opmerking);
    }

    public function setOpmerking($opmerking)
    {
        $this->opmerking = utf8_encode($opmerking);

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

    public function isDelenMw(): bool
    {
        return $this->delenMw;
    }

    public function setDelenMw(bool $delenMw): void
    {
        $this->delenMw = $delenMw;
    }

    public function getKlant()
    {
        return $this->klant->first();
    }

}

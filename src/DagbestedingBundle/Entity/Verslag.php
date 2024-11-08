<?php

namespace DagbestedingBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\RequiredMedewerkerTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="dagbesteding_verslagen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 *
 * @ORM\DiscriminatorColumn(name="discr", type="string", length=15)
 *
 * @ORM\DiscriminatorMap({
 *     "verslag" = "Verslag",
 *     "intake" = "Intakeverslag",
 *     "evaluatie" = "Evaluatieverslag"
 * })
 */
class Verslag
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use RequiredMedewerkerTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $datum;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $opmerking;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="DagbestedingBundle\Entity\Deelnemer",inversedBy="verslagen")
     */
    protected $deelnemer;

    /**
     * @var int
     *
     * @ORM\Column()
     *
     * @deprecated Can be removed after succesful migration of data // 20230712 JTB
     */
    protected $traject_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    public function __construct()
    {
        $this->datum = new \DateTime();
    }

    public function getOpmerking(): string
    {
        if(is_null($this->opmerking)) return "";
        return mb_convert_encoding($this->opmerking ?? "", 'ISO-8859-1','UTF-8');
    }

    public function setOpmerking(?string $opmerking = "")
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');

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

    public function getDeelnemer(): Deelnemer
    {
        return $this->deelnemer;
    }

    public function setDeelnemer(Deelnemer $deelnemer): void
    {
        $this->deelnemer = $deelnemer;
    }
}

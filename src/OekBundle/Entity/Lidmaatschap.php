<?php

namespace OekBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_lidmaatschappen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Lidmaatschap
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var Groep
     *
     * @ORM\ManyToOne(targetEntity="Groep", inversedBy="lidmaatschappen", cascade={"persist"})
     * @ORM\JoinColumn(name="oekGroep_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $groep;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="Deelnemer", inversedBy="lidmaatschappen", cascade={"persist"})
     * @ORM\JoinColumn(name="oekKlant_id", nullable=false)
     * @Gedmo\Versioned
     */
    private $deelnemer;

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

    public function __construct(Groep $groep = null, Deelnemer $deelnemer = null)
    {
        $this->groep = $groep;
        $this->deelnemer = $deelnemer;
    }

    public function getGroep()
    {
        return $this->groep;
    }

    public function setGroep(Groep $groep)
    {
        $this->groep = $groep;

        return $this;
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }

    public function setDeelnemer(Deelnemer $deelnemer)
    {
        $this->deelnemer = $deelnemer;

        return $this;
    }
}

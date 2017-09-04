<?php

namespace OekBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oek_lidmaatschappen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class OekLidmaatschap
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var OekGroep
     *
     * @ORM\ManyToOne(targetEntity="OekGroep", inversedBy="oekLidmaatschappen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $oekGroep;

    /**
     * @var OekKlant
     *
     * @ORM\ManyToOne(targetEntity="OekKlant", inversedBy="oekLidmaatschappen")
     * @ORM\JoinColumn(nullable=false)
     * @Gedmo\Versioned
     */
    private $oekKlant;

    public function __construct(OekGroep $oekGroep = null, OekKlant $oekKlant = null)
    {
        $this->oekGroep = $oekGroep;
        $this->oekKlant = $oekKlant;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOekGroep()
    {
        return $this->oekGroep;
    }

    public function setOekGroep(OekGroep $oekGroep)
    {
        $this->oekGroep = $oekGroep;

        return $this;
    }

    public function getOekKlant()
    {
        return $this->oekKlant;
    }

    public function setOekKlant(OekKlant $oekKlant)
    {
        $this->oekKlant = $oekKlant;

        return $this;
    }
}

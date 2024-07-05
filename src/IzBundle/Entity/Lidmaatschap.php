<?php

namespace IzBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="iz_deelnemers_iz_intervisiegroepen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Lidmaatschap
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var Intervisiegroep
     *
     * @ORM\ManyToOne(targetEntity="Intervisiegroep", inversedBy="lidmaatschappen")
     *
     * @ORM\JoinColumn(name="iz_intervisiegroep_id", nullable=false)
     */
    private $intervisiegroep;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Versioned
     */
    protected $modified;

    /**
     * @var IzVrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="lidmaatschappen")
     *
     * @ORM\JoinColumn(name="iz_deelnemer_id", nullable=false)
     */
    private $vrijwilliger;

    public function __toString()
    {
        return sprintf('%s - %s', $this->intervisiegroep, $this->vrijwilliger);
    }

    /**
     * @return Intervisiegroep
     */
    public function getIntervisiegroep()
    {
        return $this->intervisiegroep;
    }

    /**
     * @param Intervisiegroep $intervisiegroep
     */
    public function setIntervisiegroep($intervisiegroep)
    {
        $this->intervisiegroep = $intervisiegroep;

        return $this;
    }

    /**
     * @return IzVrijwilliger
     */
    public function getVrijwilliger()
    {
        return $this->vrijwilliger;
    }

    /**
     * @param IzVrijwilliger $vrijwilliger
     */
    public function setVrijwilliger($vrijwilliger)
    {
        $this->vrijwilliger = $vrijwilliger;

        return $this;
    }
}

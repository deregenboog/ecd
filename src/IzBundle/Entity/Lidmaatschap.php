<?php

namespace IzBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_deelnemers_iz_intervisiegroepen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Lidmaatschap
{
    use IdentifiableTrait, TimestampableTrait;

    /**
     * @var Intervisiegroep
     *
     * @ORM\ManyToOne(targetEntity="Intervisiegroep", inversedBy="lidmaatschappen")
     * @ORM\JoinColumn(name="iz_intervisiegroep_id")
     */
    private $intervisiegroep;

    /**
     * @var IzVrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="lidmaatschappen")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
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

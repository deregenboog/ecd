<?php

namespace IzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\IdentifiableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="iz_deelnemers_iz_intervisiegroepen")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Lidmaatschap
{
    use IdentifiableTrait;

    /**
     * @var Intervisiegroep
     *
     * @ORM\ManyToOne(targetEntity="Intervisiegroep", inversedBy="lidmaatschap")
     * @ORM\JoinColumn(name="iz_intervisiegroep_id")
     */
    private $intervisiegroep;

    /**
     * @var IzVrijwilliger
     *
     * @ORM\ManyToOne(targetEntity="IzVrijwilliger", inversedBy="lidmaatschap")
     * @ORM\JoinColumn(name="iz_deelnemer_id")
     */
    private $vrijwilliger;

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

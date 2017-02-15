<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_vrijwilligers")
 * @ORM\HasLifecycleCallbacks
*/
class GaVrijwilligerDeelname
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vrijwilliger")
     * @ORM\JoinColumn(nullable=true)
     */
    private $vrijwilliger;

    /**
     * @ORM\ManyToOne(targetEntity="GaActiviteit", inversedBy="gaVrijwilligerDeelnames")
     * @ORM\JoinColumn(name="groepsactiviteit_id", nullable=true)
     */
    private $gaActiviteit;

    /**
     * @ORM\Column(name="afmeld_status", length=50, nullable=true)
     */
    private $status;

    public function getId()
    {
        return $this->id;
    }
}

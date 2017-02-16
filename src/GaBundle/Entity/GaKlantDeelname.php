<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_klanten")
 * @ORM\HasLifecycleCallbacks
 */
class GaKlantDeelname
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="GaActiviteit", inversedBy="gaKlantDeelnames")
     * @ORM\JoinColumn(name="groepsactiviteit_id")
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

<?php

namespace GaBundle\Entity;

use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_klanten")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
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
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @ORM\ManyToOne(targetEntity="GaActiviteit", inversedBy="gaKlantDeelnames")
     * @ORM\JoinColumn(name="groepsactiviteit_id")
     * @Gedmo\Versioned
     */
    private $gaActiviteit;

    /**
     * @ORM\Column(name="afmeld_status", length=50, nullable=true)
     * @Gedmo\Versioned
     */
    private $status;

    public function getId()
    {
        return $this->id;
    }
}

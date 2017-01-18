<?php

namespace GaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groepsactiviteiten_vrijwilligers")
 */
class GaVrijwilligerDeelname
{
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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }
}

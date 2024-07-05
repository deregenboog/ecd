<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BotKoppelingen.
 *
 * @ORM\Table(name="bot_koppelingen")
 *
 * @ORM\Entity
 */
class BotKoppeling
{
    /**
     * @var int
     *
     * @ORM\Column(name="medewerker_id", type="integer", nullable=true)
     */
    private $medewerkerId;

    /**
     * @var int
     *
     * @ORM\Column(name="back_on_track_id", type="integer", nullable=true)
     */
    private $backOnTrackId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdatum", type="date", nullable=true)
     */
    private $startdatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="einddatum", type="date", nullable=true)
     */
    private $einddatum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     */
    private $id;
}

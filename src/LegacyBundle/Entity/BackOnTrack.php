<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BackOnTracks.
 *
 * @ORM\Table(name="back_on_tracks", indexes={@ORM\Index(name="idx_back_on_tracks_dates", columns={"startdatum", "einddatum"})})
 * @ORM\Entity
 */
class BackOnTrack
{
    /**
     * @var int
     *
     * @ORM\Column(name="klant_id", type="integer")
     */
    private $klantId;

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
     * @var \DateTime
     *
     * @ORM\Column(name="intakedatum", type="date", nullable=true)
     */
    private $intakedatum;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
}

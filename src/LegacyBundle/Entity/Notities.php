<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notities.
 *
 * @ORM\Table(name="notities", indexes={@ORM\Index(name="idx_notities_klant_id", columns={"klant_id"})})
 * @ORM\Entity
 */
class Notities
{
    /**
     * @var int
     *
     * @ORM\Column(name="klant_id", type="integer")
     */
    private $klantId;

    /**
     * @var int
     *
     * @ORM\Column(name="medewerker_id", type="integer")
     */
    private $medewerkerId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum", type="datetime")
     */
    private $datum;

    /**
     * @var string
     *
     * @ORM\Column(name="opmerking", type="text", length=65535)
     */
    private $opmerking;

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
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
}

<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contactjournals.
 *
 * @ORM\Table(name="contactjournals", indexes={@ORM\Index(name="idx_contactjournals_klant_id", columns={"klant_id"})})
 * @ORM\Entity
 */
class Contactjournals
{
    /**
     * @var int
     *
     * @ORM\Column(name="klant_id", type="integer", nullable=false)
     */
    private $klantId;

    /**
     * @var int
     *
     * @ORM\Column(name="medewerker_id", type="integer", nullable=false)
     */
    private $medewerkerId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum", type="date", nullable=false)
     */
    private $datum;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_tb", type="boolean", nullable=false)
     */
    private $isTb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
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

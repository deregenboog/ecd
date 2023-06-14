<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BotVerslagen.
 *
 * @ORM\Table(name="bot_verslagen", indexes={@ORM\Index(name="idx_bto_verslagen_klant_id", columns={"klant_id"})})
 * @ORM\Entity
 */
class BotVerslag
{
    /**
     * @var string
     *
     * @ORM\Column(name="contact_type", type="string", length=50, nullable=true)
     */
    private $contactType;

    /**
     * @var string
     *
     * @ORM\Column(name="verslag", type="text", length=65535, nullable=true)
     */
    private $verslag;

    /**
     * @var int
     *
     * @ORM\Column(name="medewerker_id", type="integer", nullable=true)
     */
    private $medewerkerId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    private $modified;

    /**
     * @var int
     *
     * @ORM\Column(name="klant_id", type="integer")
     */
    private $klantId;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
}

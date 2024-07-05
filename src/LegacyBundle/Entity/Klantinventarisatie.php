<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="klantinventarisaties")
 *
 * @ORM\Entity
 */
class Klantinventarisatie
{
    /**
     * @var int
     *
     * @ORM\Column(name="klant_id", type="integer")
     */
    private $klantId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="inventarisatie_id", type="integer")
     */
    private $inventarisatieId = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="doorverwijzer_id", type="integer")
     */
    private $doorverwijzerId = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datum", type="date")
     */
    private $datum;

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

<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bedrijfitems.
 *
 * @ORM\Table(name="bedrijfitems")
 * @ORM\Entity
 */
class Bedrijfitem
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="bedrijfsector_id", type="integer")
     */
    private $bedrijfsectorId;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
}

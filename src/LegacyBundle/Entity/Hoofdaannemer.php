<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\IdentifiableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="hoofdaannemers",
 *     indexes={}
 * )
 */
class Hoofdaannemer
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $naam;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $modified;
}

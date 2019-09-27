<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="logs_2011",
 *     indexes={
 *         @ORM\Index(columns={"model", "foreign_key", "created"}),
 *         @ORM\Index(columns={"model", "foreign_key"}),
 *         @ORM\Index(columns={"medewerker_id"})
 *     }
 * )
 */
class Log2011
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(length=50, nullable=true)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(length=36, nullable=true)
     */
    private $foreign_key;

    /**
     * @ORM\Column(length=36, nullable=true)
     */
    private $medewerker_id;

    /**
     * @var string
     *
     * @ORM\Column(length=15, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $change;
}

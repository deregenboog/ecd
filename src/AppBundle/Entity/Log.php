<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="logs")
 * @ORM\HasLifecycleCallbacks
 */
class Log
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
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(length=50)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(length=36)
     */
    private $foreign_key;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="Medewerker")
     */
    private $medewerker;

    /**
     * @var string
     *
     * @ORM\Column(length=15)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $change;
}

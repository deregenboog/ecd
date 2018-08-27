<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="logs",
 *     indexes={
 *         @ORM\Index(name="idx_logs_model_foreign_key_created", columns={"model", "foreign_key", "created"}),
 *         @ORM\Index(name="idx_logs_model_foreign_key", columns={"model", "foreign_key"}),
 *         @ORM\Index(name="idx_logs_medewerker_id", columns={"medewerker_id"})
 *     }
 * )
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

<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QueueTasks.
 *
 * @ORM\Table(name="queue_tasks", indexes={@ORM\Index(name="idx_queue_tasks_status_modified", columns={"modified", "status"})})
 * @ORM\Entity
 */
class QueueTask
{
    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=50, nullable=true)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="foreign_key", type="string", length=36)
     */
    private $foreignKey;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=50, nullable=true)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", length=65535, nullable=true)
     */
    private $data;

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
     * @ORM\Column(name="run_after", type="datetime", nullable=true)
     */
    private $runAfter;

    /**
     * @var string
     *
     * @ORM\Column(name="batch", type="string", length=50, nullable=true)
     */
    private $batch;

    /**
     * @var string
     *
     * @ORM\Column(name="output", type="text", length=65535, nullable=true)
     */
    private $output;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="executed", type="datetime", nullable=true)
     */
    private $executed;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=36)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
}

<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trajecten.
 *
 * @ORM\Table(name="trajecten", uniqueConstraints={@ORM\UniqueConstraint(name="uq_klant_id", columns={"klant_id"})})
 * @ORM\Entity
 */
class Traject
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
     * @ORM\Column(name="trajectbegeleider_id", type="integer", nullable=false)
     */
    private $trajectbegeleiderId;

    /**
     * @var int
     *
     * @ORM\Column(name="werkbegeleider_id", type="integer", nullable=false)
     */
    private $werkbegeleiderId;

    /**
     * @var string
     *
     * @ORM\Column(name="klant_telefoonnummer", type="string", length=255, nullable=false)
     */
    private $klantTelefoonnummer;

    /**
     * @var string
     *
     * @ORM\Column(name="administratienummer", type="string", length=255, nullable=false)
     */
    private $administratienummer;

    /**
     * @var string
     *
     * @ORM\Column(name="klantmanager", type="string", length=255, nullable=false)
     */
    private $klantmanager;

    /**
     * @var string
     *
     * @ORM\Column(name="manager_telefoonnummer", type="string", length=255, nullable=false)
     */
    private $managerTelefoonnummer;

    /**
     * @var string
     *
     * @ORM\Column(name="manager_email", type="string", length=255, nullable=false)
     */
    private $managerEmail;

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

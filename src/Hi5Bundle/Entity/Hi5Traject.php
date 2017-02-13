<?php

namespace Hi5Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="trajecten")
 */
class Hi5Traject
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Klant")
     */
    private $klant;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $trajectbegeleider;

    /**
     * @var Medewerker
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medewerker")
     */
    private $werkbegeleider;

    /**
     * @ORM\Column(type="string", name="klant_telefoonnummer")
     */
    private $telefoonnummerKlant;

    /**
     * @ORM\Column(type="string")
     */
    private $administratienummer;

    /**
     * @ORM\Column(type="string", name="manager_telefoonnummer")
     */
    private $telefoonnummerManager;

    /**
     * @ORM\Column(type="string", name="manager_email")
     */
    private $emailManager;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    public function getId()
    {
        return $this->id;
    }
}

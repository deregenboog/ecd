<?php

namespace InloopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Entity\Klant;

/**
 * @ORM\Entity
 * @ORM\Table(name="registraties")
 * @Gedmo\Loggable
 */
class Registratie
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Klant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Klant")
     * @Gedmo\Versioned
     */
    private $klant;

    /**
     * @var Locatie
     *
     * @ORM\ManyToOne(targetEntity="Locatie")
     * @Gedmo\Versioned
     */
    private $locatie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $binnen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $buiten;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $douce;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $mw;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $kleding;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $maaltijd;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $activering;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $gbrv;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $closed;
}

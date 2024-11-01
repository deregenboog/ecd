<?php

namespace DagbestedingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="dagbesteding_contactpersonen")
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @Gedmo\Loggable
 */
class Contactpersoon
{
    public const SOORTEN = [
        'Klanthouder' => 'klanthouder',
        'Klantmanager gemeente' => 'klantmanager',
        'Overige contactpersonen' => 'overig',
    ];

    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Gedmo\Versioned
     */
    private $soort;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Gedmo\Versioned
     */
    private $naam;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $telefoon;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $opmerking;

    /**
     * @var Deelnemer
     *
     * @ORM\ManyToOne(targetEntity="Deelnemer", inversedBy="contactpersonen")
     *
     * @Gedmo\Versioned
     */
    private $deelnemer;

    public function getSoorten()
    {
        return array_flip(self::SOORTEN);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSoort()
    {
        return $this->soort;
    }

    public function setSoort($soort)
    {
        $this->soort = $soort;

        return $this;
    }

    public function getNaam()
    {
        return $this->naam;
    }

    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    public function getTelefoon()
    {
        return $this->telefoon;
    }

    public function setTelefoon($telefoon)
    {
        $this->telefoon = $telefoon;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getOpmerking()
    {
        return mb_convert_encoding($this->opmerking,'ISO-8859-1','UTF-8');
    }

    public function setOpmerking($opmerking = null)
    {
        $this->opmerking = mb_convert_encoding($opmerking, 'UTF-8', 'ISO-8859-1');
        return $this;
    }

    public function getDeelnemer()
    {
        return $this->deelnemer;
    }

    public function setDeelnemer(Deelnemer $deelnemer)
    {
        $this->deelnemer = $deelnemer;

        return $this;
    }
}

<?php

namespace AppBundle\Entity;

use AppBundle\Model\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="medewerkers")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Medewerker
{
    use NameTrait, TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="uidnumber")
     */
    private $uid;

    /**
     * @ORM\Column(nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $actief = true;

    /**
     * @ORM\Column(name="groups", type="json_array", nullable=true)
     */
    private $groepen = [];

    /**
     * @ORM\Column(name="eerste_bezoek", type="datetime", nullable=true)
     */
    private $eersteBezoek;

    /**
     * @ORM\Column(name="laatste_bezoek", type="datetime", nullable=true)
     */
    private $laatsteBezoek;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getNaam()
    {
        $parts = [];

        if ($this->voornaam) {
            $parts[] = $this->voornaam;
        }
        if ($this->tussenvoegsel) {
            $parts[] = $this->tussenvoegsel;
        }
        if ($this->achternaam) {
            $parts[] = $this->achternaam;
        }

        return implode(' ', $parts);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getGroepen()
    {
        return $this->groepen;
    }

    public function setGroepen(array $groepen = [])
    {
        $this->groepen = $groepen;

        return $this;
    }
}

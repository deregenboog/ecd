<?php

namespace AppBundle\Entity;

use AppBundle\Model\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="medewerkers")
 * @Gedmo\Loggable
 */
class Medewerker extends User implements UserInterface
{
    use NameTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $actief = true;

    /**
     * @ORM\Column(name="groups", type="json_array", nullable=true)
     */
    private $groepen = [];

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

    public function isActief()
    {
        return (bool) $this->actief;
    }

    public function setActief($actief)
    {
        $this->actief = $actief;

        return $this;
    }
}

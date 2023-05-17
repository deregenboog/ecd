<?php

namespace AppBundle\Entity;

use AppBundle\Model\IdentifiableTrait;
use AppBundle\Model\NameTrait;
use AppBundle\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Ldap\Security\LdapUser;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="medewerkers")
 * ORM\Entity(repositoryClass="AppBundle\Repository\MedewerkerRepository")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class Medewerker implements UserInterface, PasswordAuthenticatedUserInterface,EquatableInterface
{
    use IdentifiableTrait;
    use NameTrait;
    use TimestampableTrait;

    /**
     * @ORM\Column(name="uidnumber")
     */
    private $guid;

    /**
     * @ORM\Column(nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(nullable=true)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="`active`", type="integer", options={"default":1})
     */
    private $actief = true;

    /**
     * @ORM\Column(name="`groups`", type="json", nullable=true)
     * !! This column is not used / set by LDAP. What is it?!
     */
    private $groepen = [];

    /**
     * @ORM\Column(name="ldap_groups", type="json", nullable=true)
     */
    private $ldapGroups = [];

    /**
     * @ORM\Column(name="eerste_bezoek", type="datetime")
     */
    private $eersteBezoek;

    /**
     * @ORM\Column(name="laatste_bezoek", type="datetime")
     */
    private $laatsteBezoek;

    /**
     * @var array the Symfony roles for this user
     *
     * @ORM\Column(name="roles", type="json")
     */
    private $roles = [];

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the GUID used to uniquely identify the user in LDAP.
     *
     * @return string
     */
    public function getLdapGuid()
    {
        return $this->guid;
    }

    /**
     * Set the GUID used to uniquely identify the user in LDAP.
     *
     * @param string $guid
     */
    public function setLdapGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getUsername(): string
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

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /*
     * JTB: Ik denk dat dit dubbel is en het al in de Nametrait staat.
     * Ik laat het nog even staan (190523) op dit moment om te checken of het later evt problemen oplevert.
     * @TODO: nagaan of deze code exact gelijk is aan NameTrait.
     */
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

    public function getGroepen()
    {
        return $this->groepen;
    }

    public function setGroepen(array $groepen = [])
    {
        $this->groepen = $groepen;

        return $this;
    }

    public function getLdapGroups()
    {
        return $this->ldapGroups;
    }

    public function setLdapGroups(array $ldapGroups = [])
    {
        $this->ldapGroups = $ldapGroups;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActief()
    {
        return (bool) $this->actief;
    }

    /**
     * @param bool $actief
     */
    public function setActief($actief)
    {
        $this->actief = $actief;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEersteBezoek()
    {
        return $this->eersteBezoek;
    }

    /**
     * @param \DateTime $eersteBezoek
     */
    public function setEersteBezoek($eersteBezoek)
    {
        $this->eersteBezoek = $eersteBezoek;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLaatsteBezoek()
    {
        return $this->laatsteBezoek;
    }

    public function setLaatsteBezoek(\DateTime $laatsteBezoek)
    {
        $this->laatsteBezoek = $laatsteBezoek;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Sets the roles for the user.
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword(): ?string
    {
        return '';
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return $this;
    }

    public function supportsClass($class): bool
    {
        return LdapUser::class === $class;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUserIdentifier() !== $user->getUserIdentifier()) {
            return false;
        }

        return true;
    }

}

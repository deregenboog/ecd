<?php

namespace AppBundle\Event;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use LdapTools\Bundle\LdapToolsBundle\Event\LdapLoginEvent;
use LdapTools\Bundle\LdapToolsBundle\Event\AuthenticationHandlerEvent;
use LdapTools\Bundle\LdapToolsBundle\Security\User\LdapUserProvider;
use LdapTools\Exception\LdapConnectionException;
use MongoDB\Driver\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class LoginEventListener
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var LdapUserProvider
     */
    protected $ldapUserProvider;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, LdapUserProvider $ldapUserProvider = null)
    {
        $this->em = $em;
        $this->ldapUserProvider = $ldapUserProvider;
    }

    /**
     * Persists or updates the user in the database.
     *
     * @param LdapLoginEvent $event
     */
    public function onLoginSuccess(LdapLoginEvent $event)
    {
        /** @var Medewerker $medewerker */
        $medewerker = $event->getUser();


        if (!$medewerker->isActief())
        {
            throw new AuthenticationCredentialsNotFoundException(sprintf("Gebruiker %s is inactief in ECD en mag niet inloggen.",$medewerker->getUsername()));
        }
        if (!$medewerker->getId()) {
            $medewerker->setEersteBezoek(new \DateTime());
            $this->em->persist($medewerker);
        } else {

            $ldapMedewerker = $this->ldapUserProvider->loadUserByUsername($event->getToken()->getUsername());
            $medewerker
                ->setLdapGuid($ldapMedewerker->getLdapGuid())
                ->setLdapGroups($ldapMedewerker->getLdapGroups())
                ->setEmail($ldapMedewerker->getEmail())
                ->setActief($ldapMedewerker->isActief())
                ->setRoles($ldapMedewerker->getRoles())
                ->setVoornaam($ldapMedewerker->getVoornaam())
                ->setAchternaam($ldapMedewerker->getAchternaam())
            ;
        }

        $medewerker->setLaatsteBezoek(new \DateTime());
        $this->em->flush();
    }

    /**
     * Gives more details when authentication fails.
     *
     * @param AuthenticationHandlerEvent $event
     */
    public function onLoginFailure(AuthenticationHandlerEvent $event)
    {
        $exc = $event->getException();
        $prevExc = $exc->getPrevious();
        throw $prevExc;

        if($prevExc instanceof LdapConnectionException)
        {
            throw new \LogicException($prevExc->getMessage());
        }
    }
}

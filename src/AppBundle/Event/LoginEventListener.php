<?php

namespace AppBundle\Event;

use AppBundle\Entity\Medewerker;
use AppBundle\Exception\UserException;
use AppBundle\Security\LdapUserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Ldap\Security\LdapUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginEventListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var LdapUserProvider
     */
    protected $ldapUserProvider;

    public function __construct(EntityManagerInterface $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Persists or updates the user in the database.
     */
    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        /** @var Medewerker $ldapUser */
        $ldapUser = $event->getUser(); //user made by ldapUserProvider is only a mockup user. No check to database yet.

        if (!$ldapUser->isActief()) {
            throw new UserException(sprintf('Gebruiker %s is inactief in ECD en mag niet inloggen.', $ldapUser->getUserIdentifier()),403 );
        }

        $repository = $this->em->getRepository(Medewerker::class);
        $username = $ldapUser->getUserIdentifier();
        $medewerker = $repository->findOneByUsername($username);

        if ($medewerker == null || $medewerker->getUsername() == null) {
            $medewerker = new Medewerker();
            $medewerker->setEersteBezoek(new \DateTime());
            $medewerker->setUsername($ldapUser->getUserIdentifier());
        }

        //update all ldap fields with each logon.
        $medewerker
            ->setLdapGuid($ldapUser->getLdapGuid())
            ->setLdapGroups($ldapUser->getLdapGroups())
            ->setEmail($ldapUser->getEmail())
            ->setActief($ldapUser->isActief())
            ->setRoles($ldapUser->getRoles())
            ->setVoornaam($ldapUser->getVoornaam())
            ->setAchternaam($ldapUser->getAchternaam())
            ->setLaatsteBezoek(new \DateTime());
        ;

        $this->em->persist($medewerker);
        $this->em->flush();
        // Login user
        $token = new UsernamePasswordToken($medewerker, null, 'main', $medewerker->getRoles());
        $this->tokenStorage->setToken($token);

        return $medewerker;
    }

    /**
     * Gives more details when authentication fails.
     */
    public function onLoginFailure(AuthenticationHandlerEvent $event)
    {
        $exc = $event->getException();
        $prevExc = $exc->getPrevious();
//        throw $prevExc;
        if ($prevExc instanceof LdapConnectionException) {
            throw new \LogicException($prevExc->getMessage());
        }
        throw $exc;
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class];
    }
}

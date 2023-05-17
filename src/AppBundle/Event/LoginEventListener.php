<?php

namespace AppBundle\Event;

use AppBundle\Entity\Medewerker;
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

    public function __construct(EntityManagerInterface $em, LdapUserProvider $ldapUserProvider = null, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->ldapUserProvider = $ldapUserProvider;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Persists or updates the user in the database.
     */
    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        /** @var LdapUser $ldapUser */
        $ldapUser = $event->getUser(); //user made by ldapUserProvider is only a mockup user. No check to database yet.

        if (!$ldapUser->isActief()) {
            throw new AuthenticationCredentialsNotFoundException(sprintf('Gebruiker %s is inactief in ECD en mag niet inloggen.', $ldapUser->getUserIdentifier()));
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

        //        $medewerker->setLdapGuid($ldapUser->getExtraFields()["ldapGuid"]);
//        $medewerker->setVoornaam($ldapUser->getExtraFields()["voornaam"]);
//        $medewerker->setAchternaam($ldapUser->getExtraFields()["achternaam"]);
//        $medewerker->setActief($ldapUser->getExtraFields()["actief"]);
//        $medewerker->setEmail($ldapUser->getExtraFields()["email"]);
//        $medewerker->setRoles($ldapUser->getRoles());

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
        throw $prevExc;
        if ($prevExc instanceof LdapConnectionException) {
            throw new \LogicException($prevExc->getMessage());
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [];
      //  return ['ldap_tools_bundle.login.success' => 'onLoginSuccess', 'ldap_tools_bundle.guard.login.failure' => 'onLoginFailure'];
    }
}

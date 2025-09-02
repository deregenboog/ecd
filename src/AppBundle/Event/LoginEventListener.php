<?php

namespace AppBundle\Event;

use AppBundle\Entity\Medewerker;
use AppBundle\Exception\UserException;
use AppBundle\Security\LdapUserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginEventListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

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
        /** @var Medewerker $user */
        $user = $event->getUser();

        if (!$user->isActief()) {
            throw new UserException(403, sprintf('Gebruiker %s is inactief in ECD en mag niet inloggen.', $user->getUserIdentifier()));
        }

        // Detect authentication method via authenticator type
        $authenticator = $event->getAuthenticator();
        $isLdapAuth = $authenticator instanceof \Symfony\Component\Ldap\Security\LdapAuthenticator;
        
        if($isLdapAuth) { // extra logic for ldap
            $repository = $this->em->getRepository(Medewerker::class);
            $username = $user->getUserIdentifier();
            $medewerker = $repository->findOneByUsername($username);

            if (null == $medewerker || null == $medewerker->getUsername()) {
                $medewerker = new Medewerker();
                $medewerker->setEersteBezoek(new \DateTime());
                $medewerker->setUsername($user->getUserIdentifier());
            }

            // update all ldap fields with each logon.
            $medewerker
                ->setLdapGuid($user->getLdapGuid())
                ->setLdapGroups($user->getLdapGroups())
                ->setEmail($user->getEmail())
                ->setActief($user->isActief())
                ->setRoles($user->getRoles())
                ->setVoornaam($user->getVoornaam())
                ->setAchternaam($user->getAchternaam())
                ->setLaatsteBezoek(new \DateTime());

            // Login user
            $token = new UsernamePasswordToken($medewerker, 'main', $medewerker->getRoles());
            $this->tokenStorage->setToken($token);
        } else {
            // SAML: user is already from database, just use it
            $medewerker = $user;
        }
        
        //for ldap and saml, persistence is necessary.
        $this->em->persist($medewerker);
        $this->em->flush();

        return $medewerker;
    }

    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class];
    }
}

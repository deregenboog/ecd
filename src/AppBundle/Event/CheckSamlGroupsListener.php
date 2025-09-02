<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Event;

use Hslavich\OneloginSamlBundle\Security\Http\Authenticator\Passport\Badge\SamlAttributesBadge;
use Hslavich\OneloginSamlBundle\Security\User\SamlUserInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Ldap\Security\LdapBadge;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

/**
 * When the user is verified and gets its passportBadge its samlAttributes gets checked and parsed
 * for the right application ROLES.
 */
class CheckSamlGroupsListener implements EventSubscriberInterface
{


    private $samlRolesGroups;


    public function __construct($samlRolesGroups = [])
    {
       $this->samlRolesGroups = $samlRolesGroups;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {

        $passport = $event->getPassport();

        if (!$passport->hasBadge(SamlAttributesBadge::class)) {
            return;
        }

        /** @var SamlAttributesBadge $samlBadge */
        $samlBadge = $passport->getBadge(SamlAttributesBadge::class);
        if (!$samlBadge->isResolved()) {
            throw new \LogicException(sprintf('To check Saml Groups memberships/claims, SamlattributesBadge should be resolved. (means that the credentials are valid)'));
        }

        $user = $passport->getUser();

        $roles[] = 'ROLE_USER';

        foreach ($user->getSamlGroups() as $samlGroup) { // Iterate through each group entry line
            if (false !== $key = array_search($samlGroup, $this->samlRolesGroups)) { // Check if the group is in the mapping
                $roles[] = $key; // Map the group to the role the user will have
            }
        }
        if(sizeof($roles) === 1) {
            throw new BadCredentialsException("Geen geldige groepen gevonden.");
        }

        $user->setRoles($roles);
    }

    public static function getSubscribedEvents(): array
    {
        return [CheckPassportEvent::class => ['onCheckPassport', 256]]; //hogere prioriteit dan UserModifiedListener zodat de roles geset zijn voor hij wordt opgslagne.
    }
}

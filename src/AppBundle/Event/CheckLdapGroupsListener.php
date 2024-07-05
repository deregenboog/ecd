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
 * Verifies password credentials using an LDAP service whenever the
 * LdapBadge is attached to the Security passport.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class CheckLdapGroupsListener implements EventSubscriberInterface
{
    private $ldapLocator;

    private $ldapSearchUser;
    private $ldapSearchPassword;
    private $ldapBaseDn;

    private $rolesGroups;

    private $extraFields;

    public function __construct(ContainerInterface $ldapLocator, $ldapBaseDn, $ldapSearchUser, $ldapSearchPassword, $rolesGroups = [])
    {
        $this->ldapLocator = $ldapLocator;
        $this->ldapBaseDn = $ldapBaseDn;
        $this->ldapSearchUser = $ldapSearchUser;
        $this->ldapSearchPassword = $ldapSearchPassword;
        $this->extraFields = [
            'memberOf', // represents the groups where a user is member of in the AD.
        ];
        $this->rolesGroups = $rolesGroups;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        if (!$passport->hasBadge(LdapBadge::class)) {
            return;
        }

        /** @var LdapBadge $ldapBadge */
        $ldapBadge = $passport->getBadge(LdapBadge::class);
        if (!$ldapBadge->isResolved()) {
            throw new \LogicException(sprintf('To check LdapGroups, LdapBadge should be resolved. (means that the credentials are valid)'));
        }

        if (!$passport instanceof UserPassportInterface || !$passport->hasBadge(PasswordCredentials::class)) {
            throw new \LogicException(sprintf('LDAP authentication requires a passport containing a user and password credentials, authenticator "%s" does not fulfill these requirements.', \get_class($event->getAuthenticator())));
        }

        if (!$this->ldapLocator->has($ldapBadge->getLdapServiceId())) {
            throw new \LogicException(sprintf('Cannot check membership of groups using the "%s" ldap service, as such service is not found. Did you maybe forget to add the "ldap" service tag to this service?', $ldapBadge->getLdapServiceId()));
        }

        $user = $passport->getUser();
        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            trigger_deprecation('symfony/ldap', '5.3', 'Not implementing the "%s" interface in class "%s" while using password-based authenticators is deprecated.', PasswordAuthenticatedUserInterface::class, get_debug_type($user));
        }

        /** @var LdapInterface $ldap */
        $ldap = $this->ldapLocator->get($ldapBadge->getLdapServiceId());
        try {
            try {
                @$ldap->bind($this->ldapSearchUser, $this->ldapSearchPassword);
            } catch (\Exception $e) {
                throw new BadCredentialsException($e->getMessage());
            }

            $identifier = $ldap->escape($user->getUserIdentifier(), '', LdapInterface::ESCAPE_FILTER);
            $query = str_replace(['{username}', '{user_identifier}'], $identifier, '(sAMAccountName={username})');
            $search = $ldap->query($this->ldapBaseDn, $query, ['filter' => 0 == \count($this->extraFields) ? '*' : $this->extraFields]);

            $entries = $search->execute();
            $count = \count($entries);

            if (1 !== $count) {
                throw new BadCredentialsException('User returns more than one result.');
            }

            $entry = $entries[0];
            $roles[] = 'ROLE_USER';
            foreach ($entry->getAttribute('memberOf') as $groupLine) { // Iterate through each group entry line
                if (false !== $key = array_search($groupLine, $this->rolesGroups)) { // Check if the group is in the mapping
                    $roles[] = $key; // Map the group to the role the user will have
                }
            }
            $user->setRoles($roles);
            $user->setLdapGroups(implode(',', $entry->getAttribute('memberOf')));
        } catch (ConnectionException $e) {
            throw new BadCredentialsException('Cannot connect to LDAP server to check groups.');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [CheckPassportEvent::class => ['onCheckPassport', 144]];
    }
}

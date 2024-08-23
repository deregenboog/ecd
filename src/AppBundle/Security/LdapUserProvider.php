<?php

namespace AppBundle\Security;

use AppBundle\Entity\Medewerker;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Security\LdapUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Handles the mapping of ldap groups to security roles.
 * Class LdapUserProvider.
 */
class LdapUserProvider extends \Symfony\Component\Ldap\Security\LdapUserProvider
{
    /** @var array maps ldap groups to roles */
    private $groupMapping = [// Definitely requires modification for your setup
    ];

    /** @var string extracts group name from dn string */
    private $groupNameRegExp = '/^CN=(?P<group>[^,]+),ou.*$/i'; // You might want to change it to match your ldap server

    private $rolesGroups = [];

    /** @var string[] list of userAccountControl flags. See https://stackoverflow.com/questions/21698305/php-active-directory-user-account-control */
    private $msADUserAccountFlaglist = [
        1 => 'SCRIPT',
        2 => 'ACCOUNTDISABLE',
        8 => 'HOMEDIR_REQUIRED',
        16 => 'LOCKOUT',
        32 => 'PASSWD_NOTREQD',
        64 => 'PASSWD_CANT_CHANGE',
        128 => 'ENCRYPTED_TEXT_PWD_ALLOWED',
        256 => 'TEMP_DUPLICATE_ACCOUNT',
        512 => 'NORMAL_ACCOUNT',
        2048 => 'INTERDOMAIN_TRUST_ACCOUNT',
        4096 => 'WORKSTATION_TRUST_ACCOUNT',
        8192 => 'SERVER_TRUST_ACCOUNT',
        65536 => 'DONT_EXPIRE_PASSWORD',
        131072 => 'MNS_LOGON_ACCOUNT',
        262144 => 'SMARTCARD_REQUIRED',
        524288 => 'TRUSTED_FOR_DELEGATION',
        1048576 => 'NOT_DELEGATED',
        2097152 => 'USE_DES_KEY_ONLY',
        4194304 => 'DONT_REQ_PREAUTH',
        8388608 => 'PASSWORD_EXPIRED',
        16777216 => 'TRUSTED_TO_AUTH_FOR_DELEGATION',
        67108864 => 'PARTIAL_SECRETS_ACCOUNT',
    ];

    /**
     * @param array $rolesGroups
     */
    public function setRolesGroups($rolesGroups): void
    {
        $this->rolesGroups = $rolesGroups;
    }

    public function loadUser($username, Entry $entry): UserInterface
    {
        $roles = ['ROLE_USER']; // Actually we should be using $this->defaultRoles, but it's private. Has to be redesigned.

        if (!$entry->hasAttribute('memberOf')) { // Check if the entry has attribute with the group
            return new LdapUser($entry, $username, '', $roles);
        }

        foreach ($entry->getAttribute('memberOf') as $groupLine) { // Iterate through each group entry line
            //            $groupName = strtolower($this->getGroupName($groupLine)); // Extract and normalize the group name fron the line

            if (false !== $key = array_search($groupLine, $this->rolesGroups)) { // Check if the group is in the mapping
                $roles[] = $key; // Map the group to the role the user will have
            }
        }

        $objectguid = bin2hex($entry->getAttribute('objectGUID')[0]);
        $usercontrol = $entry->getAttribute('userAccountControl')[0];

        $flags = $this->findFlags($usercontrol); // for documentation purpose, this is left here...

        $isAccountEnabled = 2 == !($usercontrol & 2);

        $user = new Medewerker();
        $user->setUsername($username)
            ->setRoles($roles)
            ->setLdapGuid($objectguid)
            ->setLdapGroups($entry->getAttribute('memberOf'))
            ->setActief(1)
            ->setEmail($entry->getAttribute('mail')[0])
            ->setVoornaam($entry->getAttribute('givenName')[0])
            ->setAchternaam($entry->getAttribute('sn')[0])
        ;

        return $user;
    }

    public function findFlags($flag)
    {
        $flags = [];
        for ($i = 0; $i <= 26; ++$i) {
            if ($flag & (1 << $i)) {
                array_push($flags, 1 << $i);
            }
        } // bitwise separation of flags.

        foreach ($flags as $k => &$v) {
            $v = $v.' '.$this->msADUserAccountFlaglist[$v];
        }

        return $flags;
    }

    /**
     * Get the group name from the DN.
     *
     * @param string $dn
     *
     * @return string
     */
    private function getGroupName($dn)
    {
        $matches = [];

        return preg_match($this->groupNameRegExp, $dn, $matches) ? $matches['group'] : '';
    }

    public function supportsClass(string $class): bool
    {
        return Medewerker::class === $class;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof Medewerker) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $user;
    }
}

<?php

namespace AppBundle\Event;

use AppBundle\Entity\Medewerker;
use LdapTools\Bundle\LdapToolsBundle\Event\LoadUserEvent;

class LoadUserEventListener
{
    /**
     * Updates the Symfony user object with data from the LDAP object.
     *
     * @param LoadUserEvent $event
     */
    public function afterUserLoad(LoadUserEvent $event)
    {
        /** @var Medewerker $medewerker */
        $medewerker = $event->getUser();
        $ldapObject = $event->getLdapObject();

        $medewerker
            ->setLdapGuid($ldapObject->get('guid'))
            ->setLdapGroups($ldapObject->get('groups'))
            ->setVoornaam($ldapObject->get('givenName'))
            ->setAchternaam($ldapObject->get('sn'))
            ->setEmail($ldapObject->get('mail'))
            ->setActief($ldapObject->get('enabled'))
        ;
    }
}

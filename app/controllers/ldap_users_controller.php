<?php

class LdapUsersController extends AppController
{
    public $name = 'LdapUsers';

    public $uses = ['LdapUser'];

    public function index()
    {
        $this->set('primaryKey', $this->LdapUser->primaryKey);
        $users = $this->LdapUser->findAll('uid', '*');
        $this->set('ldap_users', $users);
    }

    public function matrix()
    {
        $permissions = Configure::read('ACL.permissions');
        ksort($permissions);

        $allControllers = [];
        foreach ($permissions as $controllers) {
            foreach ($controllers as $controller) {
                $allControllers[$controller] = $controller;
            }
        }
        ksort($allControllers);

        $this->loadModel('Medewerker');
        $activeUsers = $this->Medewerker->getActiveUsers();

        $ldapUsers = [];
        foreach ($this->LdapUser->findAll('uid', '*') as $ldapUser) {
            $uid = $ldapUser['LdapUser']['uid'];
            if (in_array($uid, $activeUsers)) {
                $ldapUsers[$uid] = ['cn' => $ldapUser['LdapUser']['cn']];
            }
        }
        ksort($ldapUsers);

        $medewerkers = [];
        $result = $this->Medewerker->query('SELECT id, username FROM medewerkers');
        foreach ($result as $row) {
            $medewerkers[$row['medewerkers']['username']] = $row['medewerkers']['id'];
        }

        foreach ($ldapUsers as $uid => $ldapUser) {
            if (key_exists($uid, $medewerkers)) {
                $ldapUsers[$uid]['id'] = $medewerkers[$uid];
            }
            $ldapUsers[$uid]['groups'] = [];
            foreach ($this->LdapUser->getGroups($uid) as $group) {
                $gid = $group['gidnumber'];
                if (array_key_exists($gid, $permissions)) {
                    $ldapUsers[$uid]['groups'][$gid] = $group['cn'];
                }
            }

            // remove irrelevant users
            if (count($ldapUsers[$uid]['groups']) === 0) {
                unset($ldapUsers[$uid]);
            }
        }

        $this->set('all_controllers', $allControllers);
        $this->set('permissions', $permissions);
        $this->set('ldap_users', $ldapUsers);
    }

    public function groups()
    {
        $groups = $this->LdapUser->getGroups();
        $this->set('ldap_user', []);
        $this->set('ldap_groups', $groups);

        $this->render('view');
    }

    public function view($uid)
    {
        $user = $this->LdapUser->read(null, $uid);
        $groups = $this->LdapUser->getGroups($uid);
        $this->set('ldap_user', $user);
        $this->set('ldap_groups', $groups);
    }
}

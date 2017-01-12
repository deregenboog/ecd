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

        foreach ($ldapUsers as $uid => $ldapUser) {
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

    public function add()
    {
        if (empty($this->data)) {
            $this->set('ldap_users', null);
            $newuid = $this->LdapUser->findLargestUidNumber() + 1;
            $this->set('newuid', $newuid);
        } else {
            if ($this->LdapUser->save($this->data)) {
                if (is_object($this->Session)) {
                    $this->flashError('The LDAP User has been saved');
                    $this->redirect('/ldap_users/index');
                } else {
                    $this->flash('LDAP User saved.', '/ldap_users/index');
                }
            } else {
                if (is_object($this->Session)) {
                    $this->flashError('Please correct errors below.');
                }
                $data = $this->data;
                $this->set('ldap_users', $data);
            }
        }
    }

    public function edit($id)
    {
        if (empty($this->data)) {
            $data = $this->LdapUser->read(null, $id);
            $this->set('ldap_user', $data);
        } else {
            $this->LdapUser->del($id);
            if ($this->LdapUser->save($this->data)) {
                if (is_object($this->Session)) {
                    $this->flashError('The LDAP User has been saved');
                    $this->redirect('/ldap_users/index');
                } else {
                    $this->flash('LDAP User saved.', '/ldap_users/index');
                }
            } else {
                if (is_object($this->Session)) {
                    $this->flashError('Please correct errors below.');
                }
                $data = $this->data;
                $this->set('ldap_user', $data);
            }
        }
    }

    public function view($uid)
    {
        $user = $this->LdapUser->read(null, $uid);
        $groups = $this->LdapUser->getGroups($uid);
        $this->set('ldap_user', $user);
        $this->set('ldap_groups', $groups);
    }

    public function delete($id)
    {
        $this->LdapUser->del($id);
        $this->redirect('/ldap_users/index');
    }
}

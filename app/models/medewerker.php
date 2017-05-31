<?php

class Medewerker extends AppModel
{
    public $name = 'Medewerker';
    public $users = array('Containable');

    public $displayField = 'name';
    public $validate = array(
        'achternaam' => array(
            'notempty' => array(
                'rule' => array(
                    'notempty',
                ),
            ),
        ),
    );

    public $actsAs = array(
        'Containable',
    );

    public $order = array(
        'voornaam ASC',
        'achternaam ASC',
    );

    public $hasMany = [];

    public $belongsTo = array(
        'LdapUser' => array(
            'className' => 'LdapUser',
            'foreignKey' => 'uidnumber',
        ),
    );

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['name'] = "CONCAT_WS(' ', `".$this->alias.'`.`voornaam`, `';
        $this->virtualFields['name'] .= $this->alias."`.`tussenvoegsel`, `$this->alias`.`achternaam`)";
    }

    public function getMembers($gid)
    {
        $m = $this->LdapUser->getMembers($gid);
        $members = $this->find('list', [
            'conditions' => ['username' => $m],
            'contain' => [],
        ]);

        return $members;
    }

    public function registerUser($userdata)
    {
        $username = $userdata['Medewerker']['username'];
        $ldapData = $userdata['Medewerker']['LdapUser'];

        if (isset($ldapData['uidnumber'])) {
            $user['uidnumber'] = $ldapData['uidnumber'];
        }

        $user['username'] = $username;
        $user['voornaam'] = $ldapData['givenname'];
        $user['achternaam'] = $ldapData['sn'];
        $user['laatste_bezoek'] = date('Y-m-d H:i:s');
        $user['email'] = $ldapData['mail'];

        $exists = $this->find('list', array(
            'conditions' => array(
                'username' => $username,
            ),
            'limit' => 1,
        ));

        if (!$exists) {
            $user['eerste_bezoek'] = date('Y-m-d H:i:s');
        } else {
            $user['id'] = current(array_flip($exists));
        }

        $medewerker['Medewerker'] = $user;
        $this->save($medewerker);

        $user_id = $this->id;

        return $user_id;
    }

    public function listByLdapGroup($group)
    {
        $cacheKey = 'Medewerker_listByLdapGroup_'.$group;
        $users = Cache::read($cacheKey, 'ldap');

        if (!$users) {
            $ldapUsers = $this->LdapUser->findAll('cn', $group);

            $userNames = array_values(
                Set::flatten(
                    Set::classicExtract($ldapUsers, '{n}.LdapUser.memberuid')
                )
            );

            $users = $this->find('list', array(
                'conditions' => array(
                    'username' => $userNames,
                ),
            ));
            Cache::write($cacheKey, $users, 'ldap');
        }

        return $users;
    }

    public function getActiveUsers()
    {
        $cacheKey = 'Medewerker_UsersAll';

        $users = Cache::read($cacheKey, 'ldap');
        if (!$users) {
            $ldapUsers = $this->LdapUser->findAll('loginshell', '/bin/bash');

            $users = array_values(
                Set::flatten(
                    Set::classicExtract($ldapUsers, '{n}.LdapUser.uid')
                )
            );

            Cache::write($cacheKey, $users, 'ldap');
        }

        return $users;
    }

    private function cacheKey($ids)
    {
        $cstr = '';
        if (!empty($ids)) {
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    $cstr += $id;
                }
            } else {
                $cstr = $ids;
            }

            if (!empty($cstr)) {
                $cstr = md5($cstr);
            }
        }

        return $cstr;
    }

    private function listByLdapGroupId(array $groupIds)
    {
        $cacheKey = 'Medewerker_listByLdapGroupId_'.$this->cacheKey($groupIds);
        $usernames = Cache::read($cacheKey, 'ldap');

        if (!$usernames) {
            $usernames = [];
            foreach ($groupIds as $groupId) {
                $usernames = array_merge($usernames, $this->LdapUser->getMembers($groupId));
            }
            Cache::write($cacheKey, $usernames, 'ldap');
        }

        return $usernames;
    }

    public function getMedewerkers(
        array $medewerker_ids = [],
        array $group_ids = [],
        $all_users = false
    ) {
        $cacheKey = 'Medewerker_getMedewerkers'.$this->cacheKey($group_ids).$this->cacheKey($medewerker_ids);
        $usernames = Cache::read($cacheKey, 'ldap');

        if (!empty($usernames)) {
            return $usernames;
        }

        $usernames = [];
        if (!empty($group_ids)) {
            $usernames = [];
            foreach ($group_ids as $groupId) {
                $usernames = array_merge($usernames, $this->listByLdapGroupId($group_ids));
            }
        }

        $options = [
            'contain' => [],
            'conditions' => [],
            'order' => 'voornaam, achternaam',
        ];

        if (!$all_users) {
            $options['conditions'] = ['active' => true];
        }

        if (!empty($group_ids)) {
            $options['conditions']['username'] = $usernames;
        }

        if (!empty($medewerker_ids)) {
            if (!empty($options['conditions'])) {
                $options['conditions'] = [
                    'OR' => array(
                        'AND' => $options['conditions'],
                        'id' => $medewerker_ids,
                    ),
                ];
            } else {
                $options['conditions'] = ['id' => $medewerker_ids];
            }
        }

        $usernames = $this->find('list', $options);
        Cache::write($cacheKey, $usernames, 'ldap');

        return $usernames;
    }

    public function uit_dienst()
    {
        $medewerkers = $this->find('all', array(
            'fields' => array('id', 'username', 'active'),
            'contain' => [],
        ));

        $ldap_users = $this->getActiveUsers();
        if (!$ldap_users) {
            return false;
        }

        foreach ($medewerkers as $key => $medewerker) {
            $username = $medewerker['Medewerker']['username'];
            if (in_array($username, $ldap_users)) {
                $medewerkers[$key]['Medewerker']['active'] = true;
            } else {
                $medewerkers[$key]['Medewerker']['active'] = false;
            }
        }

        if ($this->saveAll($medewerkers)) {
            return $medewerkers;
        }

        return false;
    }
}

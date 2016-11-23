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

    public $hasMany = array();

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
        $members = $this->find('list', array(
            'conditions' => array('username' => $m),
            'contain' => array(),

        ));

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

    public function listByLdapGroupId($group_ids)
    {
        $cacheKey = 'Medewerker_listByLdapGroupId_'.$this->cacheKey($group_ids);
        $users = Cache::read($cacheKey, 'ldap');
        //$users = null; // ignore cache

        if (!$users) {
            $ldapUsers = $this->LdapUser->findAll('gidnumber', $group_ids);
            $users = array_values(
                    Set::flatten(
                            Set::classicExtract($ldapUsers, '{n}.LdapUser.memberuid')
                    )
            );

            Cache::write($cacheKey, $users, 'ldap');
        }

        return $users;
    }

    public function getMedewerkers($medewerker_ids = null, $group_ids = null, $all_users = false)
    {
        $cacheKey = 'Medewerker_getMedewerkers'.$this->cacheKey($group_ids).$this->cacheKey($medewerker_ids);
        $medewerkers = Cache::read($cacheKey, 'ldap');

        if (!empty($medewerkes)) {
            return $medewerkers;
        }

        $medewerkers = array();
        if (!empty($group_ids)) {
            $medewerkers = $this->listByLdapGroupId($group_ids);
        }

        $options = array(
            'contain' => array(),
            'order' => 'voornaam, achternaam',
        );
        $options['conditions'] = array();
        if (!$all_users) {
            $options['conditions'] = array('active' => true);
        }

        if (!empty($group_ids)) {
            $options['conditions']['username'] = $medewerkers;
        }

        if (!empty($medewerker_ids)) {
            if (!empty($options['conditions'])) {
                $options['conditions'] = array(
                    'OR' => array(
                        'AND' => $options['conditions'],
                        'id' => $medewerker_ids,
                    ),
                );
            } else {
                $options['conditions'] = array(
                    'id' => $medewerker_ids,
                );
            }
        }
        $medewerkers = $this->find('list', $options);
        Cache::write($cacheKey, $medewerkers, 'ldap');

        return $medewerkers;
    }

    public function uit_dienst()
    {
        $medewerkers = $this->find('all', array(
                'fields' => array('id', 'username', 'active'),
                'contain' => array(),
        ));

        $ldap_users = $this->getActiveUsers();

        if (!$ldap_users) {
            return false;
        }

        $new = array();

        foreach ($medewerkers as $key => $medewerker) {
            $medewerkers[$key]['Medewerker']['active'] = false;
            $username = $medewerkers[$key]['Medewerker']['username'];

            if (in_array($username, $ldap_users)) {
                $medewerkers[$key]['Medewerker']['active'] = true;
            }
        }

        if ($this->saveAll($medewerkers)) {
            return $medewerkers;
        }

        return false;
    }
}

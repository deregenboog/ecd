<?php

class LdapUser extends AppModel
{
    public $name = 'LdapUser';
    public $useTable = false;
    public $primaryKey = 'uid';

    public $host;
    public $port;
    public $baseDn;

    public $ds;
    public $bind;

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);

        $config = Configure::read('LDAP.configuration');

        foreach ($config as $key => $val) {
            $this->$key = $val;
        }

        $this->ds = ldap_connect($this->host, $this->port);
        ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        $this->bind = ldap_bind($this->ds);
    }

    public function __destruct()
    {
        ldap_close($this->ds);
    }

    public function findAll($attribute = 'uid', $value = '*')
    {
        if (is_array($value)) {
            $s = '';
            foreach ($value as $v) {
                $s .= '('.$attribute.'='.$v.')';
            }
            $s = '(|'.$s.')';
        } else {
            $s = $attribute.'='.$value;
        }

        $r = ldap_search($this->ds, $this->baseDn, $s);
        if ($r) {
            ldap_sort($this->ds, $r, 'sn');

            $result = ldap_get_entries($this->ds, $r);

            return $this->convert_from_ldap($result);
        }

        return null;
    }

    public function testConnection()
    {
        if (!$this->ds) {
            return false;
        }

        if (!$this->bind) {
            return false;
        }

        return true;
    }

    public function read($fields, $uid)
    {
        $r = ldap_search($this->ds, $this->baseDn, 'uid='.$uid);
        if ($r) {
            $l = ldap_get_entries($this->ds, $r);
            $convert = $this->convert_from_ldap($l);

            return $convert[0];
        }
    }

    public function getMembers($gid)
    {
        $type = 'ldapGetMembers';
        $members = registry_get($type, $gid, true, 'ldap');

        if (!$members) {
            $members = [];
            $r = ldap_search($this->ds, 'ou=groups,'.$this->baseDn, 'gidNumber='.$gid);

            if ($r) {
                $l = ldap_get_entries($this->ds, $r);

                $m = $this->convert_from_ldap($l);

                if (count($m) == 1) {
                    if (is_array($m[0]['LdapUser']['memberuid'])) {
                        foreach ($m[0]['LdapUser']['memberuid'] as $member) {
                            $members[] = $member;
                        }
                    } else {
                        $members[] = $m[0]['LdapUser']['memberuid'];
                    }
                }
            }

            registry_set($type, $gid, $members, true, 'ldap');
        }

        return $members;
    }

    public function getGroups($uid = null)
    {
        $group_array = [];
        $r = ldap_search($this->ds, $this->baseDn, 'uid='.$uid);

        if ($r) {
            if ($uid) {
                $query = '(&(objectClass=posixgroup)(memberUid='.$uid.'))';
            } else {
                $query = 'objectClass=posixgroup';
            }

            $r = ldap_search($this->ds, $this->baseDn, $query);
            $l = ldap_get_entries($this->ds, $r);

            $groups = $this->convert_from_ldap($l);
            $group_array = [];

            foreach ($groups as $g) {
                $gid = $g['LdapUser']['gidnumber'];
                $cn = $g['LdapUser']['cn'];
                $group['gidnumber'] = $gid;
                $group['cn'] = $cn;
                $group_array[] = $group;
            }
        }

        return $group_array;
    }

    public function save($data)
    {
        $dn = 'uid='.$data['LdapUser']['uid'].','.$this->baseDn;

        foreach ($data['LdapUser'] as $field => $value):
         $data_ldap[$field][0] = $value;
        endforeach;

        $data_ldap['objectClass'] = array('account', 'posixAccount', 'top', 'shadowAccount');

        return ldap_add($this->ds, $dn, $data_ldap);
    }

    public function del($uid)
    {
        $dn = "uid=$uid,".$this->baseDn;

        return ldap_delete($this->ds, $dn);
    }

    public function auth($uid, $password)
    {
        $result = $this->findAll('uid', $uid);

        if (!empty($result[0])) {
            $connect = $this->ds;

            if (($res_id = ldap_search($connect, $this->baseDn,
                           "uid=$uid")) == false) {
                return false;
            }

            if (ldap_count_entries($connect, $res_id) != 1) {
                echo "failure: username $username found more than once<br>\n";

                return false;
            }

            if (($entry_id = ldap_first_entry($connect, $res_id)) == false) {
                return false;
            }

            if (($user_dn = ldap_get_dn($connect, $entry_id)) == false) {
                return false;
            }

            if (ldap_bind($this->ds, $user_dn, $password)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function findLargestUidNumber()
    {
        $r = ldap_search($this->ds, $this->baseDn, 'uidnumber=*');
        if ($r) {
            ldap_sort($this->ds, $r, 'uidnumber');

            $result = ldap_get_entries($this->ds, $r);
            $count = $result['count'];
            $biguid = $result[$count - 1]['uidnumber'][0];

            return $biguid;
        }

        return null;
    }

    private function convert_from_ldap($data)
    {
        $final = [];

        foreach ($data as $key => $row) {
            if ($key === 'count') {
                continue;
            }

            foreach ($row as $key1 => $param) {
                if (!is_numeric($key1)) {
                    continue;
                }

                if ($row[$param]['count'] === 1) {
                    $final[$key]['LdapUser'][$param] = $row[$param][0];
                } else {
                    foreach ($row[$param] as $key2 => $item) {
                        if ($key2 === 'count') {
                            continue;
                        }
                        $final[$key]['LdapUser'][$param][] = $item;
                    }
                }
            }
        }

        return $final;
    }

    public function disabled_users()
    {
        $users = null;
        $result = ldap_search($this->ds, 'ou=people,'.$this->baseDn, 'uid=robert');

        if ($result) {
            $users = ldap_get_entries($this->ds, $result);
        }

        return $users;
    }
}

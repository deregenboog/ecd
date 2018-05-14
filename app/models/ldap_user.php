<?php

use AppBundle\Exception\AppException;

class LdapUser extends AppModel
{
    const ACCOUNT_EXPIRES_NULL = 0;
    const ACCOUNT_EXPIRES_NEVER = 0x7FFFFFFFFFFFFFFF;

    public $name = 'LdapUser';
    public $useTable = false;
    public $primaryKey = 'uid';

    public $host;
    public $port;
    public $baseDn;

    public $ds;
    public $bind;

    public $active_directory = false;
    public $username = null;
    public $password = null;

    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);

        $config = Configure::read('LDAP.configuration');
        foreach ($config as $key => $val) {
            $this->$key = $val;
        }

        $this->ds = ldap_connect($this->host, $this->port);
        ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ds, LDAP_OPT_NETWORK_TIMEOUT, 15);

        if ($this->active_directory) {
            $this->uid = 'sAMAccountName';
            $this->group = 'group';
            $this->user = 'user';
            $this->memberUid = 'member';
            $this->gidnumber = 'cn';
            $this->gidnumber = 'distinguishedname';
            $this->bind = ldap_bind($this->ds, $this->username, $this->password);
        } else {
            $this->uid = 'uid';
            $this->group = 'posixgroup';
            $this->user = 'posixAccount';
            $this->memberUid = 'memberUid';
            $this->gidnumber = 'gidnumber';
            $this->gidnumber = 'gidnumber';
            $this->bind = ldap_bind($this->ds);
        }

        if (!$this->testConnection()) {
            throw new AppException('Cannot connect to authentication server');
        }
    }

    public function __destruct()
    {
        if (is_resource($this->ds)) {
            ldap_close($this->ds);
        }
    }

    public function isActiveDirectory()
    {
        return (bool) $this->active_directory;
    }

    public function findAll($attribute = null, $value = null)
    {
        if (!$attribute) {
            $attribute = $this->uid;
        }

        if (!$value) {
            $value = '*';
        }

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
        $query = "(&(objectClass={$this->user})({$this->uid}={$uid}))";
        $r = ldap_search($this->ds, $this->baseDn, $query);

        if ($r) {
            $l = ldap_get_entries($this->ds, $r);

            $convert = $this->convert_from_ldap($l);

            if ($this->active_directory) {
                $convert[0]['LdapUser']['displayname'] = $convert[0]['LdapUser']['cn'];
                $convert[0]['LdapUser']['uidnumber'] = '0';
            }

            return $convert[0];
        }
    }

    public function getMembers($gid)
    {
        $type = 'ldapGetMembers';
        $members = registry_get($type, $gid, true, 'ldap');

        if (!$members) {
            $members = [];

            if ($this->active_directory) {
                $r = ldap_search($this->ds, $gid, 'cn=*');
            } else {
                $r = ldap_search($this->ds, 'ou=groups,'.$this->baseDn, 'gidNumber='.$gid);
            }

            if ($r) {
                $l = ldap_get_entries($this->ds, $r);
                $m = $this->convert_from_ldap($l);

                if (!$this->active_directory) {
                    if (count($m) == 1) {
                        if (is_array($m[0]['LdapUser']['memberuid'])) {
                            foreach ($m[0]['LdapUser']['memberuid'] as $member) {
                                $members[] = $member;
                            }
                        } else {
                            $members[] = $m[0]['LdapUser']['memberuid'];
                        }
                    }
                } else {
                    if (!empty($m[0]['LdapUser']['member'])) {
                        foreach ($m[0]['LdapUser']['member'] as $member) {
                            $r = ldap_search($this->ds, $member, 'objectClass=person', ['sAMAccountName']);
                            if ($r) {
                                $l = ldap_get_entries($this->ds, $r);
                                $m = $this->convert_from_ldap($l);
                                $members[] = $m[0]['LdapUser']['samaccountname'];
                            }
                        }
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

        if ($uid) {
            if ($this->active_directory) {
                $user = $this->read(null, $uid);

                if (!empty($user)) {
                    $uid = $user['LdapUser']['distinguishedname'];
                }
            }
            $query = "(&(objectClass={$this->group})({$this->memberUid}={$uid}))";
        } else {
            $query = "objectClass={$this->group}";
        }

        $r = ldap_search($this->ds, $this->baseDn, $query);
        $l = ldap_get_entries($this->ds, $r);

        $groups = $this->convert_from_ldap($l);
        $group_array = [];

        foreach ($groups as $g) {
            $cn = $g['LdapUser']['cn'];
            $group['gidnumber'] = $g['LdapUser'][$this->gidnumber];
            $group['cn'] = $cn;
            $group_array[] = $group;

            $group_array[] = $group;
        }

        return $group_array;
    }

    public function auth($uid, $password)
    {
        $result = $this->findAll($this->uid, $uid);

        if (!empty($result[0])) {
            $connect = $this->ds;

            $res_id = ldap_search($connect, $this->baseDn, "{$this->uid}=$uid");
            if ($res_id == false) {
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
        }

        return false;
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
}

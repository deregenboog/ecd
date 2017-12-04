<?php
/**
* Extend the Auth component, but override login parts to use LDAP and multiple
* groups.
* Code based on
* http://bakery.cakephp.org/articles/view/authext-a-small-auth-extension-to-set-permission-on-user-belonging-to-several-groups-roles.
*/
App::import('component', 'Auth');

class AuthExtComponent extends AuthComponent
{
    public $parentModel = 'Group';
    public $userModel = 'Medewerker';

    // override, to store the associated role

    // Use LDAP login, the LdapUser model is required.

    public function login($data = null)
    {
        $this->_loggedIn = false;

        $model = $this->getModel();
        $alias = $model->alias;

        // The Auth component encrypts $password; the easiest is to post
        // another variable $passwd that is not encrypted, and use that one for
        // the LDAP authentication.

        $posted_data = $this->data;

        if (empty($data[$alias.'.username'])
            || empty($posted_data[$alias]['passwd'])
        ) {
            return $this->_loggedIn;
        }

        if (!$model->LdapUser->testConnection()) {
            $this->Session->setFlash('LDAP server not accessible.');

            return $this->_loggedIn;
        }

        if ($model->LdapUser->auth($data[$alias.'.username'], $posted_data[$alias]['passwd'])) {
            $this->_loggedIn = true;

            $ldap = $model->LdapUser->read(null, $data[$alias.'.username']);

            $user[$this->userModel]['username'] = $data[$alias.'.username'];
            if (isset($ldap['LdapUser']['uidnumber'])) {
                $user[$this->userModel]['uidnumber'] = $ldap['LdapUser']['uidnumber'];
            }

            $user[$this->userModel]['LdapUser'] = $ldap['LdapUser'];

            $groups = $model->LdapUser->getGroups($data[$alias.'.username']);
            if (!empty($groups)) {
                $user[$this->userModel]['LdapUser']['Groups'] = $groups;
                // Store group IDs in an accesible array, for Acl:
                $acl_groups = [];
                foreach ($groups as $g) {
                    $acl_groups[] = $g['gidnumber'];
                }
                $user[$this->userModel][$this->parentModel] = $acl_groups;
            }
            $this->Session->write($this->sessionKey, $user[$this->userModel]);
        } else {
            // The 'auth' flash is not shown in the view if it is shown in
            // the login view, so I removed it from there and left it only in
            // the default layout.
            $this->Session->setFlash($this->loginError, $this->flashElement, [], 'auth');
        }

        return $this->_loggedIn;
    }

    // override this to find the right aro/aco. If we use Auth->authorize =
    // 'controller' we don't need this at all (we just call the standard
    // isAuthorized() method, see http://book.cakephp.org/view/396/authorize).
    // This is left here in case we want to authorize on actions, while having
    // multiple groups.
    public function isAuthorized($type = null, $object = null, $user = null)
    {
        if (Configure::read('ACL.disabled') && Configure::read('debug') > 0) {
            // ACL disabled with a flag
            return true;
        }

        // $valid = parent::isAuthorized($type, $object, $user);
        $valid = false;

        if ($this->user($this->parentModel)) {
            if ($type == 'actions') {
                // Needs ACL, acos and aros tables.
                // get the roles from the Session, and set the proper Aro path
                $groups = $this->user($this->parentModel);
                // check using our Roles Aro paths
                $valid = $this->Acl->check([
                            $this->parentModel => ['id' => $groups],
                            ],
                        $this->action());
            } elseif ($type == 'controller') {
                // Call the controller function 'isAuthorized', that can be
                // defined in the app_controller for all controllers.
                $valid = parent::isAuthorized($type, $object, $user);
            }
        }

        return $valid;
    }
}

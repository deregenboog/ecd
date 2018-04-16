<?php

use AppBundle\Entity\Medewerker;

class MedewerkersController extends AppController
{
    public $name = 'Medewerkers';

    public function beforeFilter()
    {
        parent :: beforeFilter();
        $this->AuthExt->allow('login');
        $this->AuthExt->allow('logout');
        $this->AuthExt->allow('IueYRH4zBT8X');

        if ('clear_cache' == $this->action
            && in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])
        ) {
            $this->AuthExt->allow('clear_cache');
        }
    }

    public function index()
    {
        $this->Medewerker->recursive = 0;
        $this->set('medewerkers', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid medewerker', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('medewerker', $this->Medewerker->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Medewerker->create();
            if ($this->Medewerker->save($this->data)) {
                $this->flash(__('The medewerker has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The medewerker could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid medewerker', true));
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->Medewerker->save($this->data)) {
                $this->flash(__('The medewerker has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The medewerker could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Medewerker->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for medewerker', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->Medewerker->delete($id)) {
            $this->flash(__('Medewerker deleted', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('Medewerker was not deleted', true));
        $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if ($this->AuthExt->user()) {
            $user_groups = $this->AuthExt->user('Group');
            $ldap = $this->AuthExt->user('LdapUser');
            $this->log(['LdapUser' => $ldap, 'Group' => $user_groups], 'login');

            // valid users need to belong to at least one of the known groups.
            $permissions = Configure::read('ACL.permissions');
            $is_ok = false;
            foreach (array_keys($permissions) as $known_group) {
                if (in_array($known_group, $user_groups)) {
                    $is_ok = true;
                    break;
                }
            }

            if (!$is_ok) {
                $this->Session->destroy();
                if (isset($ldap['displayname'])) {
                    $name = $ldap['displayname'];
                } else {
                    $name = 'deze medewerker';
                }
                $this->flashError(
                    'Sorry, '.$name.' is nog niet bevoegd om dit systeem te gebruiken.'
                );
                $this->redirect($this->AuthExt->logout());
            }

            $user_id = $this->Medewerker->registerUser($this->AuthExt->user());
            $this->Session->Write('Auth.Medewerker.id', $user_id);

            $this->saveUserGroups($user_id, $ldap);

            if (isset($ldap['displayname'])) {
                $this->flash(__('Welkom', true).' '.$ldap['displayname']);
            } else {
                $this->flash(__('Welkom', true));
            }

            $afterLoginUrl = $this->Session->read('AfterLogin.Url');
            if ($afterLoginUrl && false === strpos($afterLoginUrl, 'login')) {
                return $this->redirect($afterLoginUrl);
            } else {
                return $this->redirect('/');
            }
        }

        unset($this->data['Medewerker']['passwd']);
    }

    private function saveUserGroups($userId, $ldap = [])
    {
        $em = $this->getEntityManager();
        $user = $em->find(Medewerker::class, $userId);

        if ($user && key_exists('Groups', $ldap)) {
            $userGroups = [];
            foreach ($ldap['Groups'] as $group) {
                $userGroups[] = $group['gidnumber'];
            }
            $user->setGroepen($userGroups);
            try {
                $em->persist($user);
                $em->flush($user);
            } catch (\Exception $e) {
                // ignore
            }
        }
    }

    public function logout()
    {
        $this->Session->destroy();
        $this->flash(__('Have a nice day!', true));
        $this->redirect($this->AuthExt->logout());
    }

    // Ajax function that deletes roles of a user in a particular object.
    // Only owner of a hotel/organization can use this.
    // Also a user may want to delete its own roles at hotels/organizations
    // he belongs to.
    public function ajaxDeleteRolesForUser($user_id = null, $model = null, $foreign_key = null)
    {
        $this->RolesUser->deleteRolesForUserInObject($user_id, $model, $foreign_key);
    }

    /**
     * clear_cache Clear all caches intensively. The purpose is to force a
     * restart without having to restart apache. This doesn't clean the
     * /tmp/minify_* files, those should be generated automatically when one of
     * the css/js files changes its date.
     */
    public function clear_cache($type = 'manual')
    {
        if (!empty($this->data)) {
            $types = array_filter($this->data['type']);
        } elseif ('auto' == $type) {
            $types = [$type];
        } else {
            $types = [];
        }
        $messages = [];

        foreach ($types as $type) {
            switch ($type) {
            case 'default':
                Cache::clear(false, 'default');
                $messages[] = 'Default cache deleted.';
                break;
            case 'ldap':
                Cache::clear(false, 'ldap');
                $messages[] = 'LDAP cache deleted.';
                break;
            case 'views':
                clearCache(null, 'views');
                $messages[] = 'VIEW cache deleted.';
                break;
            case 'models':
                clearCache(null, 'models');
                Cache::clear(false, '_cake_model_');
                Cache::clear(false, '_cake_core_');
                $messages[] = 'MODEL cache deleted.';
                break;
            case 'opcode':
                if (function_exists('apc_clear_cache')) {
                    apc_clear_cache('opcode');
                    $messages[] = 'OPCODE cache deleted.';
                }
                break;
            case 'persistent':
                $messages[] = 'PERSISTENT cache deleted.';
                clearCache(null, 'persistent');
                break;
            case 'apc':
                if (function_exists('apc_clear_cache')) {
                    apc_clear_cache();
                    $messages[] = 'APC cache deleted.';
                }
                break;
            case 'auto':
                clearCache(null, 'models');
                clearCache(null, 'persistent');
                Cache::clear(false, '_cake_model_');
                Cache::clear(false, '_cake_core_');
                Cache::clear(false, 'default');
                Cache::clear(false, 'ldap');
                if (function_exists('apc_clear_cache')) {
                    apc_clear_cache();
                    apc_clear_cache('opcode');
                    debug(apc_cache_info());
                }
                $this->autoRender = false;

                return true;
            default:
                $messages[] = '____________ Error: no action defined for type '.$type;
            }
        }

        if (!empty($messages)) {
            $this->Session->setFlash(implode('<br />', $messages));
        }
    }

    public function IueYRH4zBT8X()
    {
        $this->loadModel('Geslacht');

        $this->Geslacht->recursive = -1;
        $retval = true;

        $first = $this->Geslacht->read('id', 1);
        if (empty($first) || $first['Geslacht']['id'] != 1) {
            $retval = false;
        }

        $getbyid = $this->Geslacht->getById(1);
        if (empty($getbyid) || 1 != $getbyid['id']) {
            $retval = false;
        }

        $data = [$retval];

        $this->set(jsonVar, $data);
        $this->render('/elements/json', 'ajax');
    }
}

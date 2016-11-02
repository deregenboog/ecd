<?php

class MedewerkersController extends AppController
{
    public $name = 'Medewerkers';

    public function beforeFilter()
    {
        parent :: beforeFilter();
        $this->AuthExt->allow('login');
        $this->AuthExt->allow('logout');

        if ($this->action == 'clear_cache' &&
            in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
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
            $this->redirect(array('action' => 'index'));
        }
        $this->set('medewerker', $this->Medewerker->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Medewerker->create();
            if ($this->Medewerker->save($this->data)) {
                $this->flash(__('The medewerker has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The medewerker could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid medewerker', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Medewerker->save($this->data)) {
                $this->flash(__('The medewerker has been saved', true));
                $this->redirect(array('action' => 'index'));
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
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Medewerker->delete($id)) {
            $this->flash(__('Medewerker deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->flashError(__('Medewerker was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    public function login()
    {
        if ($this->AuthExt->user()) {
            $user_groups = $this->AuthExt->user('Group');
            $ldap = $this->AuthExt->user('LdapUser');

            // valid users need to belong to at least one of the known groups.

            $enable = Configure::read('ACL.permissions');
            $valid_groups = array_keys($enable);

            $is_ok = false;

            $this->log(array('LdapUser' => $ldap, 'Group' => $user_groups),
                    'login');

            foreach ($valid_groups as $known_group) {
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
                $this->flashError('Sorry, '.$name.
                        ' is nog niet bevoegd om dit systeem te gebruiken.');
                $this->redirect($this->AuthExt->logout());
            }

            $user_id = $this->Medewerker->registerUser($this->AuthExt->user());
            $this->Session->Write('Auth.Medewerker.id', $user_id);

            if (isset($ldap['displayname'])) {
                $this->flash(__('Welkom', true).' '.
                     $ldap['displayname']);
            } else {
                $this->flash(__('Welkom', true));
            }

            if (isset($ldap['gidnumber']) &&
                $ldap['gidnumber'] == GROUP_ADMIN) {
                // Superusers have their main posix gidnumber equal to
        // GROUP_ADMIN
                $this->Session->Write('is_superuser', true);
            } else {
                $this->Session->Write('is_superuser', false);
            }

            $cont = $this->Session->read('AfterLogin.Controler');
            $action = $this->Session->read('AfterLogin.Action');

            if ($cont && $action && $action != 'login') {
                $this->redirect(array(
                            'controller' => $cont, 'action' => $action,
                            )
                        );
            }

            $this->redirect('/');
        }

        unset($this->data['Medewerker']['passwd']);
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
     *
     * @access public For administrators.
     * @return void
     */
   public function clear_cache($type = 'manual')
   {
       if (!empty($this->data)) {
           $types = array_filter($this->data['type']);
       } elseif ($type == 'auto') {
           $types = array($type);
       } else {
           $types = array();
       }
       $messages = array();

       foreach ($types as $type) {
           switch ($type) {
           case 'default':
               Cache::clear(false, 'default');
               $messages[] = 'Default cache deleted.';
               break;
           case 'ldap':
               Cache::clear(false, 'ldap');
               $messages[] = 'Default cache deleted.';
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
               break;
           default:
               $messages[] = '____________ Error: no action defined for type '.$type;
           }
       }
       if (!empty($messages)) {
           $this->Session->setFlash(implode('<br />', $messages));
       }
   }
}

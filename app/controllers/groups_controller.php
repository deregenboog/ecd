<?php

// We don't use groups in the current Auth approach, where things are filtered
// based on the controller and not in the actions. This is commited here so
// that it is available the day we need to use the Acl component to filter on
// actions. Then we will need AROs and ACOs, and there are functions in here
// that are very handy to generate these.

class GroupsController extends AppController
{
    public $name = 'Groups';
    public $helpers = ['Html', 'Form'];
    public $uses = [];

    public function beforeFilter()
    {
        parent::beforeFilter();
        // Enable this to add more groups manually
        // $this->Auth->allow('*');
    }

    public function index()
    {
        $this->Group->recursive = 0;
        $this->set('groups', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid Group', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('group', $this->Group->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Group->create();
            if ($this->Group->save($this->data)) {
                $this->flashError(__('The Group has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The Group could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid Group', true));
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->Group->save($this->data)) {
                $this->flashError(__('The Group has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The Group could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Group->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for Group', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->Group->del($id)) {
            $this->flashError(__('Group deleted', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('The Group could not be deleted. Please, try again.', true));
        $this->redirect(['action' => 'index']);
    }

    /** Copy all LDAP groups to a local table. We don't need this if we
     * Auth->autorize = 'controller', that's only necessary for 'actions' */
    public function admin_store_ldap_groups()
    {
        if (!Configure::read('debug')) {
            return $this->_stop();
        }
        $this->loadModel('LdapUser');

        $groups = $this->LdapUser->getGroups();

        foreach ($groups as $g) {
            $save['id'] = $g['gidnumber'];
            $save['name'] = $g['cn'];
            $this->Group->save($save);
        }
    }

    /** We don't need AROs and ACOs this if we
     * Auth->autorize = 'controller', that's only necessary for 'actions' */

    /** This function is used to generate an ACO table everytime
     * we need to add new objects, because we have new controllers or methods
     * inside them. It is quite tedious, but right now it is the best way to
     * ensure security.
     * Because we are using cake's ACL to handle groups only, and these are
     * static metadata, the ACO and ARO tables can be then exported just as
     * metadata to the metadata.sql file, so that we don't need to run this
     * procedure and the initDB below on every installation. See
     * http://agilo.toltech.nl/trac/wiki/HandleDatabaseEntries.
     * http://book.cakephp.org/complete/641/Simple-Acl-controlled-Application.

     * Make function _private to deactivate it.
     */
    public function admin_build_acl()
    {
        if (!Configure::read('debug')) {
            return $this->_stop();
        }
        $log = [];

        $aco = &$this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $aco->create(['parent_id' => null, 'model' => null, 'alias' => 'controllers']);
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
            $log[] = 'Created Aco node for controllers';
        } else {
            $root = $root[0];
        }

        App::import('Core', 'File');
        $Controllers = Configure::listObjects('controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'buildAcl';

        $Plugins = $this->_getPluginControllerNames();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

            // Do all Plugins First
            if ($this->_isPlugin($ctrlName)) {
                $pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
                if (!$pluginNode) {
                    $aco->create(['parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)]);
                    $pluginNode = $aco->save();
                    $pluginNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for '.$this->_getPluginName($ctrlName).' Plugin';
                }
            }
            // find / make controller node
            $controllerNode = $aco->node('controllers/'.$ctrlName);
            if (!$controllerNode) {
                if ($this->_isPlugin($ctrlName)) {
                    $pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
                    $aco->create(['parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)]);
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for '.$this->_getPluginControllerName($ctrlName).' '.$this->_getPluginName($ctrlName).' Plugin Controller';
                } else {
                    $aco->create(['parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName]);
                    $controllerNode = $aco->save();
                    $controllerNode['Aco']['id'] = $aco->id;
                    $log[] = 'Created Aco node for '.$ctrlName;
                }
            } else {
                $controllerNode = $controllerNode[0];
            }

            //clean the methods. to remove those in Controller and private actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
                if (!$methodNode) {
                    $aco->create(['parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method]);
                    $methodNode = $aco->save();
                    $log[] = 'Created Aco node for '.$ctrlName.'/'.$method;
                }
            }
        }
        if (count($log) > 0) {
            debug($log);
        }
    }

    public function _getClassMethods($ctrlName = null)
    {
        App::import('Controller', $ctrlName);
        if (strlen(strstr($ctrlName, '.')) > 0) {
            // plugin's controller
            $num = strpos($ctrlName, '.');
            $ctrlName = substr($ctrlName, $num + 1);
        }
        $ctrlclass = $ctrlName.'Controller';
        $methods = get_class_methods($ctrlclass);

        // Add scaffold defaults if scaffolds are being used
        $properties = get_class_vars($ctrlclass);
        if (array_key_exists('scaffold', $properties)) {
            if ($properties['scaffold'] == 'admin') {
                $methods = array_merge($methods, ['admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete']);
            } else {
                $methods = array_merge($methods, ['add', 'edit', 'index', 'view', 'delete']);
            }
        }

        return $methods;
    }

    public function _isPlugin($ctrlName = null)
    {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) > 1) {
            return true;
        } else {
            return false;
        }
    }

    public function _getPluginControllerPath($ctrlName = null)
    {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0].'.'.$arr[1];
        } else {
            return $arr[0];
        }
    }

    public function _getPluginName($ctrlName = null)
    {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[0];
        } else {
            return false;
        }
    }

    public function _getPluginControllerName($ctrlName = null)
    {
        $arr = String::tokenize($ctrlName, '/');
        if (count($arr) == 2) {
            return $arr[1];
        } else {
            return false;
        }
    }

    /**
     * Get the names of the plugin controllers ...
     *
     * This function will get an array of the plugin controller names, and
     * also makes sure the controllers are available for us to get the
     * method names by doing an App::import for each plugin controller.
     *
     * @return array of plugin names
     */
    public function _getPluginControllerNames()
    {
        App::import('Core', 'File', 'Folder');
        $paths = Configure::getInstance();
        $folder = new Folder();
        $folder->cd(APP.'plugins');

        // Get the list of plugins
        $Plugins = $folder->read();
        $Plugins = $Plugins[0];
        $arr = [];

        // Loop through the plugins
        foreach ($Plugins as $pluginName) {
            // Change directory to the plugin
            $didCD = $folder->cd(APP.'plugins'.DS.$pluginName.DS.'controllers');
            // Get a list of the files that have a file name that ends
            // with controller.php
            $files = $folder->findRecursive('.*_controller\.php');

            // Loop through the controllers we found in the plugins directory
            foreach ($files as $fileName) {
                // Get the base file name
                $file = basename($fileName);

                // Get the controller name
                $file = Inflector::camelize(substr($file, 0, strlen($file) - strlen('_controller.php')));
                if (!preg_match('/^'.Inflector::humanize($pluginName).'App/', $file)) {
                    if (!App::import('Controller', $pluginName.'.'.$file)) {
                        debug('Error importing '.$file.' for plugin '.$pluginName);
                    } else {
                        /// Now prepend the Plugin name ...
                        // This is required to allow us to fetch the method names.
                        $arr[] = Inflector::humanize($pluginName).'/'.$file;
                    }
                }
            }
        }

        return $arr;
    }

    // http://localhost/regenboog/admin/groups/list_all_methods

    public function admin_list_all_methods()
    {
        if (!Configure::read('debug')) {
            return $this->_stop();
        }

        App::import('Core', 'File');
        $Controllers = Configure::listObjects('controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'buildAcl';

        $Plugins = $this->_getPluginControllerNames();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

            //clean the methods, to remove those in Controller and private
            //actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = 'controllers/'.$ctrlName.'/'.$method;
                $log[] = $methodNode;
            }
        }
        debug($log);
    }

    public function admin_initDB_methods()
    {
        if (!Configure::read('debug')) {
            return $this->_stop();
        }

        /** Patterns are regular expressions, so 'edit' enables actions like
         admin_, _private and base methods are ignored anyway.
         */
        $enable_patterns = [
            GROUP_PORTIER => [
                    'Registraties' => ['.*'],
                    ],
            GROUP_MLO => [
                    'Intakes' => ['.*'],
                    'Klanten' => ['.*'],
                    'Registraties' => ['.*'],
                    ],
            GROUP_MAATSCHAPPELIJK => [
                    'Intakes' => ['.*'],
                    'Klanten' => ['.*'],
                    'MaatschappelijkWerken' => ['.*'],
                    ],
            GROUP_HI5 => [
                    'Intakes' => ['.*'],
                    'Klanten' => ['.*'],
                    'HiFive' => ['.*'],
                    ],
            GROUP_ADMIN => [
                    'Intakes' => ['.*'],
                    'Klanten' => ['.*'],
                    'HiFive' => ['.*'],
                    'MaatschappelijkWerken' => ['.*'],
                    'Registraties' => ['.*'],
                    ],
            ];

        $log = [];

        // Build first an array of all methods in controllers.

        App::import('Core', 'File');
        $Controllers = Configure::listObjects('controller');
        $appIndex = array_search('App', $Controllers);
        if ($appIndex !== false) {
            unset($Controllers[$appIndex]);
        }
        $baseMethods = get_class_methods('Controller');
        $baseMethods[] = 'buildAcl';

        $Plugins = $this->_getPluginControllerNames();
        $Controllers = array_merge($Controllers, $Plugins);

        // look at each controller in app/controllers
        foreach ($Controllers as $ctrlName) {
            $methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

            //clean the methods, to remove those in Controller and private
            //actions.
            foreach ($methods as $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (strpos($method, 'admin_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
                $methodNode = 'controllers/'.$ctrlName.'/'.$method;

                $methodNodes[$ctrlName][$method] = $methodNode;
            }
        }

        $group = &$this->Group;
        foreach ($enable_patterns as $group_id => $controllers) {
            $group->id = $group_id;
            $this->Acl->deny($group, 'controllers');
            $log[] = "Deny group $group_id access to all controllers -----";

            foreach ($controllers as $controller => $patterns) {
                foreach ($patterns as $pattern) {
                    foreach ($methodNodes[$controller] as $method => $node) {
                        if (preg_match("/$pattern/", $method)) {
                            $this->Acl->allow($group, $node);
                            $log[] = "Granted $group_id access for $node";
                        }
                    }
                }
            }
        }

        if (count($log) > 0) {
            debug($log);
        }
    }

    /**
     * clear_cache Clear all caches intensively. The purpose is to force a
     * restart without having to restart apache.
     */
    public function clear_cache()
    {
        // Clear contents of cache directories
        clearCache(null, 'views');
        clearCache(null, 'models');
        clearCache(null, 'persistent');

        // clear keys from memory cache
        Cache::clear();

        // Extra cleaning if we use APC:
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
            apc_clear_cache('opcode');
            debug(apc_cache_info());
        }

        if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
            echo "Local access.\n";
        }

        echo "The cache has been cleared.\n";
        die;
    }
}

<?php
//  Application Controller. Here we specify functions that can be shared with all controllers.

class AppController extends Controller
{

    // The helpers we are going to use in all controllers. We do that for
    // default view, later we can specify helpers that will be used per
    // controller.

    public $helpers = array('Date', 'Session', 'Html', 'Js', 'Format');
    // setup components
    public $components = array('Session', 'AuthExt', 'RequestHandler', 'DebugKit.Toolbar');
    // default datetime filter
    /**
     * user groups for the current logged in user.
     * @var array
     */
    public $userGroups = array();

    /*
     * List of controllers belonging to menu items
     */
    public $menuControllers = array(
        'Hi5' => array('Hi5', 'Hi5Intakes'),
        'Klanten' => array('Klanten'),
        'Vrijwilligers' => array('Vrijwilligers'),
        'Registraties' => array('Registraties'),
        'MaatschappelijkWerk' => array('MaatschappelijkWerk', 'Verslagen'),
        'Rapportages' => array('Rapportages'),
        'Awbz' => array('Awbz'),
        'PfoClienten' => array('PfoClienten', 'PfoAardRelaties', 'PfoGroepen', 'PfoClientenVerslagen', 'PfoVerslagen'),
        'BackOnTrack' => array('BackOnTrack', 'BotKoppelingen', 'BotVerslagen'),
        'IzDeelnemers' => array('IzDeelnemers', 'IzProjecten', 'IzIntervisiegroepen', 'IzVerslagen', 'IzKoppelingen',
            'IzEindekoppelingen', 'IzAfsluitingen', 'IzVraagaanboden', 'IzOntstaanContacten', 'IzViaPersonen', ),
        'Groepsactiviteiten' => array('Groepsactiviteiten', 'GroepsactiviteitenGroepen', 'GroepsactiviteitenRedenen',
            'GroepsactiviteitenIntakes', 'GroepsactiviteitenVerslagen', ),
        'Admin' => array('Admin', 'ZrmSettings'),
    );

    /**
     * Before any Controller action
     */

    public function _findIp()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            return getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            return getenv("HTTP_X_FORWARDED_FOR");
        } else {
            return getenv("REMOTE_ADDR");
        }
    }

    /** Check if a controller is accesible by the current user, based on the
     * groups it belongs to. The list of controllers accessible per group are
     * defined at config/core.php, in the array ACL.permissions. 
     *
     * @param String $controller The controller name
     */

    public function _isControllerAuthorized ($controller)
    {
        $s_user = $this->Session->read('is_superuser');

        if ($s_user) {
            return true;
        }

        $allow = false;

        $enable = Configure::read('ACL.permissions');

        foreach ($this->userGroups as $gid => $g_name) {
            if (!isset($enable[$gid])) {
                // Don't know anything about this group
                // debug("Don't know about $gid");
                continue;
            }
            if (in_array($controller, $enable[$gid])
                || in_array("*", $enable[$gid])) {
                // Users in this group can use this controller.
                // debug("$controller is for $gid");
                $allow = true;
                break;
            } else {
                // debug("$controller is NOT for $gid");
            }
        }

        return $allow;
    }

    /** Get a parameter's value from somewhere of the parameters attribute, by
     * using a predefined order. A position can be specified, if the parameter
     * may show up in the GET URL at a certain position. If it is not found,
     * return null */
    public function getParam($full_name, $position = -1, $default = null)
    {
        $p = &$this->params;

        // A full Model.field reference is used to explore the $data.
        $d = Set::classicExtract($this->data, $full_name);
        if ($d !== null) {
            if (is_string($d)) {
                return trim($d);
            } else {
                return $d;
            }
        }

        // For the other parameters, only the field name is used:
        $name = end(explode('.', $full_name));

        if (isset($p['form'][$name])) {
            if (!is_array($p['form'][$name])) {
                return trim($p['form'][$name]);
            }
            return $p['form'][$name];
        }
        if (isset($p['named'][$name])) {
            if (!is_array($p['named'][$name])) {
                return trim($p['named'][$name]);
            }
            return ($p['named'][$name]);
        }
        if (isset($p['url'][$name])) {
            if (!is_array($p['url'][$name])) {
                return trim($p['url'][$name]);
            }
            return ($p['url'][$name]);
        }

        // A position in the URL can be also used:
        if (isset($p['pass'][$position])) {
            return $p['pass'][$position];
        }

        return $default;
    }

    /** We don't need AROs and ACOs this if we
     * Auth->autorize = 'controller', that's only necessary for 'actions'.
     * Because we filter on controller, an isAuthorized() function is required
     * per controller. We put a generic one here so that is present for all of
     * them, with common parameters: */

    public function isAuthorized()
    {
        //ts('isAuthorized');
        $allow = $this->_isControllerAuthorized($this->name);
        if (!$allow) {
            $this->Session->setFlash("{$this->name}: toegang geweigerd");
        }
        //ts('isAuthorized end');
        return $allow;
    }

    /**
     * forAdminOnly If you are not admin, redirect. For quick access handling
     * in actions.
     * 
     * @access public
     * @return void
     */
    public function forAdminsOnly()
    {
        if (!$this->user_is_administrator) {
            $this->flashError(__('Action restricted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    /**
     * Function to set medewerker parameters in the view
     * group_ids array of ecd groups
     * @param unknown_type $medewerker_ids can be an array of medewerkers with should be in the array even when inactive
     */
    public function setMedewerkers($medewerker_ids = null,  $group_ids = null)
    {
        if (! isset($this->Medewerkers)) {
            $this->Medewerker= ClassRegistry::init('Medewerker');
        }
        $viewmedewerkers=array('' => '');
        $viewmedewerkers += $this->Medewerker->getMedewerkers(null, null, true);
        $this->set('viewmedewerkers', $viewmedewerkers);

        $medewerkers=array('' => '');
        $medewerkers += $this->Medewerker->getMedewerkers($medewerker_ids, $group_ids, false);
        //debug($medewerkers);
        $this->set('medewerkers', $medewerkers);
    }

    /**
     * flash Wrapper for Session-flash. See flashError, it is used more.
     * 
     * @param mixed $msg 
     * @access public
     * @return void
     */
    public function flash($msg)
    {
        $this->Session->setFlash($msg);
    }

    /**
     * flashError Wrapper for Session-flash, using error message CSS styling.
     * 
     * @param mixed $msg 
     * @access public
     * @return void
     */
    public function flashError($msg)
    {
        $this->Session->setFlash($msg, 'default',
                    array('class' => 'error-message'));
    }

    public function beforeFilter()
    {
        //Configure AuthComponent
        // Authorize = actions makes use of ACL.
        // http://book.cakephp.org/view/396/authorize

        // By authorizing controller and not actions, we can get rid of ACL and
        // keep things simple. See isAuthorized() above.
        //ts('beforeFilter');
        $this->AuthExt->authorize = 'controller';
        $this->AuthExt->autoRedirect = false;
        $this->AuthExt->actionPath = 'controllers/';
        $this->AuthExt->loginAction =
            array('controller' => 'medewerkers', 'action' => 'login');
        $this->AuthExt->logoutRedirect =
            array('controller' => 'medewerkers', 'action' => 'login');
        //this is to allow everyone to see the static pages
        $this->AuthExt->allowedActions = array('display');

        // Element to be rendered for ajax login form:
        $this->AuthExt->ajaxLogin = "ajax_login";
        $auth = $this->Session->read('Auth');
        $this->set('user_is_logged_in', false);
        $this->set('user_is_administrator', false);
        $s_user = false;
        $user_groups = $this->AuthExt->user('Group');
        if (!empty($user_groups)) {
            $this->userGroups = array_flip($user_groups);
            $this->set('userGroups', $this->userGroups);
        } else {
            $this->set('userGroups', array());
        }

        if (Configure::read("ACL.disabled") && Configure::read('debug')) {
            // Disable ACL, fake user data:
            $auth['Group'] = array( 1 );
            $auth['username'] = "sysadmin";
            $auth['Medewerker']['LdapUser']['displayname'] = "System Administrator";
            $auth['Medewerker']['LdapUser']['givenname'] = "System";
            $auth['Medewerker']['LdapUser']['sn'] = "Administrator";
            $auth['Medewerker']['LdapUser']['uidnumber'] = "1";
            $s_user = $this->Session->write('is_superuser', $auth);
            // $this->Session->write('Auth.User', $auth);
        }

        // route the user to home directory

        if (isset($auth['Medewerker'])) {
            // We are logged in already.
            //$contact = $this->Session->read('Auth.Contact');
            // set some view variables:
            $this->set('user_is_logged_in', true);
            $user_id = $this->Session->read('user_id');
            $this->set('user_id', $user_id);

            // Store user ID also in the default model, so that we can use it
            // there.
            $name = $this->modelClass;

            $s_user = $this->Session->read('is_superuser');

            if ($s_user) {
                // Allow admins to do everything here by now! Like that we do
                // not need to create all ACOs.
                // TODO: without this, it seems access to actions is not
                // granted to s_user despite it is allowed to access all
                // controllers/. ACO for all specific objects need to exists!
                $this->AuthExt->allow('*');
            } else {
            }

            // http://bakery.cakephp.org/articles/view/logablebehavior
            if (sizeof($this->uses) &&
                    property_exists($this->modelClass, 'Behaviors') &&
                    $this->{$this->modelClass}->
                    Behaviors->attached('Logable')) {
                $activeUser = array('Medewerker' => array(
                            'id' => $auth['Medewerker']['id'],
                            'username' => $auth['Medewerker']['username'],
                            ),
                        );
                $this->{$this->modelClass}->setUserData($activeUser);
                $this->{$this->modelClass}->setUserIp($this->RequestHandler->getClientIP());
            }
        } else {
            // Make sure the user model is present, in case we are rendering
            // the AJAX login form views/elements/ajax_login (set in
            // $this->AuthExt->ajaxLogin above).
            $this->loadModel('Medewerker');
            if ($this->action != 'login') {
                $this->Session->write('AfterLogin.Controler', $this->name);
                $this->Session->write('AfterLogin.Action', $this->action);
            }
        }

        if ($s_user
                || array_key_exists(GROUP_ADMIN, $this->userGroups)
                || array_key_exists(GROUP_DEVELOP, $this->userGroups)) {
            $is_admin = true;
        } else {
            $is_admin = false;
        }
        // Pass it to the view, model, and the controller
        //$model->user_is_administrator = $is_admin;
        $this->user_is_administrator = $is_admin;
        $this->set('user_is_administrator', $is_admin);
        $this->set('htmlBodyId', Inflector::variable($this->name.'_'.$this->action));

        //ts('beforeFilter end');
    }
    /**
    *before render
    */
    public function beforeRender()
    {

        //ts('beforeRender');
        if (isset($this->data['Medewerker']['password'])) {
            unset($this->data['Medewerker']['password']);
        }
        if (isset($this->data['Medewerker']['password_confirm'])) {
            unset($this->data['Medewerker']['password_confirm']);
        }

        $menu_elements = Configure::read('all_menu_items');

        $menu_allowed = array();

        foreach ($menu_elements as $controller => $text) {
            if ($this->_isControllerAuthorized($controller)) {
                $menu_allowed[$controller] = $text;
            }
        }
        $this->set(compact('menu_allowed'));
        $this->set('menuControllers', $this->menuControllers);

        // A hack to fix the problem that HABTM validation messages do not come
        // to the right place. Maybe it is fixed in latest Cakes, but we need
        // it here for the Inkomen errors to be taken out from the Intakes, for
        // example.
        $model = Inflector::singularize($this->name);
        if (!empty($this->{$model}) &&
                is_array($this->{$model}->hasAndBelongsToMany)) {
            foreach ($this->{$model}->hasAndBelongsToMany as $k=>$v) {
                if (isset($this->{$model}->validationErrors[$k])) {
                    $this->{$model}->{$k}->validationErrors[$k] = $this->{$model}->validationErrors[$k];
                }
            }
        }

        //ts('beforeRender end');
    }

    public function afterFilter()
    {
        //ts($this->name . " " . $this->action . " afterFilter");
    }

    /*
     * sends an email to $recipent, with $content
    */
    public function _sendEmail($recipent, $content)
    {
        $this->Email->to = $recipent;
        $this->Email->subject = 'Title';
        $this->Email->replyTo = 'test@regenboog.nl';
        $this->Email->from = 'Regenbog <test@regenboog.nl>';
        $this->Email->template = 'blank';
        $this->Email->sendAs = 'text';
        //Set view variables
        $this->set('content', $content);
        //send
        $this->Email->send();
    }

/** A generic sendEmail function for internal usage, that accepts multiple
     * optional parameters (see array $defaults) and multiple addressees passed
     * as an array (that will generate multiple emails with the same contents).
     *
     * It will use email templates. All parameters will be passed to the view,
     * but the contents should all be in the array $params['contents'] to keep
     * things tidy. The basic template 'generic_notification' uses only
     * $params['contents']['text'], for example.
     * @param $parameters array
     * $param $debug if true, do not send any real email, but return an array
     * with all emails that would have been generated.
     */

    public function _genericSendEmail($parameters, $debug = false)
    {
        App::import('Component', 'Email');
        $this->Email =& new EmailComponent(null);
        if (method_exists($this->Email, 'initialize')) {
            $this->Email->initialize($this);
        }
        if (method_exists($this->Email, 'startup')) {
            $this->Email->startup($this);
        }
        $defaults = array(
            'template' => 'default',
            'from_id' => null,
            'to' => array(),
            'cc' => array(),
            'bcc' => array(),
            'message_id' => "",
            'summary' => "",
            'error' => null,
            'model' => null,
            'direct_link' => null,
            'foreign_key' => null,
            'subject' => "Regenboog verzoek",
            'from' => 'noreply@deregenboog.org',
            'replyTo' => 'noreply@deregenboog.org',
            'returnPath' => 'noreply@deregenboog.org',
            'sendAs' => 'text', );

        $params = array_merge($defaults, $parameters);
        $this->set('params', $params);

        if (empty($params['to'])) {
            return false;
        }

        $this->Email->subject = $params['subject'];
        $this->Email->replyTo = $params['replyTo'];
        $this->Email->from = $params['from'];
        $this->Email->return = $params['returnPath'];
        $this->Email->additionalParams = "-r ".$params['returnPath'];
        $this->Email->template = $params['template'];
        $this->Email->sendAs = $params['sendAs'];
        $this->Email->cc = $params['cc'];
        $this->Email->bcc = $params['bcc'];

        $result = true;
        $sent = array();

        foreach ($params['to'] as $user_id => $to) {
            $this->Email->to = $to;
            // First render the email and store the contents in the session.
            $this->Email->delivery = 'debug';
            $this->Email->send();

            // Then send the real email:
            if (!$debug) {
                $this->Email->delivery = 'mail';
                $success = $this->Email->send();
            } else {
                $success = true;
            }

            $message['from'] = $params['from_id'];
            $message['user_id'] = $user_id;
            $message['subject'] = $params['subject'];
            $c = $this->Session->read('Message.email');
            $message['content'] = var_export($c, true);
            $sent[] = $c['message'];
        }

        if ($debug) {
            return $sent;
        }

        return $result;
    }

    protected function formatPostedDate($key)
    {
        $startDate = $this->data[$key];
        $endDate = $this->data['date_to'];

        if (!empty($startDate['year']) && !empty($startDate['month']) && !empty($startDate['day'])) {
            $startDate = $startDate['year'].'-'.$startDate['month'].'-'.$startDate['day'];
        } else {
            $startDate = null;
        }

        if (!empty($endDate['year']) && !empty($endDate['month']) && !empty($endDate['day'])) {
            $endDate = $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'];
        } else {
            $endDate = null;
        }
    }
}

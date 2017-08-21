<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
    /**
     * Here, we are connecting '/' (base path) to controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, /app/views/pages/home.ctp)...
     */
    Router::connect('/', ['controller' => 'pages', 'action' => 'display', 'home']);
/*
 * ...and connect the rest of 'Pages' controller's urls.
 */
    Router::connect('/pages/*', ['controller' => 'pages', 'action' => 'display']);

    //some aliases that make maatschappelijk_werk in the url go to verslagen_controller
    Router::connect('/maatschappelijk_werk/', ['controller' => 'verslagen', 'action' => 'index']);
    Router::connect('/MaatschappelijkWerk/', ['controller' => 'verslagen', 'action' => 'index']);
    Router::connect('/maatschappelijk_werk', ['controller' => 'verslagen', 'action' => 'index']);
    Router::connect('/MaatschappelijkWerk', ['controller' => 'verslagen', 'action' => 'index']);
    Router::connect('/maatschappelijk_werk/:action/*', ['controller' => 'verslagen', 'action' => 'index']);
    Router::connect('/MaatschappelijkWerk/:action/*', ['controller' => 'verslagen', 'action' => 'index']);

<?php
/**
 * A custom view class that is used for themeing.
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
 * @since         CakePHP(tm) v 0.10.0.1076
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Theme view class.
 *
 * Allows the creation of multiple themes to be used in an app. Theme views are regular view files
 * that can provide unique HTML and static assets.  If theme views are not found for the current view
 * the default app view files will be used. You can set `$this->theme` and `$this->view = 'Theme'`
 * in your Controller to use the ThemeView.
 *
 * Example of theme path with `$this->theme = 'super_hot';` Would be `app/views/themed/super_hot/posts`
 */
class ThemeView extends View
{
    /**
     * Constructor for ThemeView sets $this->theme.
     *
     * @param Controller $controller controller object to be rendered
     * @param bool       $register   should the view be registered in the registry
     */
    public function __construct(&$controller, $register = true)
    {
        parent::__construct($controller, $register);
        $this->theme = &$controller->theme;
    }

    /**
     * Return all possible paths to find view files in order.
     *
     * @param string $plugin the name of the plugin views are being found for
     * @param bool   $cached set to true to force dir scan
     *
     * @return array paths
     *
     * @todo Make theme path building respect $cached parameter.
     */
    public function _paths($plugin = null, $cached = true)
    {
        $paths = parent::_paths($plugin, $cached);
        $themePaths = [];

        if (!empty($this->theme)) {
            $count = count($paths);
            for ($i = 0; $i < $count; ++$i) {
                if (false === strpos($paths[$i], DS.'plugins'.DS)
                    && false === strpos($paths[$i], DS.'libs'.DS.'view')) {
                    if ($plugin) {
                        $themePaths[] = $paths[$i].'themed'.DS.$this->theme.DS.'plugins'.DS.$plugin.DS;
                    }
                    $themePaths[] = $paths[$i].'themed'.DS.$this->theme.DS;
                }
            }
            $paths = array_merge($themePaths, $paths);
        }

        return $paths;
    }
}

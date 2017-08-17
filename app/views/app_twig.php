<?php


/*
 * Base Classes
 */
App::import('View', 'Twig.Twig');

/**
 * TwigView Class.
 *
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 */
class AppTwigView extends TwigView
{
    /**
     * Default Options.
     *
     * @var array
     */
    public $twigOptions = [
        'fileExtension' => '.twig',
        'extensions' => [
//             'i18n',
//             'number',
//             'text',
//             'time',
        ],
    ];

    /**
     * Constructor.
     *
     * @param Controller $controller A controller object to pull View::__passedArgs from
     * @param bool       $register   Should the View instance be registered in the ClassRegistry
     *
     * @return View
     */
    public function __construct(&$controller, $register = true)
    {
        parent::__construct($controller, $register);

        $this->twigPluginPath = dirname(dirname(__FILE__)).DS.'plugins'.DS.'twig'.DS;
        $this->twigExtensionPath = $this->twigPluginPath.'extensions';

        // import page title, if assigned the old way
        if (isset($controller->pageTitle)) {
            $this->pageTitle = $controller->pageTitle;
        }

        // import plugin options
        $appOptions = Configure::read('TwigView');
        if (!empty($appOptions) && is_array($appOptions)) {
            $this->twigOptions = array_merge($this->twigOptions, $appOptions);
        }

        // set preferred extension
        $this->ext = $this->twigOptions['fileExtension'];

        // Setup template paths
        $pluginFolder = Inflector::underscore($this->plugin);
        $paths = $this->_paths($pluginFolder);
        foreach ($paths as $i => $path) {
            // Make "{% include 'test.ctp' %}" a replacement for self::element()
            $paths[] = $path.'elements'.DS;
        }

        // check if all paths really exist. unfortunately Twig_Loader_Filesystem does an is_dir() for each path
        // while CakePHP just assumes you know what you are doing.
        foreach ($paths as $i => $path) {
            if (!is_dir($path)) {
                unset($paths[$i]);
                continue; // skip
            }
        }

        // Setup Twig Environment
// 		$loader = new Twig_Loader_Filesystem($paths);
// 		$this->Twig = new Twig_Environment($loader, array(
// 				'cache' => false, // use cakephp cache
// 				'debug' => (Configure::read() > 0),
// 		));
        global $kernel;
        $this->Twig = $kernel->getContainer()->get('twig');

        $loader = $this->Twig->getLoader();
        foreach ($paths as $path) {
            $loader->addPath($path);
        }

        // Do not escape return values (from helpers)
// 		$escaper = new Twig_Extension_Escaper(false);
// 		$this->Twig->addExtension($escaper);

        // Add custom TwigView Extensions
        $this->twigLoadedExtensions = [];
        foreach ($this->twigOptions['extensions'] as $extensionName) {
            if ($extensionClassName = $this->_loadTwigExtension($extensionName)) {
                $this->Twig->addExtension(new $extensionClassName());
            }
        }

        $this->set('this', $this);
    }
}

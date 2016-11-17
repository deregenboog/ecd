<?php

use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension;
use Knp\Bundle\PaginatorBundle\Helper\Processor;
use App\Service\UrlGenerator;
use Symfony\Component\Translation\Translator;

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
    public $twigOptions = array(
        'fileExtension' => '.twig',
        'extensions' => array(
            'i18n',
            'number',
            'text',
            'time',
        ),
    );

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
        $container = KernelRegistry::getInstance()->getKernel()->getContainer();
        $this->Twig = $container->get('twig');

        $loader = $this->Twig->getLoader();
        foreach ($paths as $path) {
            $loader->addPath($path);
        }

        // Do not escape return values (from helpers)
// 		$escaper = new Twig_Extension_Escaper(false);
// 		$this->Twig->addExtension($escaper);

        // Add custom TwigView Extensions
        $this->twigLoadedExtensions = array();
        foreach ($this->twigOptions['extensions'] as $extensionName) {
            if ($extensionClassName = $this->_loadTwigExtension($extensionName)) {
                $this->Twig->addExtension(new $extensionClassName());
            }
        }
    }

    /**
     * Constructor.
     *
     * @param Controller $controller
     *                               A controller object to pull View::__passedArgs from
     * @param bool       $register
     *                               Should the View instance be registered in the ClassRegistry
     *
     * @return View
     */
    public function ___construct(&$controller, $register = true)
    {
        parent::__construct($controller, $register);

        $container = ContainerRegistry::getInstance()->getContainer();
        $this->Twig = $container->get('twig');

//         var_dump($twig); die;
//         var_dump($this->Twig); die;

        $this->Twig = $twig;

//         $this->Twig->getLoader()->addPath(realpath(__DIR__.'/../../vendor/symfony/twig-bridge/Resources/views/Form'));

//         $formEngine = new TwigRendererEngine([
//             'form_div_layout.html.twig',
//         ]);
//         $formEngine->setEnvironment($this->Twig);
//         $this->Twig->addExtension(new FormExtension(new TwigRenderer($formEngine)));

//         $this->Twig->addExtension(new PaginationExtension(
//         	new Processor(new UrlGenerator(), new Translator('nl_NL'))
//         ));
    }
}

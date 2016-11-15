<?php

use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;

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
     * Constructor.
     *
     * @param Controller $controller
     *                               A controller object to pull View::__passedArgs from
     * @param bool       $register
     *                               Should the View instance be registered in the ClassRegistry
     *
     * @return View
     */
    public function __construct(&$controller, $register = true)
    {
        parent::__construct($controller, $register);

        $this->Twig->getLoader()->addPath(realpath(__DIR__.'/../../vendor/symfony/twig-bridge/Resources/views/Form'));

        $formEngine = new TwigRendererEngine([
            'form_div_layout.html.twig',
        ]);
        $formEngine->setEnvironment($this->Twig);
        $this->Twig->addExtension(new FormExtension(new TwigRenderer($formEngine)));
    }
}

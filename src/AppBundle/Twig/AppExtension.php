<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getGlobals()
    {
        return [
            'tab' => "\t",
        ];
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tabless', array($this, 'tablessFilter')),
        );
    }

    public function tablessFilter($value)
    {
        return preg_replace("/(\s|\t)+/", ' ', $value);
    }

    public function getName()
    {
        return 'app_extension';
    }
}

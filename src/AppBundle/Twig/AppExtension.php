<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    public function __construct($locale)
    {
        setlocale(LC_MONETARY, $locale);
    }

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
            new \Twig_SimpleFilter('money', array($this, 'moneyFilter')),
        );
    }

    public function tablessFilter($value)
    {
        return preg_replace("/(\s|\t)+/", ' ', $value);
    }

    public function moneyFilter($value)
    {
        return money_format('%(#1n', $value);
    }
}

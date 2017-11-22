<?php

namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack, $locale)
    {
        $this->requestStack = $requestStack;
        setlocale(LC_MONETARY, $locale);
    }

    public function getGlobals()
    {
        return [
            'tab' => "\t",
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('isActiveRoute', [$this, 'isActiveRoute']),
            new \Twig_SimpleFunction('isActivePath', [$this, 'isActivePath']),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('tabless', [$this, 'tablessFilter']),
            new \Twig_SimpleFilter('money', [$this, 'moneyFilter']),
            new \Twig_SimpleFilter('nl2ws', [$this, 'nl2wsFilter']),
            new \Twig_SimpleFilter('unique', [$this, 'uniqueFilter']),
            new \Twig_SimpleFilter('color', [$this, 'colorFilter'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('green', [$this, 'greenFilter'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('red', [$this, 'redFilter'], ['is_safe' => ['html']]),
        ];
    }

    public function tablessFilter($value)
    {
        return preg_replace("/(\s|\t)+/", ' ', $value);
    }

    public function moneyFilter($value)
    {
        return money_format('%(#1n', $value);
    }

    public function nl2wsFilter($value)
    {
        return preg_replace("/(\r|\n|\r\n)+/", ' ', $value);
    }

    public function uniqueFilter(array $values)
    {
        return array_unique($values);
    }

    public function isActiveRoute($patterns)
    {
        if (!is_array($patterns)) {
            $patterns = [$patterns];
        }

        $route = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        foreach ($patterns as $pattern) {
            if (false !== strpos($route, $pattern)) {
                return true;
            }
        }

        return false;
    }

    public function isActivePath($patterns)
    {
        if (!is_array($patterns)) {
            $patterns = [$patterns];
        }

        $path = $this->requestStack->getCurrentRequest()->getPathInfo();
        foreach ($patterns as $pattern) {
            if (false !== strpos($path, $pattern)) {
                return true;
            }
        }

        return false;
    }

    public function colorFilter($value, $color)
    {
        return sprintf('<span style="color: %s;">%s</span>', $color, $value);
    }

    public function greenFilter($value)
    {
        return $this->colorFilter($value, 'green');
    }

    public function redFilter($value)
    {
        return $this->colorFilter($value, 'red');
    }
}

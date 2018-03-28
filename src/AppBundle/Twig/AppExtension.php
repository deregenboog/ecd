<?php

namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Common\Collections\Collection;
use AppBundle\Entity\Geslacht;

class AppExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $administratorName;

    /**
     * @var string
     */
    private $administratorEmail;

    public function __construct(
        RequestStack $requestStack,
        $locale,
        $administratorName,
        $administratorEmail
    ) {
        $this->requestStack = $requestStack;
        setlocale(LC_MONETARY, $locale);
        $this->administratorName = $administratorName;
        $this->administratorEmail = $administratorEmail;
    }

    public function getGlobals()
    {
        return [
            'tab' => "\t",
            'now' => new \DateTime('now'),
            'today' => new \DateTime('today'),
            'administrator_name' => $this->administratorName,
            'administrator_email' => $this->administratorEmail,
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
            new \Twig_SimpleFilter('saldo', [$this, 'saldoFilter'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('factuurSaldo', [$this, 'factuurSaldoFilter'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('nl2ws', [$this, 'nl2wsFilter']),
            new \Twig_SimpleFilter('unique', [$this, 'uniqueFilter']),
            new \Twig_SimpleFilter('color', [$this, 'colorFilter'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('green', [$this, 'greenFilter'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('red', [$this, 'redFilter'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('orderBy', [$this, 'orderBy']),
            new \Twig_SimpleFilter('aanhef', [$this, 'aanhef']),
        ];
    }

    public function aanhef(Geslacht $geslacht = null)
    {
        if (!$geslacht) {
            return 'heer/mevrouw';
        }

        switch ($geslacht->getAfkorting()) {
            case 'M':
                return 'heer';
            case 'V':
                return 'mevrouw';
            default:
                return 'heer/mevrouw';
        }
    }

    public function orderBy($collection, $method)
    {
        if ($collection instanceof Collection) {
            $collection = $collection->toArray();
        }

        usort($collection, function($itemA, $itemB) use ($method) {
            if (call_user_func([$itemA, $method]) > call_user_func([$itemB, $method])) {
                return 1;
            }
            if (call_user_func([$itemA, $method]) < call_user_func([$itemB, $method])) {
                return -1;
            }
            return 0;
        });

        return $collection;
    }

    public function tablessFilter($value)
    {
        return preg_replace("/(\s|\t)+/", ' ', $value);
    }

    public function moneyFilter($value)
    {
        // check if locale set in %framework.default_locale% is supported
        if (setlocale(LC_ALL, 0) && 'C' !== setlocale(LC_ALL, 0)) {
            return money_format('%+#1n', (float) $value);
        }

        // or fallback
        return '€ '.number_format((float) $value, 2, ',', '.');
    }

    /**
     * Combines the filter "money" and "color".
     *
     * @param float $value
     * @return string
     */
    public function saldoFilter($value)
    {
        return $this->colorFilter(
            $this->moneyFilter((float) $value),
            ($value < 0) ? 'red' : 'green'
        );
    }

    /**
     * Inversed version of "saldo" filter.
     *
     * @param float $value
     * @return string
     */
    public function factuurSaldoFilter($value)
    {
        return $this->saldoFilter(-1 * $value);
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

<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Persoon;
use AppBundle\Exception\AppException;
use AppBundle\Service\NameFormatter;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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

    /**
     * @var int
     */
    private $tbcMonthsPeriod;

    public static function getRedirectUri(Request $request)
    {
        return preg_replace('/^.*[?&]redirect=([^&]*).*/', '$1', $request->getRequestUri());
    }

    public function __construct(
        RequestStack $requestStack,
        $locale,
        $administratorName,
        $administratorEmail,
        $tbcMonthsPeriod
    ) {
        $this->requestStack = $requestStack;
        setlocale(LC_MONETARY, $locale);
        $this->administratorName = $administratorName;
        $this->administratorEmail = $administratorEmail;
        $this->tbcMonthsPeriod = $tbcMonthsPeriod;
    }

    public function getGlobals()
    {
        return [
            'tab' => "\t",
            'now' => new \DateTime('now'),
            'today' => new \DateTime('today'),
            'administrator_name' => $this->administratorName,
            'administrator_email' => $this->administratorEmail,
            'redirect_uri' => self::getRedirectUri($this->requestStack->getCurrentRequest()),
            'tbc_months_period' => $this->tbcMonthsPeriod,
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('class', [$this, 'getClass']),
            new \Twig_SimpleFunction('instanceof', [$this, 'isInstanceof']),
            new \Twig_SimpleFunction('isActiveRoute', [$this, 'isActiveRoute']),
            new \Twig_SimpleFunction('isActivePath', [$this, 'isActivePath']),
            new \Twig_SimpleFunction('colgroup', [$this, 'colgroup'], ['is_safe' => ['html']]),
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
            new \Twig_SimpleFilter('naam_voor_achter', [$this, 'naamVoorAchter']),
            new \Twig_SimpleFilter('naam_achter_voor', [$this, 'naamAchterVoor']),
            new \Twig_SimpleFilter('name_formal', [$this, 'nameFormal']),
            new \Twig_SimpleFilter('name_informal', [$this, 'nameInformal']),
            new \Twig_SimpleFilter('diff', [$this, 'diff']),
            new \Twig_SimpleFilter('human_days', [$this, 'humanDays']),
            new \Twig_SimpleFilter('implode', [$this, 'implode']),
        ];
    }

    public function naamVoorAchter(Persoon $persoon)
    {
        $parts = [];

        try {
            if ($persoon->getVoornaam()) {
                $parts[] = $persoon->getVoornaam();
            }
            if ($persoon->getRoepnaam()) {
                $parts[] = "({$persoon->getRoepnaam()})";
            }
            if ($persoon->getTussenvoegsel()) {
                $parts[] = $persoon->getTussenvoegsel();
            }
            if ($persoon->getAchternaam()) {
                $parts[] = $persoon->getAchternaam();
            }
        } catch (EntityNotFoundException $e) {
            // ignore
        }

        return implode(' ', $parts);
    }

    public function naamAchterVoor(Persoon $persoon)
    {
        $parts = [];

        try {
            if ($persoon->getAchternaam()) {
                if ($persoon->getVoornaam() || $persoon->getTussenvoegsel() || $persoon->getRoepnaam()) {
                    $parts[] = $persoon->getAchternaam().',';
                } else {
                    $parts[] = $persoon->getAchternaam();
                }
            }
            if ($persoon->getVoornaam()) {
                $parts[] = $persoon->getVoornaam();
            }
            if ($persoon->getRoepnaam()) {
                $parts[] = "({$persoon->getRoepnaam()})";
            }
            if ($persoon->getTussenvoegsel()) {
                $parts[] = $persoon->getTussenvoegsel();
            }
        } catch (EntityNotFoundException $e) {
            // ignore
        }

        return implode(' ', $parts);
    }

    public function nameFormal(Persoon $persoon)
    {
        return NameFormatter::formatFormal($persoon);
    }

    public function nameInformal(Persoon $persoon)
    {
        return NameFormatter::formatInformal($persoon);
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

        usort($collection, function ($itemA, $itemB) use ($method) {
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
     *
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
     *
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

    public function getClass($object, $fqcn = true)
    {
        $class = get_class($object);

        if (false === $fqcn) {
            $class = substr($class, 1 + strrpos($class, '\\'));
        }

        return $class;
    }

    public function isInstanceof($object, $class)
    {
        return $object instanceof $class;
    }

    public function isActiveRoute($patterns)
    {
        if (!is_array($patterns)) {
            $patterns = [$patterns];
        }

        $route = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        foreach ($patterns as $pattern) {
            if (0 === strpos($route, $pattern) || 0 === strpos($route, 'app_'.$pattern)) {
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
            if (1 === strpos($path, $pattern)) {
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

    public function diff(\DateTime $a, \DateTime $b)
    {
        return $a->diff($b);
    }

    /**
     * Humanizes the number of days: translates it to days, weeks, months or
     * years depending on the number of days.
     *
     * @param int $days Number of days
     *
     * @return string
     */
    public function humanDays($days)
    {
        if (0 == $days) {
            return 'vandaag';
        }

        if (1 == $days) {
            return '1 dag';
        }

        if ($days < 7) {
            return $days.' dagen';
        }

        if ($days < 30) {
            return floor($days / 7).' weken';
        }

        if ($days < 365) {
            return floor($days / 30).' maanden';
        }

        if (1 == floor($days / 365)) {
            return '1 jaar';
        }

        return floor($days / 365).' jaren';
    }

    public function implode($collection, $separator = ', ')
    {
        $values = [];
        foreach ($collection as $item) {
            $values[] = (string) $item;
        }

        return implode($values, $separator);
    }

    public function colgroup($n, array $percentages = [])
    {
        if (array_sum($percentages) > 100) {
            throw new AppException(sprintf('Percentage cannot be greater than 100 for %s', var_export($percentages, true)));
        }

        if ($n > count($percentages)) {
            $percentages = array_merge($percentages, array_fill(0, $n - count($percentages), null));
        }

        $remainder = 100 - array_sum($percentages);
        $nulls = array_filter($percentages, 'is_null');
        foreach (array_keys($nulls) as $i) {
            $percentages[$i] = floor($remainder / count($nulls));
        }

        $remainder = 100 - array_sum($percentages);
        foreach (array_keys($nulls) as $i) {
            if ($remainder > 0) {
                ++$percentages[$i];
                --$remainder;
            } else {
                break;
            }
        }

        $html = '<colgroup>';
        foreach ($percentages as $percentage) {
            $html .= '<col width="'.$percentage.'%"></col>';
        }
        $html .= '</colgroup>';

        return $html;
    }
}

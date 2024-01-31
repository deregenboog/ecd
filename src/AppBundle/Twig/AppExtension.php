<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Geslacht;
use AppBundle\Entity\Persoon;
use AppBundle\Exception\AppException;
use AppBundle\Service\ECDHelper;
use AppBundle\Service\NameFormatter;
use AppBundle\Util\DateTimeUtil;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Polyfill\Intl\Icu\Exception\MethodArgumentValueNotImplementedException;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Error\Error;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $locale;

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
        $tbcMonthsPeriod,
        ECDHelper $ecdHelper
    ) {
        $this->requestStack = $requestStack;
        $this->locale = $locale;
        $this->administratorName = $administratorName;
        $this->administratorEmail = $administratorEmail;
        $this->tbcMonthsPeriod = $tbcMonthsPeriod;
        $this->ecdHelper = $ecdHelper;
    }

    public function getGlobals(): array
    {
        return [
            'tab' => "\t",
            'now' => new \DateTime('now'),
            'today' => new \DateTime('today'),
            'administrator_name' => $this->administratorName,
            'administrator_email' => $this->administratorEmail,
            'redirect_uri' => self::getRedirectUri($this->requestStack->getCurrentRequest()),
            'tbc_months_period' => $this->tbcMonthsPeriod,
            'title' => null,
            'entity_name' => null,
            'route_base' => null,
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('class', [$this, 'getClass']),
            new TwigFunction('instanceof', [$this, 'isInstanceof']),
            new TwigFunction('isActiveRoute', [$this, 'isActiveRoute']),
            new TwigFunction('isActivePath', [$this, 'isActivePath']),
            new TwigFunction('colgroup', [$this, 'colgroup'], ['is_safe' => ['html']]),
            new TwigFunction('asset', [$this, 'asset']),
            new TwigFunction('sleep', [$this, 'sleep']),
            ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('apply_filter', [$this, 'applyFilter'], [
                'needs_environment' => true,
                'needs_context' => true,
            ]),
            new TwigFilter('tabless', [$this, 'tablessFilter']),
            new TwigFilter('money', [$this, 'moneyFilter']),
            new TwigFilter('saldo', [$this, 'saldoFilter'], ['is_safe' => ['html']]),
            new TwigFilter('factuurSaldo', [$this, 'factuurSaldoFilter'], ['is_safe' => ['html']]),
            new TwigFilter('nl2ws', [$this, 'nl2wsFilter']),
            new TwigFilter('unique', [$this, 'uniqueFilter']),
            new TwigFilter('color', [$this, 'colorFilter'], ['is_safe' => ['html']]),
            new TwigFilter('colorIf', [$this, 'colorIfFilter'], ['is_safe' => ['html'], 'is_variadic' => true]),
            new TwigFilter('green', [$this, 'greenFilter'], ['is_safe' => ['html']]),
            new TwigFilter('red', [$this, 'redFilter'], ['is_safe' => ['html']]),
            new TwigFilter('orderBy', [$this, 'orderBy']),
            new TwigFilter('aanhef', [$this, 'aanhef']),
            new TwigFilter('naam_voor_achter', [$this, 'naamVoorAchter']),
            new TwigFilter('naam_achter_voor', [$this, 'naamAchterVoor']),
            new TwigFilter('name_formal', [$this, 'nameFormal']),
            new TwigFilter('name_informal', [$this, 'nameInformal']),
            new TwigFilter('diff', [$this, 'diff']),
            new TwigFilter('human_days', [$this, 'humanDays']),
            new TwigFilter('day_of_week', [DateTimeUtil::class, 'dayOfWeek']),
            new TwigFilter('implode', [$this, 'implode']),
            new TwigFilter('postvoorkeur', [$this, 'postvoorkeur']),
            new TwigFilter('emailvoorkeur', [$this, 'emailvoorkeur']),
            new TwigFilter('telefoonvoorkeur', [$this, 'telefoonvoorkeur']),
            new TwigFilter('try', [$this, 'try']),
            new TwigFilter('ja_nee', [$this, 'jaNee']),
            new TwigFilter('if_date', [$this, 'ifDate'], [
                'needs_environment' => true,
            ]),
            new TwigFilter('filterAllRows',[$this->ecdHelper,'filterAllRows']),
        ];
    }

    /**
     * @see https://github.com/marcj/twig-apply_filter-bundle
     */
    public function applyFilter(Environment $env, array $context, $value, $filters)
    {
        $name = 'apply_filter_'.md5($filters);
        $template = $env->createTemplate(sprintf('{{ %s|%s }}', $name, $filters));
        $context[$name] = $value;

        return $template->render($context);
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

    public function moneyFilter($value, $currency = 'EUR')
    {


        try {
            $fmt = new \NumberFormatter($this->locale, \NumberFormatter::CURRENCY);

            return $fmt->formatCurrency($value, $currency);
        } catch (MethodArgumentValueNotImplementedException $e) {

        } catch (\Exception $e) {
            //better safe than sorry?
        }

        return $currency." ".$value;


    }

    /**
     * Combines the filter "money" and "color".
     *
     * @param float $value
     *
     * @return string
     */
    public function saldoFilter($value, $currency = 'EUR')
    {
        return $this->colorFilter(
            $this->moneyFilter((float) $value, $currency),
            ($value < 0) ? 'red' : 'green'
        );
    }

    public function colorIfFilter($value, array $options = [])
    {
        $color = null;
        $cases = $options[0];
        $colors = $options[1];

        foreach ($cases as $k => $v) {
            if ($value == $v) {
                $color = $colors[$k];
            }
        }
        if ($color) {
            return $this->colorFilter($value, $color);
        }

        return $value;
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

    /**
     * If mode is OR, if one of pattersn are matched, true is returned.
     * If mode is AND, all patterns must be matched.
     * @param $patterns
     * @param $mode
     * @return bool
     */
    public function isActiveRoute($patterns, $mode="OR")
    {
        if (!is_array($patterns)) {
            $patterns = [$patterns];
        }

        $route = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $routeParams = $this->requestStack->getCurrentRequest()->attributes->get('_route_params');//->getQueryString();

        $noMatches = 0;
        foreach ($patterns as $pattern) {
            if (0 === strpos($route, $pattern) || 0 === strpos($route, 'app_'.$pattern) || false!==in_array($pattern,$routeParams) ) {
                $noMatches++;
            }
        }
        if($noMatches > 0 && $mode == 'AND' && $noMatches == count($patterns))
        {
            return true;
        }
        elseif($noMatches > 0 && $mode == "OR")
        {
            return true;
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
            $values[] = (string) $item; //explicit cast to string...
        }

        return implode($separator, $values);
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

    public function asset($path)
    {
        return '/'.$path;
    }

    public function jaNee($value)
    {
        return $value ? 'Ja' : 'Nee';
    }

    public function postvoorkeur($value)
    {
        return ($value ? 'Geen' : 'Wel').' post';
    }

    public function emailvoorkeur($value)
    {
        return ($value ? 'Geen' : 'Wel').' e-mail';
    }

    public function telefoonvoorkeur($value)
    {
        return ($value ? 'Geen' : 'Wel').' telefonisch contact';
    }

    public function try($value)
    {
        try {
            switch (true) {
                case is_bool($value):
                    return (string) $value ? 1 : 0;
                case $value instanceof \DateTime:
                    return $value->format('d-m-Y');
                default:
                    return (string) $value;
            }
        } catch (Error $e) {
            return '';
        }
    }

    /**
     * Like default date filter, but prints nothing if no date is provided
     * (instead of printing todays date).
     */
    public function ifDate(Environment $env, $date, $format = null, $timezone = null)
    {
        if ($date) {
            return twig_date_format_filter($env, $date, $format, $timezone);
        }

        return '';
    }


    public function sleep($length=1)
    {
        sleep($length);
    }
}

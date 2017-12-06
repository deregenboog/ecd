<?php
/**
 * TwigView Filters for CakePHP.
 *
 * - TextHelper -
 *
 * @version 0.7.rock-lobster
 *
 * @author Kjell Bublitz <m3nt0r.de@gmail.com>
 * @license MIT License
 */
App::import('Helper', 'Text');

/**
 * TextHelper - Extension for Filterset.
 *
 * @author Kjell Bublitz
 */
class Twig_Extension_Text extends Twig_Extension
{
    public function getName()
    {
        return 'TextHelper';
    }

    public function getFilters()
    {
        return [
            'stripLinks' => new Twig_Filter_Function('TwigView_Filter_Text::stripLinks'),
            'autoLink' => new Twig_Filter_Function('TwigView_Filter_Text::autoLink'),
            'truncate' => new Twig_Filter_Function('TwigView_Filter_Text::truncate'),
            'excerpt' => new Twig_Filter_Function('TwigView_Filter_Text::excerpt'),
            'highlight' => new Twig_Filter_Function('TwigView_Filter_Text::highlight'),
        ];
    }
}
TwigView::registerExtension(__FILE__, 'Twig_Extension_Text');

/**
 * TextHelper - Filter Set.
 *
 * @author Kjell Bublitz
 */
class TwigView_Filter_Text extends TwigView_Extension
{
    /**
     * TextHelper::stripLinks.
     *
     * @param string $var
     *
     * @author Kjell Bublitz
     */
    public static function stripLinks($var)
    {
        return self::helperObject('TextHelper')->stripLinks($var);
    }

    /**
     * TextHelper::autoLink.
     *
     * @param string $var
     *
     * @author Kjell Bublitz
     */
    public static function autoLink($var)
    {
        return self::helperObject('TextHelper')->autoLink($var);
    }

    /**
     * TextHelper::truncate.
     *
     * @param string $var
     * @param int    $length  length of returned string, including ellipsis
     * @param array  $options an array of html attributes and options
     *
     * @author Kjell Bublitz
     */
    public static function truncate($var, $length = 100, $options = [])
    {
        return self::helperObject('TextHelper')->truncate($var, $length, $options);
    }

    /**
     * TextHelper::excerpt.
     *
     * @param string $var
     * @param string $phrase Phrase that will be searched for
     * @param int    $radius The amount of characters that will be returned on each side of the founded phrase
     * @param string $ending Ending that will be appended (default: '...')
     *
     * @author Kjell Bublitz
     */
    public static function excerpt($var, $phrase, $radius = 100, $ending = '...')
    {
        return self::helperObject('TextHelper')->excerpt($var, $phrase, $radius, $ending);
    }

    /**
     * TextHelper::highlight.
     *
     * @param string $var
     * @param string $phrase  The phrase that will be searched
     * @param array  $options an array of html attributes and options
     *
     * @author Kjell Bublitz
     */
    public static function highlight($var, $phrase, $options = [])
    {
        return self::helperObject('TextHelper')->highlight($var, $phrase, $options);
    }
}

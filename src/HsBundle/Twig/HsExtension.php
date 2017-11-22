<?php

namespace HsBundle\Twig;

use HsBundle\Entity\Klus;

class HsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('status_color', [$this, 'statusColorFilter'], ['is_safe' => ['html']]),
        ];
    }

    public function statusColorFilter($value)
    {
        switch ($value) {
            case Klus::STATUS_OPENSTAAND:
                $color = 'green';
                break;
            case Klus::STATUS_IN_BEHANDELING:
                $color = 'blue';
                break;
            case Klus::STATUS_ON_HOLD:
                $color = 'orange';
                break;
            case Klus::STATUS_AFGEROND:
                $color = 'red';
                break;
            default:
                return $value;
        }

        return sprintf('<span style="color: %s;">%s</span>', $color, $value);
    }
}

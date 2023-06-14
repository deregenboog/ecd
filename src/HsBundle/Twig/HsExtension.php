<?php

namespace HsBundle\Twig;

use HsBundle\Entity\Klus;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('status_color', [$this, 'statusColorFilter'], ['is_safe' => ['html']]),
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
                $color = 'gray';
                break;
            case Klus::STATUS_GEANNULEERD:
                $color = 'red';
                break;
            default:
                return $value;
        }

        return sprintf('<span style="color: %s;">%s</span>', $color, $value);
    }

    public function getTests(): array
    {
        return array (
            new TwigFilter("instanceof",array($this,"isInstanceOf")),
        );
    }

    public function isInstanceOf($var,$instance) {
        $reflectionClass = new \ReflectionClass($instance);
        return $reflectionClass->isInstance($var);
    }
}

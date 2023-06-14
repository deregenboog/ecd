<?php

namespace AppBundle\Service;

use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Iterable_;

class ECDHelper
{

    /**
     * @param $value
     * @param $allRows
     * @param $invert If true, returns 'alles behalve'. If false, it shows list of $value.
     * @param $message
     * @return string
     *
     * Checks if $value contains all the rows of an entity/collection. If so, 'fold' back to 'Alle <entityName>'
     */
    public function filterAllRows(iterable $value, iterable $allRows, bool $invert = true): string
    {
        if(count($value)<1 || !is_iterable($value)) return "";

        if(!is_array($value)) $value = $value->toArray();
        $d = array_diff($allRows,$value);

        if( (count($d) > count($allRows)/2) && $invert) return implode(", ",$value);
        if(count($d) > 0) return 'Alles behalve: '.implode(", ",$d);

        return "Alle ".$this->getClassShortnameFromObject($value[0])."(s)";
    }

    public function getClassShortnameFromObject(object $object): string
    {
        return (new \ReflectionClass($object))->getShortName();

    }

}
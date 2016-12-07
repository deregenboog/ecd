<?php

class MedewerkerHelper extends AppHelper
{
    public function htmlList($entities, array $medewerkers, $onEmpty = '', $fieldName = 'medewerker_id')
    {
        if (key_exists($fieldName, $entities)) {
            $entities = [$entities];
        }

        $list = [];
        foreach ($entities as $entity) {
            $id = $entity[$fieldName];
            if (key_exists($id, $medewerkers)) {
                $list[$id] = $medewerkers[$id];
            }
        }

        if (count($list) === 0) {
            return $onEmpty;
        }

        $output = '<ul>';
        foreach ($list as $item) {
            $output .= '<li>'.$item.'</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function csList($entities, array $medewerkers, $fieldName = 'medewerker_id')
    {
        if (key_exists($fieldName, $entities)) {
            $entities = [$entities];
        }

        $list = [];
        foreach ($entities as $entity) {
            $id = $entity[$fieldName];
            if (key_exists($id, $medewerkers)) {
                $list[$id] = $medewerkers[$id];
            }
        }

        return implode(', ', $list);
    }
}

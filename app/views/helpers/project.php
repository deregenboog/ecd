<?php

class ProjectHelper extends AppHelper
{
    public function htmlList($entities, array $projecten, $fieldName = 'project_id')
    {
        if (key_exists($fieldName, $entities)) {
            $entities = [$entities];
        }

        $list = [];
        foreach ($entities as $entity) {
            $id = $entity[$fieldName];
            $list[$id] = $projecten[$id];
        }

        $output = '<ul>';
        foreach ($list as $item) {
            $output .= '<li>'.$item.'</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function csList($entities, array $projecten, $fieldName = 'project_id')
    {
        if (key_exists($fieldName, $entities)) {
            $entities = [$entities];
        }

        $list = [];
        foreach ($entities as $entity) {
            $id = $entity[$fieldName];
            $list[$id] = $projecten[$id];
        }

        return implode(', ', $list);
    }
}

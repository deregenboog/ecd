<?php

class Inventarisatie extends AppModel
{
    public $name = 'Inventarisatie';
    public $displayField = 'titel';
    public $actsAs = ['Tree'];

    /*
     * generates array representing the tree in a way that for every root all
     * children are subarrays of the same level
     * $fields - attributes of (children) nodes to be retrieved from DB
    */
    public function getTree($fields = [])
    {
        //default node fields to be read form DB
        if (empty($fields)) {
            $fields = ['id', 'order', 'type', 'titel', 'actie', 'depth'];
        }

        //getting all the roots
        $roots = $this->find('all', [
            'conditions' => ['parent_id' => null],
            'fields' => ['id', 'titel'],
            'order' => 'order ASC',
        ]);

        $tree = [];
        //for every root collect the children
        foreach ($roots as &$root) {
            $rootId = &$root['Inventarisatie']['id'];
            $tree[$rootId] = $this->children($rootId, false, $fields);
            $tree[$rootId]['rootName'] = &$root['Inventarisatie']['titel'];
        }

        return $tree;
    }

    /*
     * returns depth (length of the path to the root) of a node
     * (0 for root)
     */
    public function getDepth($id)
    {
        return count($path = $this->getpath($id, ['id'])) - 1;
    }
}

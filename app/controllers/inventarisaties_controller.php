<?php

class InventarisatiesController extends AppController
{
    public $name = 'Inventarisaties';
    public $uses = ['Inventarisatie'];

    public function index()
    {
        $tree = $this->Inventarisatie->generatetreelist(null, null, '{n}.Inventarisatie.depth', '--');

        $this->set(compact('tree'));
        $this->set('inventarisaties', $this->paginate());
    }

    /*
     * REBUILDING THE TREE STRUCTURE
     * run this function anytime there have been changes to the inv. tree
     * espacially on node addition, removal and reordering
    */
    public function reorganiseTree()
    {
        $this->Inventarisatie->recover();
        debug($this->Inventarisatie->reorder(['field' => 'order',
            'order' => 'ASC', 'verify' => true, ]));
        $nodes = $this->Inventarisatie->find('all');
        foreach ($nodes as $addr => &$node) {
            $node['Inventarisatie']['depth'] = $this->Inventarisatie->getDepth(
                $node['Inventarisatie']['id']);
            $this->Inventarisatie->Behaviors->detach('Tree');
            $this->Inventarisatie->save($node);
            $this->Inventarisatie->Behaviors->attach('Tree');
        }
        debug($tree = $this->Inventarisatie->generatetreelist(null, null, '{n}.Inventarisatie.depth', '--'));
    }

    public function verifyTree()
    {
        if ($this->Inventarisatie->verify()) {
            $msg = 'Tree OK!';
        } else {
            $msg = 'Tree broken!';
        }
        debug($msg);
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid Inventarisatie', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('inventarisatie', $this->Inventarisatie->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Inventarisatie->create();
            if ($this->Inventarisatie->save($this->data)) {
                $this->flash(__('The Inventarisatie has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The Inventarisatie could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid Inventarisatie', true));
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->Inventarisatie->save($this->data)) {
                $this->flash(__('The Inventarisatie has been saved', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->flashError(__('The Inventarisatie could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Inventarisatie->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for Inventarisatie', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->Inventarisatie->delete($id)) {
            $this->flashError(__('Inventarisatie deleted', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('Inventarisatie was not deleted', true));
        $this->redirect(['action' => 'index']);
    }
}

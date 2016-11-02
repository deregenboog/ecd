<?php

class AwbzHoofdaannemersController extends AppController
{
    public $name = 'AwbzHoofdaannemers';
    public $uses = array('AwbzHoofdaannemer');

    public function add($klant_id = null)
    {
        if (!$klant_id) {
            $this->flashError(__('Invalid klant', true));
            $this->render('/elements/ajax_awbz_hoofdannemer', 'ajax');
            return;
        }

        if (!empty($this->data)) {
            $this->AwbzHoofdaannemer->Klant->recursive = -1;
            $klant = $this->AwbzHoofdaannemer->Klant->read('id', $klant_id);

            if (!empty($klant)) {
                $this->data['AwbzHoofdaannemer']['klant_id'] = $klant_id;

                $this->AwbzHoofdaannemer->recursive = -1;
                $previous = $this->AwbzHoofdaannemer->find('first', array(
                    'conditions' => array(
                        'AwbzHoofdaannemer.klant_id' => $klant_id,
                        'AwbzHoofdaannemer.einddatum' => null,
                    ),
                    'order' => 'AwbzHoofdaannemer.begindatum DESC',
                ));

                if (!$this->AwbzHoofdaannemer->save($this->data)) {
                    $this->flashError(__('Couldn\'t save', true));
                } else {
                    if (!empty($previous)) {
                        $the_date =
                            $this->data['AwbzHoofdaannemer']['begindatum'];
                        $previous_begin =
                                $previous['AwbzHoofdaannemer']['begindatum'];
                        if (strtotime($the_date) > strtotime($previous_begin)) {
                            $previous['AwbzHoofdaannemer']['einddatum'] =
                                $the_date;
                            $this->AwbzHoofdaannemer->save($previous);
                        }
                    }
                    
                    $this->flash(__('Saved', true));
                }
            } else {
                $this->flashError(__('Invalid klant', true));
                $this->render('/elements/ajax_awbz_hoofdaannemer', 'ajax');
                return;
            }
        } else {
            $this->flashError(__('Nothing to save', true));
        }

        $this->_render_ajax_view($klant_id);
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid awbz Hoofdaannemer', true));
            $this->redirect(array(
                'controller' => 'awbz',
                'action' => 'index',
            ));
        }
        
        if (!empty($this->data)) {
            if ($this->AwbzHoofdaannemer->save($this->data)) {
                $this->flashError(__('The awbz Hoofdaannemer has been saved', true));
                $this->redirect(array(
                    'controller' => 'awbz',
                    'action' => 'view',
                    $this->data['AwbzHoofdaannemer']['klant_id'],
                ));
            } else {
                $this->flashError(__('The awbz Hoofdaannemer could not be saved. Please, try again.', true));
            }
        }
        
        if (empty($this->data)) {
            $this->data = $this->AwbzHoofdaannemer->read(null, $id);
        }
        
        $klant_id = $this->data['AwbzHoofdaannemer']['klant_id'];
        
        $klant = $this->AwbzHoofdaannemer->Klant->find('first', array(
            'conditions' => array('Klant.id' => $klant_id),
        ));
        
        $hoofdaannemers = $this->AwbzHoofdaannemer->Hoofdaannemer->find('list');
        $this->set(compact('klant', 'hoofdaannemers'));
    }

    public function ajax_delete($id = null, $klant_id = null)
    {
        if (!$klant_id) {
            $this->flashError(__('Invalid klant', true));
            $this->render('/elements/ajax_awbz_hoofdannemer', 'ajax');
            return;
        }

        if (!$id) {
            $this->flashError(__('Invalid id for awbz Hoofdaannemer', true));
        }
        
        if ($this->AwbzHoofdaannemer->delete($id)) {
            $this->flashError(__('Awbz Hoofdaannemer deleted', true));
        } else {
            $this->flashError(__('Awbz Hoofdaannemer was not deleted', true));
        }
        
        $this->_render_ajax_view($klant_id);
    }

    public function _render_ajax_view($klant_id)
    {
        $klant = $this->AwbzHoofdaannemer->Klant->find('first', array(
            'conditions' => array('Klant.id' => $klant_id),
            'contain' => array('AwbzHoofdaannemer' => array('Hoofdaannemer')),
        ));
        
        $hoofdaannemers = $this->AwbzHoofdaannemer->Hoofdaannemer->find('list');

        $this->set(compact('klant', 'hoofdaannemers'));

        $this->render('/elements/ajax_awbz_hoofdaannemers', 'ajax');
    }
}

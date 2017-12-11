<?php

class AwbzIndicatiesController extends AppController
{
    public $name = 'AwbzIndicaties';
    public $uses = ['AwbzIndicatie'];

    public function add($klant_id = null)
    {
        if (!$klant_id) {
            $this->flashError(__('Invalid klant', true));
            $this->render('/elements/ajax_awbz_indicaties', 'ajax');

            return;
        }

        if (!empty($this->data)) {
            $this->AwbzIndicatie->Klant->recursive = -1;
            $klant = $this->AwbzIndicatie->Klant->read('id', $klant_id);

            if (!empty($klant)) {
                $this->data['AwbzIndicatie']['klant_id'] = $klant_id;

                if (!$this->AwbzIndicatie->save($this->data)) {
                    $this->flashError(__('The AWBZ Indicatie could not be saved. Please, try again.', true));
                } else {
                    $this->flash(__('The AWBZ Indicatie has been saved', true));
                }
            } else {
                $this->flashError(__('Invalid klant', true));
                $this->render('/elements/ajax_awbz_indicaties', 'ajax');

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
            $this->flashError(__('Invalid awbz Indicatie', true));
            $this->redirect([
                'controller' => 'awbz',
                'action' => 'index',
            ]);
        }

        if (!empty($this->data)) {
            if ($this->AwbzIndicatie->save($this->data)) {
                $this->flash(__('The AWBZ Indicatie has been saved', true));

                $this->redirect([
                    'controller' => 'awbz',
                    'action' => 'view',
                    $this->data['AwbzIndicatie']['klant_id'],
                ]);
            } else {
                $this->flashError(__('The AWBZ Indicatie could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->AwbzIndicatie->read(null, $id);
        }

        $klant_id = $this->data['AwbzIndicatie']['klant_id'];
        $klant = $this->AwbzIndicatie->Klant->find('first', [
            'conditions' => ['Klant.id' => $klant_id],
        ]);

        $this->set(compact('klant'));
    }

    public function ajax_delete($id = null, $klant_id = null)
    {
        if (!$klant_id) {
            $this->flashError(__('Invalid klant', true));
            $this->render('/elements/ajax_awbz_indicaties', 'ajax');

            return;
        }

        if (!$id) {
            $this->flashError(__('Invalid id for awbz Indicatie', true));
        }

        if ($this->AwbzIndicatie->delete($id)) {
            $this->flashError(__('AWBZ Indicatie deleted', true));
        } else {
            $this->flashError(__('AWBZ Indicatie was not deleted', true));
        }

        $this->_render_ajax_view($klant_id);
    }

    public function _render_ajax_view($klant_id)
    {
        $klant = $this->AwbzIndicatie->Klant->find('first', [
            'conditions' => ['Klant.id' => $klant_id],
            'contain' => [
                'AwbzIndicatie' => ['Hoofdaannemer'],
            ],
        ]);

        $hoofdaannemers = $this->AwbzIndicatie->Hoofdaannemer->find('list');

        $this->set(compact('klant', 'hoofdaannemers'));

        $this->render('/elements/ajax_awbz_indicaties', 'ajax');
    }

    public function jsonIndicationRequest($ind_id = null)
    {
        if (!$ind_id) {
            $jsonVar = ['aangevraagd' => false, 'error' => 'bad_id'];
        } else {
            $this->AwbzIndicatie->recursive = 0;
            if (!$this->AwbzIndicatie->read('id', $ind_id)) {
                $jsonVar = [
                    'aangevraagd_id' => false,
                    'error' => 'indicatie_not_found',
                ];
            } else {
                $this->AwbzIndicatie->set('aangevraagd_id',
                    $this->Session->read('Auth.Medewerker.id'));
                $this->AwbzIndicatie->set('aangevraagd_datum', date('Y-m-d'));
                if ($this->AwbzIndicatie->save()) {
                    App::import('Helper', 'Date');
                    $date = new DateHelper();
                    $jsonVar = [
                        'aangevraagd' => true,
                        'naam' => $this->_getLoggedinUserDisplayName(),
                        'date' => $date->show(date('Y-m-d')), ];
                } else {
                    $jsonVar = [
                        'aangevraagd' => false,
                        'error' => 'coulndt_save',
                    ];
                }
            }
        }

        $this->set(compact('jsonVar'));
        $this->render('/elements/json', 'ajax');
    }

    public function jsonDeleteIndicationRequest($ind_id = null)
    {
        if (!$ind_id) {
            $jsonVar = ['aangevraagd' => false, 'error' => 'bad_id'];
        } else {
            $this->AwbzIndicatie->recursive = 0;
            if (!$this->AwbzIndicatie->read('id', $ind_id)) {
                $jsonVar = [
                    'success' => false,
                    'error' => 'indicatie_not_found',
                ];
            } else {
                $this->AwbzIndicatie->set('aangevraagd_niet', 1);

                if ($this->AwbzIndicatie->save()) {
                    App::import('Helper', 'Date');
                    $date = new DateHelper();
                    $jsonVar = ['success' => true];
                } else {
                    $jsonVar = [
                        'success' => false,
                        'error' => 'coulndt_save',
                    ];
                }
            }
        }

        $this->set(compact('jsonVar'));
        $this->render('/elements/json', 'ajax');
    }

    private function _getLoggedinUserDisplayName()
    {
        $user = $this->Session->read('Auth.Medewerker.LdapUser.displayname');
        if (!$user) {
            $user = $this->Session->read('Auth.Medewerker.LdapUser.cn');
        }
        if (!$user) {
            $user = $this->Session->read('Auth.Medewerker.LdapUser.givenname');
        }

        return $user;
    }
}

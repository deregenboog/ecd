<?php

class VerslagenController extends AppController
{
    public $name = 'Verslagen';

    public $components = array(
            'ComponentLoader',
    );

    public function index()
    {
        $laaste_rapportage = null;

        if (is_array($this->data['verslagen']['laatste_rapportage'])) {
            if (!empty($this->data['verslagen']['laatste_rapportage']['year']) &&
              !empty($this->data['verslagen']['laatste_rapportage']['year']) &&
              !empty($this->data['verslagen']['laatste_rapportage']['day'])) {
                $this->data['verslagen']['laatste_rapportage'] = $this->data['verslagen']['laatste_rapportage']['year'].'-'.$this->data['verslagen']['laatste_rapportage']['month'].'-'.$this->data['verslagen']['laatste_rapportage']['day'];
                $laaste_rapportage = $this->data['verslagen']['laatste_rapportage'];
            } else {
                unset($this->data['verslagen']['laatste_rapportage']);
            }
        }

        if (isset($this->params['named']['verslagen.laatste_rapportage'])) {
            $laaste_rapportage = $this->params['named']['verslagen.laatste_rapportage'];
        }

        $this->ComponentLoader->load('Filter');

        $this->paginate = array(
                'contain' => array(
                    'LasteIntake' => array(
                        'fields' => array(
                            'locatie1_id',
                            'locatie2_id',
                            'locatie3_id',
                            'datum_intake',
                        ),
                    ),
                    'Verslag' => array('datum'),
                    'Intake' => array(
                        'fields' => array(
                            'datum_intake',
                            'id',
                        ),
                    ),
                    'Geslacht',
                ),
        );

        if (!empty($laaste_rapportage)) {
            $table = 'select klant_id, max(datum) as laatste_rapportage from verslagen group by klant_id';

            $joins = array(
                array(
                   'table' => "( {$table} )",
                       'alias' => 'verslagen',
                       'type' => 'INNER',
                       'conditions' => array(
                       'Klant.id = verslagen.klant_id',
                   ),
                ),
            );

            $this->paginate['joins'] = $joins;
        }

        $this->Medewerker = ClassRegistry::init('Medewerker');

        $medewerkers = array('' => '');
        $medewerkers += $this->Medewerker->find('list');

        $klanten = $this->paginate('Klant', $this->Filter->filterData);
        foreach ($klanten as $key => $klant) {
            $klanten[$key]['Klant']['laatste_verslag_datum'] = null;
            $l = count($klant['Verslag']);

            if ($l > 0) {
                $klanten[$key]['Klant']['laatste_verslag_datum'] = $klant['Verslag'][$l - 1]['datum'];
            }
        }

        $klanten = $this->Verslag->Klant->LasteIntake->completeKlantenIntakesWithLocationNames($klanten);

        $rowOnclickUrl = array(
                'controller' => 'verslagen',
                'action' => 'view',
        );

        $this->set(compact('klanten', 'rowOnclickUrl', 'medewerkers'));
        $this->set('maatschappelijkwerk', true);

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/klantenlijst', 'ajax');
        }
    }

    public function view($klant_id = null)
    {
        $this->ComponentLoader->load('Filter');

        if (!$klant_id) {
            $this->flashError(__('Invalid klant', true));

            $this->redirect(array(
                    'action' => 'index',
            ));
        }

        $contain = $this->Verslag->Klant->contain;

        $contain['Document'] = array(
            'conditions' => array(
                'Document.group' => Attachment::GROUP_MW,
                'is_active' => 1,
            ),
        );

        $klant = $this->Verslag->Klant->find('first', array(
            'conditions' => array(
                'Klant.id' => $klant_id,
            ),
            'contain' => $contain,
        ));

        $persoon = $this->Verslag->Klant->getAllById($klant_id);
        $diensten = $this->Verslag->Klant->diensten($persoon);

        $verslaginfo = $this->Verslag->Klant->Verslaginfo->find('first', array(
            'conditions' => array(
                'Verslaginfo.klant_id' => $klant_id,
            ),
        ));

        $medewerkers = $this->Verslag->Medewerker->find('list');

        $verslagen = $this->Verslag->find('all', array(
            'conditions' => array(
                'Verslag.klant_id' => $klant_id,
            ),
            'contain' => $this->Verslag->contain,
            'order' => 'Verslag.datum DESC, created DESC',
        ));

        foreach ($verslagen as &$verslag) {
            $this->Verslag->InventarisatiesVerslagen->getInvPaths($verslag);
        }

        if (!$klant) {
            $this->flashError(__('Invalid klant', true));

            $this->redirect(array(
                    'action' => 'index',
            ));
        }

        $this->set(compact('klant', 'verslagen', 'verslaginfo', 'medewerkers', 'persoon', 'diensten'));
    }

    public function add($klant_id = null)
    {
        if (!$klant_id && !empty($this->data['Verslag']['klant_id'])) {
            $klant_id = $this->data['Verslag']['klant_id'];
        }

        $this->ComponentLoader->load('Filter');

        if (!empty($this->data)) {
            $this->Verslag->create();

            foreach (array(
                    'advocaat',
                    'contact',
                    'opmerking',
            ) as $field) {
                if (array_key_exists($field, $this->data['Verslag']) && !empty($this->data['Verslag'][$field])) {
                    $this->data['Verslag'][$field] = str_replace('\n', '<br/>',
                        $this->data['Verslag'][$field]);
                }
            }

            $this->Verslag->set($this->data);
            if ($this->Verslag->saveAll($this->data)) {
                $this->flash(__('The verslag has been saved', true));
                $this->redirect(array(
                    'action' => 'view',
                    $this->data['Verslag']['klant_id'],
                ));
            } else {
                $this->flashError(__('The verslag could not be saved. Please, try again.', true));
            }

            $intaker_id = $this->data['Verslag']['medewerker_id'];
        } else {
            $intaker_id = $this->Session->read('Auth.Medewerker.id');
        }

        if (empty($klant_id)) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(array(
                'action' => 'index',
            ));
        } else {
            $verslagen = $this->Verslag->find('all', array(
                'conditions' => array(
                    'Verslag.klant_id' => $klant_id,
                ),
                'contain' => $this->Verslag->contain,
                'order' => 'Verslag.datum DESC',
            ));

            foreach ($verslagen as &$verslag) {
                $this->Verslag->InventarisatiesVerslagen->getInvPaths($verslag);
            }

            $this->setMedewerkers();

            $klant_contain = $this->Verslag->Klant->contain;
            $klant = $this->Verslag->Klant->find('first', array(
                'conditions' => array(
                    'Klant.id' => $klant_id,
                ),
                'contain' => $klant_contain,
            ));

            if (!$klant) {
                $this->flashError(__('Client doesn\'t exist!', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $klant_id = $klant['Klant']['id'];
            }
        }

        $inventarisaties = $this->Verslag->InventarisatiesVerslagen->Inventarisatie->getTree();
        $doorverwijzers = $this->Verslag->InventarisatiesVerslagen->Doorverwijzer->getLists();
        $locaties = $this->Verslag->Locatie->find('list');
        $contactsoorts = $this->Verslag->Contactsoort->find('list');

        $this->set(compact('inventarisaties', 'doorverwijzers', 'klant_id',
                'verslagen', 'klant', 'locaties', 'locatie_id',
                'intaker_id', 'verslaginfo', 'contactsoorts'
        ));
    }

    public function edit($verslag_id = null)
    {
        if (!$verslag_id && !empty($this->data['Verslag']['id'])) {
            $verslag_id = $this->data['Verslag']['id'];
        }

        $verslag = $this->Verslag->find('first', array('conditions' => array('Verslag.id' => $verslag_id)));
        $inventarisaties = $this->Verslag->InventarisatiesVerslagen->Inventarisatie->getTree();

        $this->ComponentLoader->load('Filter');

        if (!empty($this->data)) {
            foreach (array(
                    'advocaat',
                    'contact',
                    'opmerking',
            ) as $field) {
                if (array_key_exists($field, $this->data['Verslag']) && !empty($this->data['Verslag'][$field])) {
                    $this->data['Verslag'][$field] = str_replace('\n', '<br/>',
                        $this->data['Verslag'][$field]);
                }
            }

            $this->Verslag->set($this->data);

            if ($this->Verslag->saveAll($this->data)) {
                $idsToRemove = Set::classicExtract($verslag['InventarisatiesVerslagen'], '{n}.id');

                $this->Verslag->InventarisatiesVerslagen->deleteAll(array(
                    'InventarisatiesVerslagen.id' => $idsToRemove,
                ));

                $this->Session->setFlash(__('The verslag has been saved', true));
                $this->redirect(array(
                    'action' => 'view',
                    $this->data['Verslag']['klant_id'],
                ));
            } else {
                $this->Session->setFlash(__('The verslag could not be saved. Please, try again.', true));
            }

            $intaker_id = $this->data['Verslag']['medewerker_id'];
        } else {
            $intaker_id = $this->Session->read('Auth.Medewerker.id');
            $categories = [];

            foreach ($inventarisaties as $catId => $group) {
                foreach ($group as $key => $inv) {
                    if (!is_array($inv)) {
                        continue;
                    }

                    $invId = $inv['Inventarisatie']['id'];
                    $categories[$invId] = $catId;
                }
            }

            $ivs = $verslag['InventarisatiesVerslagen'];
            $verslag['InventarisatiesVerslagen'] = [];

            foreach ($ivs as $iv) {
                $iid = $iv['inventarisatie_id'];
                $verslag['InventarisatiesVerslagen'][$categories[$iid]] =
                    $iv;
            }

            $this->data = $verslag;
        }

        $klant_id = $verslag['Verslag']['klant_id'];

        $verslagen = $this->Verslag->find('all', array(
            'conditions' => array(
                'Verslag.klant_id' => $klant_id,
            ),
            'contain' => $this->Verslag->contain,
            'order' => 'Verslag.datum DESC',
        ));

        foreach ($verslagen as &$v) {
            $this->Verslag->InventarisatiesVerslagen->getInvPaths($v);
        }

        $medewerker_id = null;

        if (isset($verslag['Verslag']['medewerker_id'])) {
            $medewerker_id = $verslag['Verslag']['medewerker_id'];
        }

        $this->setMedewerkers($medewerker_id);

        $klant_contain = $this->Verslag->Klant->contain;
        $klant = $this->Verslag->Klant->find('first', array(
            'conditions' => array(
                'Klant.id' => $klant_id,
            ),
            'contain' => $klant_contain,
        ));

        if (!$klant) {
            $this->Session->setFlash(__('Client doesn\'t exist!', true));
            $this->redirect(array('action' => 'index'));
        } else {
            $klant_id = $klant['Klant']['id'];
        }

        $doorverwijzers = $this->Verslag->InventarisatiesVerslagen->Doorverwijzer->getLists();
        $locaties = $this->Verslag->Locatie->find('list');
        $contactsoorts = $this->Verslag->Contactsoort->find('list');

        $this->set(compact('inventarisaties', 'doorverwijzers', 'klant_id',
            'verslagen', 'klant', 'locaties', 'locatie_id',
            'intaker_id', 'verslaginfo', 'contactsoorts'
        ));
    }

    public function rapportage()
    {
        $this->ComponentLoader->load('Filter');

        $inventarisaties = $this->Verslag->InventarisatiesVerslagen->Inventarisatie->getTree(array(
            'id',
            'actie',
            'titel',
            'depth',
        ));

        $inventarisaties_couts = $this->Verslag->InventarisatiesVerslagen->getInventarisatiesCount($inventarisaties);

        $medewerkers = $this->Verslag->Medewerker->find('list');

        $this->set(compact('inventarisaties', 'medewerkers'));
    }

    public function delete($id = null)
    {
        $this->ComponentLoader->load('Filter');

        if (!$id) {
            $this->flashError(__('Invalid id for verslag', true));
            $this->redirect(array(
                    'action' => 'index',
            ));
        }

        if ($this->Verslag->delete($id)) {
            $this->flashError(__('Verslag deleted', true));
            $this->redirect(array(
                    'action' => 'index',
            ));
        }

        $this->flashError(__('Verslag was not deleted', true));

        $this->redirect(array('action' => 'index'));
    }

    public function verslaginfo($klantId = null)
    {
        if (empty($klantId)) {
            $this->flashError(__('Invalid klant', true));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {
            if (!isset($this->data['Verslaginfo']['id'])) {
                $this->Verslag->Klant->Verslaginfo->create();
            }

            $result = $this->Verslag->Klant->Verslaginfo->save($this->data);

            if ($result) {
                $this->flash(__('The verslag has been saved', true));

                $this->redirect(array(
                    'action' => 'view',
                    $result['Verslaginfo']['klant_id'],
                ));
            }
        } else {
            $this->data = $this->Verslag->Klant->Verslaginfo->find('first', array(
                'conditions' => array(
                    'Verslaginfo.klant_id' => $klantId,
                ),
            ));
        }

        $klant = $this->Verslag->Klant->find('first', array(
            'conditions' => array('Klant.id' => $klantId),
        ));

        $medewerker_ids = [];

        if (isset($this->data['Verslaginfo']['trajectbegeleider_id'])) {
            $medewerker_ids[] = $this->data['Verslaginfo']['trajectbegeleider_id'];
        }

        if (isset($this->data['Verslaginfo']['casemanager_id'])) {
            $medewerker_ids[] = $this->data['Verslaginfo']['casemanager_id'];
        }

        $medewerkers = $this->setMedewerkers($medewerker_ids);

        $this->set(compact('klant'));
    }
}

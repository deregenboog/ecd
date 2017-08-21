<?php

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;

class GroepsactiviteitenController extends AppController
{
    public $name = 'Groepsactiviteiten';

    private $enabledFilters = [
        'klant' => ['id', 'naam', 'geboortedatumRange'],
        'vrijwilliger' => ['id', 'naam', 'geboortedatumRange'],
        'medewerker',
        'afsluitdatum',
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.geboortedatum',
        'vrijwilliger.id',
        'vrijwilliger.achternaam',
        'vrijwilliger.geboortedatum',
        'medewerker.achternaam',
        'intake.afsluitdatum',
    ];

    public $components = [
            'ComponentLoader',
    ];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private function setmetadata($persoon_model, $id)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);
        $geslachten = $this->{$persoon_model}->Geslacht->find('list');
        $landen = $this->{$persoon_model}->Geboorteland->find('list');
        $nationaliteiten = $this->{$persoon_model}->Nationaliteit->find('list');

        $this->setMedewerkers();
        $werkgebieden = Configure::read('Werkgebieden');

        if (!isset($this->GroepsactiviteitenIntake)) {
            $this->loadModel('GroepsactiviteitenIntake');
        }

        $intake = $this->GroepsactiviteitenIntake->find('first', [
            'conditions' => [
                'model' => $persoon_model,
                'foreign_key' => $id,
            ],
        ]);

        $is_afgesloten = false;

        if (!empty($intake['GroepsactiviteitenIntake']['afsluitdatum'])) {
            $is_afgesloten = true;
        }

        $this->set(compact('id', 'geslachten', 'is_afgesloten', 'intake', 'landen', 'nationaliteiten', 'medewerkers', 'werkgebieden'));
    }

    // Makes sure there is always an intake even if the values are empty
    private function add_to_intake($persoon_model, $id, $data = null)
    {
        if (!isset($this->GroepsactiviteitenIntake)) {
            $this->loadModel('GroepsactiviteitenIntake');
        }

        $intake = $this->GroepsactiviteitenIntake->find('first', [
            'conditions' => [
                'model' => $persoon_model,
                'foreign_key' => $id,
            ],
        ]);

        if (empty($intake) && empty($data)) {
            $data = [
                'GroepsactiviteitenIntake' => [
                    'model' => $persoon_model,
                    'foreign_key' => $id,
                ],
            ];
        }

        if (!empty($data)) {
            if (!empty($intake)) {
                $data['GroepsactiviteitenIntake']['id'] = $intake['GroepsactiviteitenIntake']['id'];
            }

            $data['GroepsactiviteitenIntake']['model'] = $persoon_model;
            $data['GroepsactiviteitenIntake']['foreign_key'] = $id;

            $this->GroepsactiviteitenIntake->create();
            if (!$this->GroepsactiviteitenIntake->save($data)) {
                return false;
            }

            $data['GroepsactiviteitenIntake']['id'] = $this->GroepsactiviteitenIntake->id;
            $intake = $data;
        }

        return $intake;
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Groepsactiviteit->create();

            if ($this->Groepsactiviteit->save($this->data)) {
                $this->Session->setFlash(__('De activiteit is opgeslagen', true));
                $this->redirect(['action' => 'planning']);
            } else {
                $this->Session->setFlash(__('Activiteit kan niet worden opgeslagen', true));
            }
        }

        $groepsactiviteitengroepen = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $this->set('groepsactiviteitengroepen', $groepsactiviteitengroepen);
    }

    public function delete($id)
    {
        $groeps_activiteit = $this->Groepsactiviteit->getById($id);
        if (!empty($groeps_activiteit)) {
            $this->Groepsactiviteit->delete($id);
        }
        $this->redirect(['action' => 'planning', $groeps_activiteit['groepsactiviteiten_groep_id']]);
    }

    public function edit($id)
    {
        if (!empty($this->data)) {
            $this->data['Groepsactiviteit']['id'] = $id;

            if ($this->Groepsactiviteit->save($this->data)) {
                $this->Session->setFlash(__('De activiteit is opgeslagen', true));
                $this->redirect(['action' => 'planning']);
            } else {
                $this->Session->setFlash(__('Activiteit kan niet worden opgeslagen', true));
            }
        } else {
            $this->Groepsactiviteit->recursive = -1;
            $this->data = $this->Groepsactiviteit->read(null, $id);
        }
        $groepsactiviteitengroepen = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $this->set(compact('id', 'groepsactiviteitengroepen'));
    }

    public function view($persoon_model = 'Klant', $id = null)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);
        if (!$id) {
            $this->Session->setFlash(__('Invalid persoon', true));

            return $this->redirect(['controller' => 'groepsacticiteiten_klanten', 'action' => 'index']);
        }

        if ($persoon_model == 'Klant') {
            $this->redirect(['action' => 'intakes', $persoon_model, $id]);
        } else {
            $this->redirect(['action' => 'verslagen', $persoon_model, $id]);
        }
    }

    public function open_groep($persoon_model, $id, $groepsactiviteiten_groep_id)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);
        if (!$id) {
            $this->Session->setFlash(__('Invalid persoon', true));

            return $this->redirect(['controller' => 'groepsacticiteiten_klanten', 'action' => 'index']);
        }
        $persoon_groepsactiviteiten_groepen = 'GroepsactiviteitenGroepen'.$persoon_model;

        $this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->create();
        $validation_error = false;
        $data = [
            $persoon_groepsactiviteiten_groepen => [
                'id' => $groepsactiviteiten_groep_id,
                'einddatum' => null,
                'groepsactiviteiten_reden_id' => null,
            ],
        ];
        $this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->validate = [];
        if (!$this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->save($data)) {
            $validation_error = true;
        }

        $this->redirect(['controller' => 'groepsactiviteiten', 'action' => 'groepen', $persoon_model, $id]);
    }

    public function opnieuw_aanmelden($id)
    {
        $this->loadModel('GroepsactiviteitenIntake');

        $data = [
        'GroepsactiviteitenIntake' => [
                'id' => $id,
                'afsluitdatum' => null,
                'groepsactiviteiten_afsluiting_id' => null,
        ],
        ];

        $intake = $this->GroepsactiviteitenIntake->getById($id);
        if (empty($intake)) {
            $this->Session->setFlash(__('Error opnieuw_aanmelden', true));
            $this->redirect('/');
        }

        $this->GroepsactiviteitenIntake->validate = [];

        if (!$this->GroepsactiviteitenIntake->save($data)) {
            $this->Session->setFlash(__('Error opnieuw_aanmelden', true));
            $this->redirect('/');
        }

        $this->redirect(['action' => 'view', $intake['model'], $intake['foreign_key'], $id]);
    }

    public function afsluiting($persoon_model, $id)
    {
        $this->loadModel('GroepsactiviteitenAfsluiting');
        $this->loadModel('GroepsactiviteitenIntake');

        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);
        $persoon_groepsactiviteiten_groepen = 'GroepsactiviteitenGroepen'.$persoon_model;
        $persoon_id_field = "{$persoon_model}_id";

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid vrijwilliger', true));

            return $this->redirect(['controller' => 'groepsacticiteiten_klanten', 'action' => 'index']);
        }

        $intake = $this->add_to_intake($persoon_model, $id, $this->data);

        $groepsactiviteiten_intake_id = null;

        if (!empty($intake)) {
            $groepsactiviteiten_intake_id = $intake['GroepsactiviteitenIntake']['id'];
        }

        $persoon = $this->{$persoon_model}->getAllById($id);

        $diensten = [];
        if ($persoon_model == 'Klant') {
            $diensten = $this->Klant->diensten($persoon);
        }

        $has_active_groepen = false;

        $open = $this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->find('list', [
            'conditions' => [
                $persoon_id_field => $id,
                'einddatum' => null,
            ],
        ]);

        if (!empty($open)) {
            $has_active_groepen = true;
        }

        $groepsactiviteiten_afsluiting_list = $this->GroepsactiviteitenAfsluiting->get_groepsactiviteiten_afsluiting_list();
        $this->set(compact('persoon', 'has_active_groepen', 'groepsactiviteiten_intake_id', 'persoon_model', 'diensten', 'groepsactiviteiten_afsluiting_list'));

        $this->setMedewerkers();
        $this->setmetadata($persoon_model, $id);
        $this->render('view');
    }

    public function verslagen($persoon_model, $id)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid vrijwilliger', true));

            return $this->redirect(['controller' => 'groepsacticiteiten_klanten', 'action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->Vrijwilliger->save($this->data)) {
                $this->Session->setFlash(__('The vrijwilliger has been saved', true));
                $this->redirect(['action' => 'view', $id]);
            } else {
                $this->Session->setFlash(__('The vrijwilliger could not be saved. Please, try again.', true));
            }
        }

        $persoon = $this->{$persoon_model}->getAllById($id);
        $diensten = [];
        if ($persoon_model == 'Klant') {
            $diensten = $this->Klant->diensten($persoon);
        }

        $this->set(compact('persoon', 'persoon_model', 'diensten'));
        $this->setMedewerkers();
        $this->setmetadata($persoon_model, $id);
        $this->render('view');
    }

    public function intakes($persoon_model, $foreign_key)
    {
        $this->loadModel('ZrmReport');
        $persoon_model = $this->check_persoon_model($persoon_model);

        $this->loadModel($persoon_model);
        if (!empty($this->data)) {
            if (empty($this->data['GroepsactiviteitenIntake']['id'])) {
                $this->{$persoon_model}->GroepsactiviteitenIntake->create();
                $this->data['GroepsactiviteitenIntake']['model'] = $persoon_model;
                $this->data['GroepsactiviteitenIntake']['foreign_key'] = $foreign_key;
                $this->data['GroepsactiviteitenIntake']['medewerker_id'] = $this->Session->read('Auth.Medewerker.id');
            }

            $this->data['GroepsactiviteitenIntake']['gespreksverslag'] = htmlentities($this->data['GroepsactiviteitenIntake']['gespreksverslag']);
            $this->{$persoon_model}->begin();
            $saved = false;

            $retval = $this->{$persoon_model}->GroepsactiviteitenIntake->save($this->data);

            if ($retval) {
                if ($persoon_model == 'Klant') {
                    $this->data['ZrmReport']['model'] = 'GroepsactiviteitenIntake';

                    $this->data['ZrmReport']['foreign_key'] = $this->{$persoon_model}->GroepsactiviteitenIntake->id;
                    $this->data['ZrmReport']['klant_id'] = $foreign_key;

                    $this->ZrmReport->create();

                    if ($this->ZrmReport->save($this->data)) {
                        $saved = true;
                    }
                } else {
                    $saved = true;
                }
            }

            if ($saved) {
                $this->flash(__('De intake is opgeslagen', true));
                $this->{$persoon_model}->commit();
                $this->redirect(['action' => 'intakes', $persoon_model, $foreign_key]);
            } else {
                $this->flash(__('De intake is niet opgeslagen', true));
                $this->{$persoon_model}->rollback();
            }
        }

        $persoon = $this->{$persoon_model}->getAllById($foreign_key);

        $diensten = [];
        if ($persoon_model == 'Klant') {
            $diensten = $this->Klant->diensten($persoon);
        }

        if (!empty($persoon['GroepsactiviteitenIntake']['id']) && empty($this->data['ZrmReport'])) {
            $zrm = $this->ZrmReport->get_zrm_report('GroepsactiviteitenIntake',
                    $persoon['GroepsactiviteitenIntake']['id'],
                    $foreign_key);
            $this->data['ZrmReport'] = $zrm['ZrmReport'];
        }

        $zrm_data = $this->ZrmReport->zrm_data();
        $this->set(compact('persoon', 'persoon_model', 'zrm_data', 'diensten'));
        $this->setMedewerkers();
        $this->setmetadata($persoon_model, $foreign_key);
        $this->render('view');
    }

    private function check_persoon_model($persoon_model)
    {
        if ($persoon_model == 'Vrijwilliger') {
            return 'Vrijwilliger';
        }

        return 'Klant';
    }

    public function communcatie_settings($persoon_model, $id)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);
        $persoon_groepsactiviteiten_groepen = 'GroepsactiviteitenGroepen'.$persoon_model;
        $this->loadModel($persoon_groepsactiviteiten_groepen);

        if (!empty($this->data)) {
            $this->data[$persoon_groepsactiviteiten_groepen]['id'] = $id;
            $this->{$persoon_groepsactiviteiten_groepen}->create();
            if (!$this->{$persoon_groepsactiviteiten_groepen}->save($this->data[$persoon_groepsactiviteiten_groepen])) {
                if ($this->RequestHandler->isAjax()) {
                    $this->render('../elements/ajax_error', 'ajax');

                    return;
                }
            }
        }

        $groepsactiviteit = $this->{$persoon_groepsactiviteiten_groepen}->read(null, $id);
        $groepsactiviteit = $groepsactiviteit[$persoon_groepsactiviteiten_groepen];

        $this->set(compact('persoon_model', 'groepsactiviteit', 'persoon_groepsactiviteiten_groepen'));
        if ($this->RequestHandler->isAjax()) {
            $this->render('communication_settings', 'ajax');
        }
    }

    public function afsluiten($persoon_model, $id)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);

        $persoon_groepsactiviteiten_groepen = 'GroepsactiviteitenGroepen'.$persoon_model;
        $persoon_id_field = $this->{$persoon_model}->hasMany[$persoon_groepsactiviteiten_groepen]['foreignKey'];

        $gid = $this->data[$persoon_groepsactiviteiten_groepen]['id'];

        $this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->create();
        $validation_error = false;
        if (!$this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->save($this->data[$persoon_groepsactiviteiten_groepen])) {
            $validation_error = true;
        }

        $this->set('validation_error', $validation_error);

        $persoon_id = $id;
        $groepsactiviteiten_redenen = $this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->GroepsactiviteitenReden->find('list', [
                'contain' => [],
        ]);

        $groepsactiviteit = $this->data[$persoon_groepsactiviteiten_groepen];

        if (is_array($groepsactiviteit['einddatum']) && $groepsactiviteit['einddatum']['day'] == 0) {
            $groepsactiviteit['einddatum'] = null;
        }

        if (is_array($groepsactiviteit['einddatum']) && $groepsactiviteit['einddatum']['month'] == 0) {
            $groepsactiviteit['einddatum'] = null;
        }

        if (is_array($groepsactiviteit['einddatum']) && $groepsactiviteit['einddatum']['year'] == 0) {
            $groepsactiviteit['einddatum'] = null;
        }

        $this->set(compact('validation_error', 'persoon_model', 'persoon_id_field', 'groepsactiviteiten_redenen', 'persoon_id', 'groepsactiviteit', 'key', 'persoon_groepsactiviteiten_groepen'));
        $this->render('groep_afsluiten', 'ajax');
    }

    public function groepen($persoon_model, $id)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);
        $this->loadModel($persoon_model);

        $persoon_groepsactiviteiten_groepen = 'GroepsactiviteitenGroepen'.$persoon_model;
        $persoon_id_field = $this->{$persoon_model}->hasMany[$persoon_groepsactiviteiten_groepen]['foreignKey'];

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid persoon', true));

            return $this->redirect(['controller' => 'groepsacticiteiten_klanten', 'action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($persoon_model == 'Vrijwilliger') {
                $this->add_to_intake($persoon_model, $id);
            }

            $this->data[$persoon_groepsactiviteiten_groepen][$persoon_id_field] = $id;
            $this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->create();

            if ($this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->save($this->data)) {
                $this->Session->setFlash(__('De persoon is toegewezen aan de groep', true));
                $this->redirect(['action' => 'groepen', $persoon_model, $id]);
            } else {
                $this->Session->setFlash(__('De persoon kan niet worden toegewezen aan de groep', true));
            }
        }

        $persoon = $this->{$persoon_model}->read(null, $id);
        $this->data = $persoon;

        $groepsactiviteiten_groep = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_groep();
        $groepsactiviteiten_list = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $groepsactiviteiten_list_view = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list(true);
        $active_groeps = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_group_selection($persoon[$persoon_groepsactiviteiten_groepen], $groepsactiviteiten_groep, true);
        $inactive_groeps = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_group_selection($persoon[$persoon_groepsactiviteiten_groepen], $groepsactiviteiten_groep, false);
        $groepsactiviteiten_list_new = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_non_selected_open_groups($active_groeps, $groepsactiviteiten_groep);

        $vrijwilliger = $this->{$persoon_model}->read(null, $id);

        $diensten = [];

        if ($persoon_model == 'Klant') {
            $diensten = $this->Klant->diensten($persoon);
        }

        $groepsactiviteiten_redenen = $this->{$persoon_model}->{$persoon_groepsactiviteiten_groepen}->GroepsactiviteitenReden->get_groepsactiviteiten_reden_list();

        $this->set(compact('diensten', 'persoon', 'groepsactiviteiten_redenen', 'persoon_model', 'persoon_id_field', 'persoon_groepsactiviteiten_groepen', 'groepsactiviteiten_list_new', 'groepsactiviteiten_list_view', 'groepsactiviteiten_list', 'active_groeps', 'inactive_groeps', 'groepsactiviteiten_groep'));
        $this->setmetadata($persoon_model, $id);

        $this->render('view');
    }

    public function activiteiten($persoon_model, $id)
    {
        $persoon_model = $this->check_persoon_model($persoon_model);

        $persoon_groepsactiviteiten_id = $this->getParam('persoon_groepsactiviteiten_id');
        $persoon_model = $this->check_persoon_model($persoon_model);

        $persoon_groepsactiviteiten = 'Groepsactiviteiten'.$persoon_model;
        $persoon_groepsactiviteiten_groepen = 'GroepsactiviteitenGroepen'.$persoon_model;

        $this->loadModel($persoon_groepsactiviteiten);

        $persoon_id_field = $this->{$persoon_groepsactiviteiten}->belongsTo[$persoon_model]['foreignKey'];
        $this->loadModel($persoon_model);

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid persoon', true));
        }

        if (!empty($this->data)) {
            if ($persoon_model == 'Vrijwilliger') {
                $this->add_to_intake($persoon_model, $id);
            }

            $data = [
                    $persoon_groepsactiviteiten => [
                        'groepsactiviteit_id' => $this->data['Groepsactiviteit']['groepsactiviteit_id'],
                         $persoon_id_field => $id,
                         'afmeld_status' => 'Aanwezig',
                    ],
            ];

            $this->{$persoon_groepsactiviteiten}->create();

            if ($this->{$persoon_groepsactiviteiten}->save($data)) {
                $this->Session->setFlash(__('Persoon toegevoegd aan activiteit', true));
            } else {
                $this->Session->setFlash(__('Persoon kan niet worden toegevoegd aan activiteit', true));
            }
        }

        $options = [
            'conditions' => [$persoon_id_field => $id],
            'contain' => ['Groepsactiviteit' => ['id', 'naam', 'datum', 'time', 'groepsactiviteiten_groep_id']],
            'fields' => ['id', 'groepsactiviteit_id', 'afmeld_status'],
            'order' => 'datum desc, time desc',
        ];

        $activiteiten = $this->{$persoon_groepsactiviteiten}->find('all', $options);

        $persoon = $this->{$persoon_model}->read(null, $id);

        $groepsactiviteiten_groep = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_groep();
        $active_groeps = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_group_selection($persoon[$persoon_groepsactiviteiten_groepen], $groepsactiviteiten_groep, true);
        $groepsactiviteitengroepen_list = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $groepsactiviteitengroepen_list_view = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list(true);

        $a = Set::ClassicExtract($active_groeps, '{n}.groepsactiviteiten_groep_id');
        $groepsactiviteiten = $this->Groepsactiviteit->groeps_activiteiten_list($a);

        $a = [];
        foreach ($groepsactiviteiten as $g) {
            $a += array_keys($g);
        }

        $newgroepsactiviteiten = $groepsactiviteiten;
        foreach ($activiteiten as $activiteit) {
            foreach ($newgroepsactiviteiten as $key => $g) {
                //unset($newgroepsactiviteiten[$key][$activiteit['Groepsactiviteit']['id']]);
            }
        }

        $diensten = [];
        if ($persoon_model == 'Klant') {
            $diensten = $this->Klant->diensten($persoon);
        }

        $this->set(compact('diensten', 'groepsactiviteiten', 'newgroepsactiviteiten', 'persoon', 'persoon_model', 'activiteiten', 'persoon_id_field', 'persoon_groepsactiviteiten', 'groepsactiviteitengroepen_list_view', 'groepsactiviteitengroepen_list'));
        $this->setmetadata($persoon_model, $id);

        $isExport = (bool) $this->getParam('export');

        if ($isExport) {
            $personInFileName = preg_replace('~\s+~', '_', $persoon[$persoon_model]['name']);
            $file = "{$personInFileName}_{$persoon_model}_activiteiten_lijst.xls";
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=\"$file\";");
            header('Content-Transfer-Encoding: binary');
            $this->autoLayout = false;
            $this->layout = false;
            $this->render('activiteiten_excel');
        } else {
            $this->render('view');
        }
    }

    public function beheer()
    {
    }

    public function export($id = null)
    {
        $groepsactiviteiten_list = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list(true);

        $this->loadModel('GroepsactiviteitenGroepenKlant');

        $this->paginate = [
            'limit' => 100,
            'contain' => ['Klant'],
        ];
        $tmpid = $id;

        if (empty($tmpid)) {
            $tmpid = -100203;
        }

        $params = [
            'groepsactiviteiten_groep_id' => $tmpid,
            'OR' => [
                    'einddatum > now()',
                    'einddatum' => null,
            ],
        ];

        $deelnemers = $this->paginate('GroepsactiviteitenGroepenKlant', $params);

        $this->loadModel('GroepsactiviteitenGroepenVrijwilliger');

        $params = [
            'contain' => ['Vrijwilliger'],
            'conditions' => [
                'groepsactiviteiten_groep_id' => $tmpid,
                'OR' => [
                    'einddatum > now()',
                    'einddatum' => null,
                ],
            ],
        ];

        $vrijwilligers = $this->GroepsactiviteitenGroepenVrijwilliger->find('all', $params);

        $this->set('id', $id);
        $this->set('deelnemers', $deelnemers);
        $this->set('vrijwilligers', $vrijwilligers);
        $this->set('groepsactiviteiten_list', $groepsactiviteiten_list);
    }

    public function upload($persoon_model, $id)
    {
        if (!empty($this->data)) {
            $this->data['GroepsactiviteitenDocument']['foreign_key'] = $id;
            $this->data['GroepsactiviteitenDocument']['model'] = $persoon_model;
            $this->data['GroepsactiviteitenDocument']['group'] = 'Groepsactiviteit';
            $this->data['GroepsactiviteitenDocument']['user_id'] = $this->Session->read('Auth.Medewerker.id');

            if (empty($this->data['GroepsactiviteitenDocument']['title'])) {
                $this->data['GroepsactiviteitenDocument']['title'] = $this->data['GroepsactiviteitenDocument']['file']['name'];
            }

            $this->loadModel($persoon_model);

            if ($this->{$persoon_model}->GroepsactiviteitenDocument->save($this->data)) {
                $this->redirect([
                    'controller' => 'Groepsactiviteiten',
                    'action' => 'view',
                    $persoon_model,
                    $id,
                ]);
            } else {
                $this->flashError(__('The document could not be saved. Please, try again.', true));
            }
        }

        $this->set('id', $id);
        $this->set('persoon_model', $persoon_model);
    }

    public function email_selectie()
    {
        $this->loadModel('QueueTask');
        $selectie = $this->Session->read('selectie_postdata');

        if (empty($selectie)) {
            $this->redirect(['action' => 'selecties']);
        }

        if (!empty($this->data)) {
            foreach ($this->data['Document'] as $key => $v) {
                if ($v['file']['error'] != 0) {
                    unset($this->data['Document'][$key]);
                    continue;
                }
                $this->data['Document'][$key]['title'] = $v['file']['name'];
                $this->data['Document'][$key]['model'] = 'QueueTask';
            }

            if (empty($this->data['Groepsactiviteit']['onderwerp'])) {
                $this->Groepsactiviteit->invalidate('onderwerp', 'Email moet een onderwerp hebben');
            }

            if (empty($this->data['Groepsactiviteit']['text'])) {
                $this->Groepsactiviteit->invalidate('text', 'Email moet een inhoud hebben');
            }

            $this->data['QueueTask'] = [
                'model' => 'Medewerker',
                'foreign_key' => $this->Session->read('Auth.Medewerker.id'),
                'data' => [
                    'selectie' => $selectie,
                    'email' => $this->data,
                ],
                'action' => 'mass_email',
                'status' => STATUS_PENDING,
            ];

            if (empty($this->Groepsactiviteit->validationErrors)) {
                $ret = $this->QueueTask->saveAll($this->data);

                $saved = $ret;
                if (!empty($this->QueueTask->Document->validationErrors)) {
                    $saved = false;
                }

                if ($saved) {
                    $this->flash(__('Email is opgeslagen en zal z.s.m. verzonden worden', true));
                    $this->redirect(['action' => 'selecties']);

                    return;
                } else {
                    $this->flashError(__('Email kan niet verwerkt worden', true));
                }
            }
        }

        $personen = $this->Groepsactiviteit->get_personen($selectie, $only_email = true);

        $this->set(compact('personen'));
    }

    public function selecties()
    {
        $personen = $vrijwilligers = $klanten = [];

        if (!empty($this->data)) {
            $validated = true;
            $msg = '';

            if (empty($this->data['Groepsactiviteit']['activiteitengroepen']) &&
                    empty($this->data['Groepsactiviteit']['werkgebieden']) &&
                    empty($this->data['Groepsactiviteit']['communicatie_type'])) {
                //$validated = false;
            }

            if (empty($this->data['Groepsactiviteit']['persoon_model'])) {
                if (!empty($msg)) {
                    $msg .= ' / ';
                }
                $msg .= 'Personen';
                $validated = false;
            }

            if (empty($this->data['Groepsactiviteit']['communicatie_type'])) {
                if (!empty($msg)) {
                    $msg .= ' / ';
                }
                $msg .= 'Contact';
                $validated = false;
            }

            if ($validated) {
                $personen = $this->Groepsactiviteit->get_personen($this->data);

                if ($this->data['Groepsactiviteit']['export'] == 'csv') {
                    $date = date('Ymd_His');
                    $file = "selecties_{$date}.xls";

                    header('Content-type: application/vnd.ms-excel');
                    header("Content-Disposition: attachment; filename=\"$file\";");
                    header('Content-Transfer-Encoding: binary');

                    $this->autoLayout = false;
                    $this->layout = false;
                    $this->set('personen', $personen);
                    $this->render('selecties_excel');
                } else {
                    $this->Session->write('selectie_postdata', $this->data);
                    $this->redirect(['action' => 'email_selectie']);
                }

                $personen = Set::sort($personen, '{n}.achternaam', 'asc');
            } else {
                $this->Session->setFlash(__("Selecteer voldoende opties ({$msg})", true));
            }
        } else {
            $this->data = [
                'Groepsactiviteit' => [
                    'persoon_model' => array_keys(Configure::read('Persoontypen')),
                    'communicatie_type' => array_keys(Configure::read('Communicatietypen')),
                ],
            ];
        }

        $activiteitengroepen = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $this->set(compact('activiteitengroepen', 'personen', 'klanten', 'vrijwilligers'));
    }

    public function planning($id = 0)
    {
        $groepsactiviteiten_list = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list();

        $groepsactiviteiten = [];

        if (!empty($id)) {
            $this->paginate = [
                'conditions' => [
                    'GroepsactiviteitenGroep.id' => $id,
                ],
                'contain' => ['GroepsactiviteitenGroep' => ['id', 'naam']],
                'fields' => ['id', 'groepsactiviteiten_groep_id', 'naam', 'datum'],
                'limit' => 30,
            ];

            $groepsactiviteiten = $this->paginate('Groepsactiviteit');
            $groepsactiviteiten = $this->Groepsactiviteit->addCount($groepsactiviteiten);
        }

        $groepsactiviteiten_groep = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_groep();

        $this->set(compact('id', 'groepsactiviteiten_groep', 'groepsactiviteiten', 'groepsactiviteiten_list'));
    }

    public function activiteit_registreren($id)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Niet geldige reden', true));

            return $this->redirect(['controller' => 'groepsacticiteiten_klanten', 'action' => 'index']);
        }

        $this->loadModel('GroepsactiviteitenKlant');

        $this->paginate['GroepsactiviteitenKlant'] = [
                'conditions' => [
                     'groepsactiviteit_id' => $id,
                 ],
                'contain' => ['Klant'],
                'limit' => 100,
        ];

        $klanten = $this->paginate('GroepsactiviteitenKlant');

        $this->loadModel('GroepsactiviteitenVrijwilliger');

        $vrijwilligers = $this->GroepsactiviteitenVrijwilliger->find('all', [
            'conditions' => ['groepsactiviteit_id' => $id],
            'contain' => ['Vrijwilliger'],
            'order' => 'achternaam',
        ]);

        $this->Groepsactiviteit->recursive = 0;
        $groepsactiviteit = $this->data = $this->Groepsactiviteit->getById($id);
        $groepsactiviteiten_list = $this->Groepsactiviteit->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $this->set(compact('id', 'groepsactiviteit', 'groepsactiviteiten_list', 'klanten', 'vrijwilligers'));
    }

    public function activiteit_registreren_groep($persoon_model, $id)
    {
        $groepsactiviteit = $this->data = $this->Groepsactiviteit->getById($id);

        $persoon_model = $this->check_persoon_model($persoon_model);
        $persoon_groepsactiviteiten = 'Groepsactiviteiten'.$persoon_model;
        $persoon_groepsactiviteiten_groepen = 'GroepsactiviteitenGroepen'.$persoon_model;

        $this->loadModel($persoon_groepsactiviteiten);
        $this->loadModel($persoon_groepsactiviteiten_groepen);

        $persoon_id_field = $this->{$persoon_groepsactiviteiten}->belongsTo[$persoon_model]['foreignKey'];

        $current = $this->{$persoon_groepsactiviteiten}->find('all', [
                'conditions' => ['groepsactiviteit_id' => ['groepsactiviteit_id' => $id]],
                'contain' => [],
                'fields' => ['id', $persoon_id_field],
        ]);

        $current = Set::classicExtract($current, "{n}.{$persoon_groepsactiviteiten}.{$persoon_id_field}");

        $groep = $this->{$persoon_groepsactiviteiten_groepen}->find('all', [
            'conditions' => [
                'groepsactiviteiten_groep_id' => $groepsactiviteit['groepsactiviteiten_groep_id'],
                'or' => [
                    'einddatum' => null,
                    'einddatum > now()',
                ],
            ],
            'contain' => [],
            'fields' => ['id', $persoon_id_field, 'einddatum'],
            'order' => 'id',
        ]);

        $data = [];

        foreach ($groep as $g) {
            if (in_array($g[$persoon_groepsactiviteiten_groepen][$persoon_id_field], $current)) {
                continue;
            }

            $current[] = $g[$persoon_groepsactiviteiten_groepen][$persoon_id_field];

            $data[$persoon_groepsactiviteiten][] = [
                'groepsactiviteit_id' => $id,
                $persoon_id_field => $g[$persoon_groepsactiviteiten_groepen][$persoon_id_field],
                'afmeld_status' => 'Aanwezig',
            ];
        }

        if (!empty($data)) {
            $this->{$persoon_groepsactiviteiten}->begin();
            $result = $this->{$persoon_groepsactiviteiten}->saveAll($data[$persoon_groepsactiviteiten], ['atomic' => false]);

            if (in_array(0, $result)) {
                $this->Session->setFlash(__('Er kunnen geen personen toegevoegd worden', true));
                $this->{$persoon_groepsactiviteiten}->rollback();
            } else {
                $this->Session->setFlash(__('Personen zijn toegevoegd', true));
                $this->{$persoon_groepsactiviteiten}->commit();
            }
        } else {
            $this->Session->setFlash(__('Personen zijn reeds toegevoegd', true));
        }

        $this->redirect(['action' => 'activiteit_registreren', $id]);
    }

    public function activiteit_persoon_delete($id)
    {
        $persoon_model = $this->getParam('persoon_model');
        $persoon_groepsactiviteiten_id = $this->getParam('persoon_groepsactiviteiten_id');
        $persoon_model = $this->check_persoon_model($persoon_model);
        $persoon_groepsactiviteiten = 'Groepsactiviteiten'.$persoon_model;

        $groepsactiviteit = $this->data = $this->Groepsactiviteit->getById($id);

        $this->loadModel($persoon_groepsactiviteiten);
        $persoon_id_field = $this->{$persoon_groepsactiviteiten}->belongsTo[$persoon_model]['foreignKey'];

        if (empty($id) || empty($persoon_model) || empty($persoon_groepsactiviteiten_id) || empty($groepsactiviteit)) {
            $result = [
                'return' => false,
            ];
            $this->set('jsonVar', $result);
            $this->render('/elements/json', 'ajax');

            return;
        }

        $gp = $this->{$persoon_groepsactiviteiten}->getById($persoon_groepsactiviteiten_id);

        $result = [
            'return' => true,
            'persoon_model' => $persoon_model,
            'persoon_groepsactiviteiten_id' => $persoon_groepsactiviteiten_id,
            'persoon_id_field' => $persoon_id_field,
            'persoon_groepsactiviteiten' => $persoon_groepsactiviteiten,
        ];

        if (!empty($persoon_groepsactiviteiten_id)) {
            $this->{$persoon_groepsactiviteiten}->delete($persoon_groepsactiviteiten_id);
        }

        $this->set('jsonVar', $result);
        $this->render('/elements/json', 'ajax');
    }

    public function activiteit_persoon_status($id)
    {
        $persoon_model = $this->getParam('persoon_model');
        $persoon_groepsactiviteiten_id = $this->getParam('persoon_groepsactiviteiten_id');

        $afmeld_status = $this->getParam('afmeld_status');
        $persoon_model = $this->check_persoon_model($persoon_model);
        $persoon_groepsactiviteiten = 'Groepsactiviteiten'.$persoon_model;

        $groepsactiviteit = $this->data = $this->Groepsactiviteit->getById($id);

        $this->loadModel($persoon_groepsactiviteiten);

        $persoon_id_field = $this->{$persoon_groepsactiviteiten}->belongsTo[$persoon_model]['foreignKey'];

        if (empty($id) || empty($persoon_model) || empty($persoon_groepsactiviteiten_id) || empty($groepsactiviteit)) {
            $result = [
                    'return' => false,
            ];
            $this->set('jsonVar', $result);
            $this->render('/elements/json', 'ajax');

            return;
        }

        $retval = false;

        $data = [
             $persoon_groepsactiviteiten => [
                'id' => $persoon_groepsactiviteiten_id,
                'afmeld_status' => $afmeld_status,
             ],
        ];

        $this->{$persoon_groepsactiviteiten}->create();
        if ($this->{$persoon_groepsactiviteiten}->save($data)) {
            $retval = true;
        }

        $result = [
                'return' => $retval,
                'persoon_model' => $persoon_model,
                'persoon_groepsactiviteiten_id' => $persoon_groepsactiviteiten_id,
                'persoon_id_field' => $persoon_id_field,
                'afmeld_status' => $afmeld_status,
                'persoon_groepsactiviteiten' => $persoon_groepsactiviteiten,
        ];

        $this->set('jsonVar', $result);
        $this->render('/elements/json', 'ajax');
    }

    public function zrm_add($id)
    {
        $this->loadModel('ZrmReport');

        if (!empty($this->data)) {
            $this->ZrmReport->update_zrm_data_for_edit($this->data, 'Groepsactiviteit', $id, $id);

            if ($this->ZrmReport->save($this->data)) {
                $this->flash(__('ZRM opgeslagen', true));
                $this->redirect(['action' => 'view', $id]);
            } else {
                $this->flashError(__('ZRM niet opgeslagen. Probeer het opnieuw', true));
            }
        }

        $this->set('zrm_data', $this->ZrmReport->zrm_data());
        $this->set('id', $id);
    }

    public function rapportages()
    {
        if (!empty($this->data)) {
            debug($this->data);
        }

        if (!$this->data) {
            $this->data = [
                    'date_from' => ['year' => date('Y', time() - YEAR), 'month' => '01', 'day' => '01'],
                    'date_to' => ['year' => date('Y', time() - YEAR), 'month' => '12', 'day' => '31'],
            ];
        }

        $report_generator = 'ajax_report_html';
        $title = 'Groepen';
        $this->set(compact('report_generator', 'title'));

        $this->render('report');
    }

    public function ajax_report_html()
    {
        $reports = [];
        $reports[] = $this->groepen_report_html();
        $reports[] = $this->werkgebied_report_html();
        $reports[] = $this->personen_report_html();

        $this->autoLayout = false;

        $this->set(compact('reports'));

        if (!empty($this->data['options']['excel'])) {
            $this->layout = false;
            $file = 'groepsactiviteiten_groepen.xls';
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=\"$file\";");
            header('Content-Transfer-Encoding: binary');

            $this->render('report_excel');
        } else {
            $this->render('report_html');
        }
    }

    public function groepen_report_html()
    {
        $conditions = [];

        $date_from = $this->data['date_from']['year'].'-'.$this->data['date_from']['month'].'-'.$this->data['date_from']['day'];
        $date_to = $this->data['date_to']['year'].'-'.$this->data['date_to']['month'].'-'.$this->data['date_to']['day'];

        $sql = "select g.naam, g.werkgebied, g.id as groep_id, klant_id as p_id, a.id as groepsactiviteit_id
            from groepsactiviteiten_groepen g
            join groepsactiviteiten a on g.id = a.groepsactiviteiten_groep_id
            left join groepsactiviteiten_klanten pg on pg.groepsactiviteit_id = a.id
            where pg.afmeld_status = 'Aanwezig' and
            a.datum >= '{$date_from}' and a.datum < '{$date_to}'";

        $data = $this->Groepsactiviteit->query($sql);

        $groepen = [];

        $template = [
            'Deelnemers' => [],
            'Vrijwilligers' => [],
            'Activiteiten' => [],
            'naam' => '',
        ];

        foreach ($data as $d) {
            $g_id = $d['g']['groep_id'];
            $g_n = $d['g']['naam'].' ('.$d['g']['werkgebied'].') ';
            $a_id = $d['a']['groepsactiviteit_id'];
            $p_id = $d['pg']['p_id'];

            if (!isset($groepen[$g_id])) {
                $groepen[$g_id] = $template;
            }

            $groepen[$g_id]['naam'] = $g_n;
            $groepen[$g_id]['id'] = $g_id;

            if (!isset($groepen[$g_id]['Deelnemers'][$p_id])) {
                $groepen[$g_id]['Deelnemers'][$p_id] = 0;
            }

            ++$groepen[$g_id]['Deelnemers'][$p_id];
            if (!isset($groepen[$g_id]['Activiteiten'][$a_id])) {
                $groepen[$g_id]['Activiteiten'][$a_id] = 0;
            }

            ++$groepen[$g_id]['Activiteiten'][$a_id];
        }

        $sql = "select g.naam, g.werkgebied, g.id as groep_id, vrijwilliger_id as p_id, a.id as groepsactiviteit_id
            from groepsactiviteiten_groepen g
            join groepsactiviteiten a on g.id = a.groepsactiviteiten_groep_id
            left join groepsactiviteiten_vrijwilligers pg on pg.groepsactiviteit_id = a.id
            where pg.afmeld_status = 'Aanwezig' and
            a.datum >= '{$date_from}' and a.datum < '{$date_to}'";

        $data = $this->Groepsactiviteit->query($sql);

        foreach ($data as $d) {
            $g_id = $d['g']['groep_id'];
            $g_n = $d['g']['naam'].' ('.$d['g']['werkgebied'].') ';
            $a_id = $d['a']['groepsactiviteit_id'];
            $p_id = $d['pg']['p_id'];

            if (!isset($groepen[$g_id])) {
                $groepen[$g_id] = $template;
            }

            $groepen[$g_id]['naam'] = $g_n;
            $groepen[$g_id]['id'] = $g_id;

            if (!isset($groepen[$g_id]['Vrijwilligers'][$p_id])) {
                $groepen[$g_id]['Vrijwilligers'][$p_id] = 0;
            }

            ++$groepen[$g_id]['Vrijwilligers'][$p_id];

            if (!isset($groepen[$g_id]['Activiteiten'][$a_id])) {
                $groepen[$g_id]['Activiteiten'][$a_id] = 0;
            }

            ++$groepen[$g_id]['Activiteiten'][$a_id];
        }

        $report['head'] = "Groepen rapport : van {$date_from} tot {$date_to}";
        $report['result'] = [];
        $report['fields'] = [
                'naam' => 'Werkgebied',
                'activiteiten' => 'Aantal activiteiten',
                'deelnemers_cnt' => 'Aantal deelnemers',
                'deelnemers_unique_cnt' => 'Aantal unieke deelnemers',
                'vrijwilliegers_cnt' => 'Vrijwilligers',
                'vrijwilligers_unique_cnt' => 'Aantal unieke vrijwilligers',
        ];

        foreach ($groepen as $groep) {
            $tmp = [];
            $tmp['naam'] = $groep['naam'];
            $tmp['activiteiten'] = count($groep['Activiteiten']);
            $tmp['deelnemers_cnt'] = count($groep['Deelnemers']);
            $tmp['deelnemers_unique_cnt'] = 0;

            foreach ($groep['Deelnemers'] as $d) {
                $tmp['deelnemers_unique_cnt'] += 1;
            }

            $tmp['vrijwilliegers_cnt'] = count($groep['Vrijwilligers']);
            $tmp['vrijwilligers_unique_cnt'] = 0;

            foreach ($groep['Vrijwilligers'] as $d) {
                $tmp['vrijwilligers_unique_cnt'] += 1;
            }

            $report['result'][] = $tmp;
        }

        $report['hasSummary'] = false;

        return $report;
    }

    public function personen_report_html()
    {
        $conditions = [];

        $date_from = $this->data['date_from']['year'].'-'.$this->data['date_from']['month'].'-'.$this->data['date_from']['day'];
        $date_to = $this->data['date_to']['year'].'-'.$this->data['date_to']['month'].'-'.$this->data['date_to']['day'];

        $sql = "select p.werkgebied, p.id from klanten p
            join groepsactiviteiten_klanten gp on gp.klant_id = p.id
            join groepsactiviteiten a on a.id = gp.groepsactiviteit_id
            where gp.afmeld_status = 'Aanwezig' and
            a.datum >= '{$date_from}' and a.datum < '{$date_to}'";

        $data = $this->Groepsactiviteit->query($sql);

        $werkgebieden = Configure::read('Werkgebieden');

        $template = [
                'Deelnemers' => [],
                'Vrijwilligers' => [],
        ];

        foreach ($werkgebieden as $key => $v) {
            $werkgebieden[$key] = $template;
            $werkgebieden[$key]['werkgebied'] = $key;
        }

        foreach ($data as $d) {
            $w = $d['p']['werkgebied'];
            if (empty($w)) {
                $w = 'Onbekend';
            }

            $p_id = $d['p']['id'];
            if (!isset($werkgebieden[$w])) {
                $werkgebieden[$w] = $template;
            }

            if (!isset($werkgebieden[$w]['Deelnemers'][$p_id])) {
                $werkgebieden[$w]['Deelnemers'][$p_id] = 0;
            }

            ++$werkgebieden[$w]['Deelnemers'][$p_id];
            $werkgebieden[$w]['werkgebied'] = $w;
        }

        $sql = "select p.werkgebied, p.id from vrijwilligers p
        join groepsactiviteiten_vrijwilligers gp on gp.vrijwilliger_id = p.id
        join groepsactiviteiten a on a.id = gp.groepsactiviteit_id
        where gp.afmeld_status = 'Aanwezig' and
        a.datum >= '{$date_from}' and a.datum < '{$date_to}'";

        $data = $this->Groepsactiviteit->query($sql);

        foreach ($data as $d) {
            $w = $d['p']['werkgebied'];

            if (empty($w)) {
                $w = 'Onbekend';
            }

            $p_id = $d['p']['id'];

            if (!isset($werkgebieden[$w])) {
                $werkgebieden[$w] = $template;
            }

            if (!isset($werkgebieden[$w]['Vrijwilligers'][$p_id])) {
                $werkgebieden[$w]['Vrijwilligers'][$p_id] = 0;
            }

            ++$werkgebieden[$w]['Vrijwilligers'][$p_id];
            $werkgebieden[$w]['werkgebied'] = $w;
        }

        $report['head'] = "Rapport personen : van  {$date_from} tot {$date_to} ";
        $report['fields'] = ['werkgebied' => 'Werkgebied', 'deelnemers_unique_cnt' => 'Aantal unieke deelnemers', 'vrijwilligers_unique_cnt' => 'Aantal unieke vrijwilligers'];
        $report['hasSummary'] = false;

        $report['result'] = [];

        foreach ($werkgebieden as $werkgebied) {
            $tmp = [];
            $tmp['werkgebied'] = $werkgebied['werkgebied'];
            $tmp['deelnemers_unique_cnt'] = count($werkgebied['Deelnemers']);
            $tmp['vrijwilligers_unique_cnt'] = count($werkgebied['Vrijwilligers']);

            $report['result'][] = $tmp;
        }

        return $report;
    }

    public function werkgebied_report_html()
    {
        $conditions = [];

        $date_from = $this->data['date_from']['year'].'-'.$this->data['date_from']['month'].'-'.$this->data['date_from']['day'];
        $date_to = $this->data['date_to']['year'].'-'.$this->data['date_to']['month'].'-'.$this->data['date_to']['day'];

        $sql = "select pg.klant_id as persoon_id, g.id as groepsactiviteit_id, gg.werkgebied as werkgebied
            from groepsactiviteiten g
            join groepsactiviteiten_groepen gg on gg.id = g.groepsactiviteiten_groep_id
            left join groepsactiviteiten_klanten pg on pg.groepsactiviteit_id = g.id
            where pg.afmeld_status = 'Aanwezig' and
            g.datum >= '{$date_from}' and g.datum < '{$date_to}'";

        $data = $this->Groepsactiviteit->query($sql);

        $werkgebieden = [];

        $template = [
            'Deelnemers' => [],
            'Vrijwilligers' => [],
            'Activiteiten' => [],
        ];

        foreach ($data as $d) {
            $w = $d['gg']['werkgebied'];
            $p = $d['pg']['persoon_id'];
            $g = $d['g']['groepsactiviteit_id'];

            if (!isset($werkgebieden[$w])) {
                $werkgebieden[$w] = $template;
            }

            if (!empty($p)) {
                if (!isset($werkgebieden[$w]['Deelnemers'][$p])) {
                    $werkgebieden[$w]['Deelnemers'][$p] = 0;
                }
            }

            ++$werkgebieden[$w]['Deelnemers'][$p];

            if (!isset($werkgebieden[$w]['Activiteiten'][$g])) {
                $werkgebieden[$w]['Activiteiten'][$g] = 0;
            }

            ++$werkgebieden[$w]['Activiteiten'][$g];
        }

        $sql = "select pg.vrijwilliger_id as persoon_id, g.id as groepsactiviteit_id, gg.werkgebied as werkgebied
            from groepsactiviteiten g
            join groepsactiviteiten_groepen gg on gg.id = g.groepsactiviteiten_groep_id
            left join groepsactiviteiten_vrijwilligers pg on pg.groepsactiviteit_id = g.id
            where  pg.afmeld_status = 'Aanwezig' and
             g.datum >= '{$date_from}' and g.datum < '{$date_to}'";

        $data = $this->Groepsactiviteit->query($sql);

        foreach ($data as $d) {
            $w = $d['gg']['werkgebied'];
            $p = $d['pg']['persoon_id'];
            $g = $d['g']['groepsactiviteit_id'];

            if (!isset($werkgebieden[$w])) {
                $werkgebieden[$w] = $template;
            }

            if (!empty($p)) {
                if (!isset($werkgebieden[$w]['Vrijwilligers'][$p])) {
                    $werkgebieden[$w]['Vrijwilligers'][$p] = 0;
                }
                ++$werkgebieden[$w]['Vrijwilligers'][$p];
            }

            if (!isset($werkgebieden[$w]['Activiteiten'][$g])) {
                $werkgebieden[$w]['Activiteiten'][$g] = 0;
            }

            ++$werkgebieden[$w]['Activiteiten'][$g];
        }

        $report['result'] = [];

        foreach ($werkgebieden as $key => $w) {
            $tmp = [];
            $tmp['werkgebied'] = $key;
            $tmp['activiteiten_cnt'] = count($w['Activiteiten']);
            $tmp['deelenemers_unique'] = count($w['Deelnemers']);
            $tmp['deelenemers'] = 0;

            foreach ($w['Deelnemers'] as $d) {
                $tmp['deelenemers'] += $d;
            }

            $tmp['vrijwilligers_unique'] = count($w['Vrijwilligers']);
            $tmp['vrijwilligers'] = 0;

            foreach ($w['Vrijwilligers'] as $d) {
                $tmp['vrijwilligers'] += $d;
            }

            $report['result'][] = $tmp;
        }

        $report['head'] = "Rapport werkgebied : van ($date_from} tot {$date_to} ";

        $report['hasSummary'] = false;

        $this->autoLayout = false;

        $report['fields'] = [
                'werkgebied' => 'Werkgebied',
                'activiteiten_cnt' => 'Activiteiten',
                'deelenemers' => 'Deelnemers',
                'deelenemers_unique' => 'Aantal unieke deelnemers',
                'vrijwilligers' => 'Vrijwilligers',
                'vrijwilligers_unique' => 'Aantal unieke vrijwilligers',
        ];

        return $report;
    }
}

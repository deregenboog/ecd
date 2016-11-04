<?php

class PfoClientenController extends AppController
{
    public $name = 'PfoClienten';
    public $uses = array('PfoClient', 'PfoVerslag');
    public $components = array('Filter', 'RequestHandler', 'Session');

    private function set_view_variables($id = null, $data = null)
    {
        $current_aardrelatie = null;
        $current_groep = null;

        if (!empty($data)) {
            $current_aardrelatie = $data['PfoClient']['aard_relatie'];
            $current_groep = $data['PfoClient']['groep'];
        }

        $groepen = array();
        $aard_relatie = array();

        if (empty($id)) {
            $groepen = array('' => '');
            $aard_relatie = array('' => '');
        }

        $this->PfoAardRelatie = ClassRegistry::init('PfoAardRelatie');
        $this->PfoGroep = ClassRegistry::init('PfoGroep');
        $this->Medewerker = ClassRegistry::init('Medewerker');

        $groepen += $this->PfoGroep->get_list($current_groep);
        $aard_relatie += $this->PfoAardRelatie->get_list($current_aardrelatie);

        $medewerker_id = null;
        if (!empty($data['PfoClient']['medewerker_id'])) {
            $medewerker_id = $data['PfoClient']['medewerker_id'];
        }

        $this->setMedewerkers($medewerker_id, array(GROUP_PFO));
        $geslachten = $this->PfoClient->Geslacht->find('list');
        $contact_type = $this->PfoVerslag->contact_type;

        $clienten = $this->PfoClient->clienten();

        $vrije_clienten = $this->PfoClient->vrije_clienten($clienten);
        $hoofd_clienten = $this->PfoClient->hoofd_clienten($clienten);

        $this->set(compact('contact_type', 'pfo_users', 'groepen', 'aard_relatie', 'medewerkers', 'geslachten', 'clienten', 'vrije_clienten', 'hoofd_clienten'));
    }

    public function index($active = null)
    {
        $this->PfoClient->recursive = 0;
        $conditions = $this->Filter->filterData;

        if ($active != 'afgesloten') {
            $conditions['PfoClient.datum_afgesloten'] = null;
        } else {
            $conditions['PfoClient.datum_afgesloten NOT'] = null;
        }

        $this->PfoClient->recursive = 0;
        $pfoclienten = $this->paginate('PfoClient', $conditions);

        $this->set_view_variables();

        $this->set('pfoclienten', $pfoclienten);
        $this->set('active', $active);

        if ($this->RequestHandler->isAjax()) {
            $this->render('/elements/pfoclientenlijst', 'ajax');
        }
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid pfo client', true));
            $this->redirect(array('action' => 'index'));
        }

        $pfoClient = $this->PfoClient->read_complete($id);
        $this->set_view_variables($id, $pfoClient, $pfoClient);

        $this->set('pfoClient', $pfoClient);
    }

    public function add()
    {
        if (!empty($this->data)) {
            if ($this->data['controll']['hoofdclient']) {
                unset($this->data['SupportClient']);
            } else {
                unset($this->data['PfoClientenSupportgroup']);
            }

            $this->PfoClient->create();

            $saved = false;

            $this->PfoClient->begin();

            if ($this->PfoClient->save($this->data['PfoClient'])) {
                if (!empty($this->data['SupportClient']['pfo_client_id'])) {
                    $this->data['SupportClient']['pfo_supportgroup_client_id'] = $this->PfoClient->id;
                    if ($this->PfoClient->SupportClient->save($this->data['SupportClient'])) {
                        $saved = true;
                    }
                } else {
                    $saved = true;
                }

                if (!empty($this->data['PfoClientenSupportgroup'])) {
                    foreach ($this->data['PfoClientenSupportgroup'] as $key => $value) {
                        $this->data['PfoClientenSupportgroup'][$key]['pfo_client_id'] = $this->PfoClient->id;
                        if ($this->PfoClient->PfoClientenSupportgroup->saveAll($this->data['PfoClientenSupportgroup'])) {
                            $saved = true;
                        }
                    }
                } else {
                    $saved = true;
                }
            }

            if ($saved) {
                $this->PfoClient->commit();
                $this->Session->setFlash(__('The pfo client has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->PfoClient->rollback();
                $this->Session->setFlash(__('The pfo client could not be saved. Please, try again.', true));
            }
        }
        $this->set_view_variables();
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid pfo client', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->data['controll']['hoofdclient']) {
                unset($this->data['SupportClient']);
            } else {
                unset($this->data['PfoClientenSupportgroup']);
            }

            $ids = array();

            if (isset($this->data['PfoClientenSupportgroup'])) {
                foreach ($this->data['PfoClientenSupportgroup'] as $key => $pg) {
                    $ids[] = $pg['pfo_supportgroup_client_id'];
                    $this->data['PfoClientenSupportgroup'][$key]['pfo_client_id'] = $id;
                }
            }

            $id_hc = null;

            if (isset($this->data['SupportClient']) && !empty($this->data['SupportClient']['pfo_client_id'])) {
                $this->data['SupportClient']['pfo_supportgroup_client_id'] = $id;
                $id_hc = $this->data['SupportClient']['pfo_client_id'];
            } else {
                unset($this->data['SupportClient']);
            }

            // 1) Delete everything where I am connected to
            // 2) Delete everything where my supportgroup is connected to
            // 3) If I select a client as a hoofdclient, this hoofdclient can
            // not be connected to anyone else
            $conditions = array(
                'OR' => array(
                    array('pfo_client_id' => $id), //1
                    array('pfo_supportgroup_client_id' => $id), //1
                    array('pfo_client_id' => $ids), //2
                    array('pfo_supportgroup_client_id' => $ids), //2
                    array('pfo_supportgroup_client_id' => $id_hc), //3
                ),
            );

            $this->PfoClient->begin();
            $del = $this->PfoClient->PfoClientenSupportgroup->deleteAll($conditions);

            if ($this->PfoClient->saveAll($this->data)) {
                $this->Session->setFlash(__('The pfo client has been saved', true));
                $this->PfoClient->commit();
                $this->redirect(array('action' => 'view', $id));
            } else {
                $this->PfoClient->rollback();
                $this->Session->setFlash(__('The pfo client could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->PfoClient->read_complete($id);
        }

        $this->set_view_variables($id, $this->data);
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for pfo client', true));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->PfoClient->delete($id)) {
            $this->Session->setFlash(__('Pfo client deleted', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('Pfo client was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }
    public function upload($id = null, $group = null)
    {
        if (empty($id)) {
            $id = $this->data['PfoClient']['id'];
        }

        if (empty($group)) {
            $group = $this->data['Document']['group'];
        }

        $proClient = $this->PfoClient->read(null, $id);
        $this->set('pfoClient', $proClient);

        if (!empty($this->data)) {
            $this->data['Document']['foreign_key'] = $id;
            $this->data['Document']['model'] = $this->PfoClient->name;
            $this->data['Document']['group'] = $group;
            $this->data['Document']['user_id'] = $this->Session->read('Auth.Medewerker.id');

            if (empty($this->data['Document']['title'])) {
                $this->data['Document']['title'] = $this->data['Document']['file']['name'];
            }

            if ($this->PfoClient->Document->save($this->data)) {
                $this->redirect(array(
                        'action' => 'view',
                        $id,
                ));
            } else {
                $this->flashError(__('The document could not be saved. Please, try again.', true));
            }
        } else {
            $this->data = $proClient;
            $this->data['Document']['group'] = $group;
        }
    }
    public function beheer()
    {
    }

    public function rapportage()
    {
        if (isset($this->data)) {
            $this->PfoGroep = ClassRegistry::init('PfoGroep');
            $groepen = $this->PfoGroep->get_list();
            $this->PfoVerslag = ClassRegistry::init('PfoVerslag');
            $contact_type = $this->PfoVerslag->contact_type;
            foreach ($contact_type as $k => $v) {
                $contact_type[$k] = 0;
            }

            $year_from = intval($this->data['PfoClient']['jaar']);
            $year_to = $year_from + 1;

            $query = "select groep, contact_type, count(*) as count 
				from pfo_verslagen PfoVerslag 
				join pfo_clienten_verslagen 
					on PfoVerslag.id = pfo_verslag_id 
				join pfo_clienten PfoClient 
					on PfoClient.id = pfo_client_id		
				where PfoVerslag.created >= '{$year_from}' 
					and PfoVerslag.created < '{$year_to}' 
				group by groep, contact_type";

            $contact_total = $this->PfoClient->query($query);

            foreach ($contact_total as $c) {
                $ct = $c['PfoVerslag']['contact_type'];
                if (!isset($contact_type[$ct])) {
                    $contact_type[$ct] = 0;
                }
            }

            $contact_momenten = array();
            foreach ($groepen as $key => $groep) {
                $contact_momenten[$key] = array();
                $contact_momenten[$key] = $contact_type;
            }

            foreach ($contact_total as $c) {
                $groep = $c['PfoClient']['groep'];
                $count = $c[0]['count'];

                if (!isset($contact_momenten[$groep])) {
                    $contact_momenten[$groep] = array();
                    $contact_momenten[$groep] = $contact_type;
                }

                $contact_momenten[$groep][$c['PfoVerslag']['contact_type']] += $count;
            }

            $total = $contact_type;
            foreach ($contact_momenten as $k => $v) {
                $tot = 0;

                foreach ($v as $k1 => $v2) {
                    $tot += $v2;
                    $total[$k1] += $v2;
                }

                $contact_momenten[$k]['Totaal'] = $tot;
            }

            $tot = 0;

            foreach ($total as $t) {
                $tot += $t;
            }

            $total['Totaal'] = $tot;
            $contact_momenten['Totaal'] = $total;

            $query = "select groep, count(*) as count 
				from ( 
					select groep from pfo_clienten PfoClient 
					join pfo_clienten_verslagen cv 
						on PfoClient.id = pfo_client_id 
					where cv.created >= '{$year_from}' 
						and cv .created < '{$year_to}' 
					or PfoClient.created >= '{$year_from}' 
						and PfoClient.created < '{$year_to}' 
					group by PfoClient.id 
				) as subq group by groep;";

            $groep_total = $this->PfoClient->query($query);
            $year = $year_from;

            $this->set(compact('groep_total', 'contact_momenten', 'contact_total', 'groepen', 'year'));
        }
    }
}

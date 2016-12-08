<?php

App::import('Controller', 'IzDeelnemers');

class IzKlantenController extends IzDeelnemersController
{
    public $name = 'IzKlanten';

    public function __construct()
    {
        $this->name = 'IzDeelnemers';
        $this->loadModel('IzKlant');
        parent::__construct();
        $this->viewPath = 'iz_klanten';
    }

    public function index()
    {
        $this->paginate['IzKlant'] = array(
            'contain' => array(
                'Klant' => array('Geslacht'),
                'IzIntake' => array('Medewerker'),
                'IzHulpvraag',
            ),
        );
        $klanten = $this->paginate('IzKlant');

        $this->set('werkgebieden', Configure::read('Werkgebieden'));

        $this->setMedewerkers();

        $projectlists_view = array('' => '') + $this->IzKlant->IzDeelnemersIzProject->IzProject->projectLists(true);
        $projectlists = array('' => '') + $this->IzKlant->IzDeelnemersIzProject->IzProject->projectLists(false);
        $this->set('projectlists', $projectlists);

        $rowOnclickUrl = array(
            'controller' => 'iz_deelnemers',
            'action' => 'view',
            'IzKlant',
        );
        $this->set('rowOnclickUrl', $rowOnclickUrl);

        $now = date('Y-m-d');
        foreach ($klanten as $key => $klant) {
            $project_ids = array();
            $medewerker_ids = array();

            if (isset($klant['IzIntake'])) {
                if (!empty($klant['IzIntake']['medewerker_id'])) {
                    $medewerker_ids[] = $klant['IzIntake']['medewerker_id'];
                }
            }

            if (isset($klant['IzHulpvraag'])) {
                foreach ($klant['IzHulpvraag'] as $hulpvraag) {
                    if (!empty($hulpvraag['koppeling_einddatum']) && $now > $hulpvraag['koppeling_einddatum']) {
                        continue;
                    }
                    if (!empty($hulpvraag['einddatum']) && $now > $hulpvraag['einddatum']) {
                        continue;
                    }
                    $project_ids[] = $hulpvraag['project_id'];
                    $medewerker_ids[] = $hulpvraag['medewerker_id'];
                }
            }

            $project_ids = array_unique($project_ids);
            $medewerker_ids = array_unique($medewerker_ids);

            $klanten[$key]['IzKlant']['projectlist'] = '';
            $klanten[$key]['IzKlant']['medewerker'] = '';

            if (!empty($project_ids)) {
                foreach ($project_ids as $project_id) {
                    if (!empty($klanten[$key]['IzKlant']['projectlist'])) {
                        $klanten[$key]['IzKlant']['projectlist'] .= ', ';
                    }
                    $klanten[$key]['IzKlant']['projectlist'] .= $projectlists_view[$project_id];
                }
            }

            $klanten[$key]['IzKlant']['medewerker_ids'] = $medewerker_ids;
        }

        $this->set('klanten', $klanten);
    }
}

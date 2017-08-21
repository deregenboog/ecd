<?php

class SchorsingenController extends AppController
{
    public $name = 'Schorsingen';

    public function index($klant_id, $locatie_id = null)
    {
        if (!$klant_id) {
            $this->flashError(__('Klant niet gevonden', true));
            $this->redirect(['controller' => 'klanten']);
        }

        $this->Schorsing->recursive = -1;

        $active_schorsingen = $this->Schorsing->getActiveSchorsingen($klant_id);
        $expired_schorsingen = $this->Schorsing->getExpiredSchorsingen($klant_id);
        $klant = $this->Schorsing->Klant->find('first', [
            'conditions' => ['Klant.id' => $klant_id],
            'contain' => $this->Schorsing->Klant->contain,
            'fields' => [
                'voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam',
                'geboortedatum', 'BSN', 'laatste_TBC_controle', 'id',
            ],
        ]);

        $this->set(compact('active_schorsingen', 'expired_schorsingen', 'klant', 'locatie_id', 'klant_id'));
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect(['action' => 'index']);
        }
        $this->set('schorsing', $this->Schorsing->read(null, $id));
    }

    public function add($klant_id = null, $locatie_id = null)
    {
        if (!$klant_id) {
            $this->flashError(__('Klant niet gevonden', true));
            $this->redirect(['controller' => 'klanten']);
        }

        if (!empty($this->data)) {
            // changing the days fields data into dates:
            if (!$this->Schorsing->calculateDates($this->data)) {
                $this->flashError(__('Please choose the number of days', true));
            } else {
                $this->Schorsing->create();

                if ($this->Schorsing->save($this->data)) {
                    $this->flash(__('The schorsing has been saved', true));
                    $this->sendSchorsingEmail($this->Schorsing->id, $klant_id, $locatie_id);

                    $redirect_url = ['action' => 'index', $klant_id];
                    if (isset($locatie_id)) {
                        $redirect_url[] = $locatie_id;
                    }

                    $this->redirect($redirect_url);
                } else {
                    $this->flashError(__('The schorsing could not be saved. Please, try again.', true));
                }
            }
        }

        if ($klant_id) {
            $violent_options = $this->Schorsing->Reden->get_violent_options();
            $this->Schorsing->Klant->recursive = -1;
            $klant = $this->Schorsing->Klant->find('first', [
                'conditions' => ['Klant.id' => $klant_id],
                'fields' => ['voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam', 'id'],
            ]);

            if (!$klant) {
                $this->flashError(__('Klant niet gevonden.', true));
                $this->redirect(['controller' => 'klanten']);
            }

            // if locatie_id is given we add schorsing to a particular locatie
            // and the locatie_id field is hidden in the form
            // otherwise we provide a dropdown with locations
            if ($locatie_id) {
                $this->set('locaties', $this->Schorsing->Locatie->find('list', [
                    'conditions' => ['Locatie.id' => $locatie_id],
                ]));
                $this->set(compact('locatie_id'));
            } else {
                $this->set('locaties', $this->Schorsing->Locatie->find('list', [
                    'conditions' => [
                        ['datum_van <>' => '0000-00-00'],
                        ['datum_van <=' => date('Y-m-d')],
                        ['OR' => [
                            ['datum_tot' => '0000-00-00'],
                            ['datum_tot >=' => date('Y-m-d')],
                        ]],
                    ],
                ]));
            }

            $this->set(compact('klant_id', 'redenen', 'klant', 'violent_options'));
        }

        $violent_options = $this->Schorsing->Reden->get_violent_options();
        $redenen = $this->Schorsing->Reden->get_schorsing_redenen();
        $this->set(compact('klanten', 'redenen', 'violent_options'));
    }

    private function sendSchorsingEmail($schorsing_id)
    {
        $schorsing = $this->Schorsing->find($schorsing_id);

        if (!isset($this->Medewerker)) {
            $this->loadModel('Medewerker');
        }
        $medewerkers = $this->Medewerker->getMedewerkers([], [], true);
        $medewerker = $medewerkers[$this->Session->read('Auth.Medewerker.id')];

        $content = [
            'Message' => ['Bericht naar aanleiding van een schorsing waarbij sprake was van fysieke of verbale agressie'],
            'medewerker' => $medewerker,
            'Schorsing' => $schorsing['Schorsing'],
        ];

        $locaties = [];
        foreach ($schorsing['Locatie'] as $locatie) {
            $locaties[] = $locatie['naam'];
        }
        $content['Schorsing']['locatie_naam'] = implode(', ', $locaties);

        $redenen = [];
        foreach ($schorsing['Reden'] as $reden) {
            $redenen[] = $reden['naam'];
        }
        $content['Schorsing']['reden'] = implode(', ', $redenen);

        $options_medewerker = Configure::read('options_medewerker');
        if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker']])) {
            $content['Schorsing']['aggressie_tegen_medewerker'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker']];
        }
        if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker2']])) {
            $content['Schorsing']['aggressie_tegen_medewerker2'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker2']];
        }
        if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker3']])) {
            $content['Schorsing']['aggressie_tegen_medewerker3'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker3']];
        }
        if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker4']])) {
            $content['Schorsing']['aggressie_tegen_medewerker4'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker4']];
        }

        if (!empty($content['Schorsing']['aangifte'])) {
            $content['Schorsing']['aangifte'] = 'ja';
        } else {
            $content['Schorsing']['aangifte'] = 'nee';
        }

        if (!empty($content['Schorsing']['nazorg'])) {
            $content['Schorsing']['nazorg'] = 'ja';
        } else {
            $content['Schorsing']['nazorg'] = 'nee';
        }

        unset($content['Schorsing']['id']);
        unset($content['Schorsing']['locatie_id']);
        unset($content['Schorsing']['klant_id']);
        unset($content['Schorsing']['id']);

        $klant_id = $schorsing['Klant']['id'];
        $content['Klant'] = $this->Schorsing->Klant->getAllById($klant_id, ['Geslacht']);

        $content['url'] = Router::url(['controller' => 'schorsingen', 'action' => 'index', $klant_id], true);

        if (count($locaties) > 2) {
            $subject = 'Agressiemelding '.count($locaties).' locaties door medewerker '.$medewerker;
        } else {
            $subject = 'Agressiemelding '.implode('/', $locaties).' door medewerker '.$medewerker;
        }

        $address = Configure::read('agressie_mail');

        $this->_genericSendEmail([
            'to' => [$address],
            'content' => $content,
            'template' => 'agressie',
            'subject' => $subject,
        ]);
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->Schorsing->save($this->data)) {
                $this->flash(__('The schorsing has been saved', true));
                $this->redirect(['action' => 'index', $this->data['Schorsing']['klant_id']]);
            } else {
                $this->flashError(__('The schorsing could not be saved. Please, try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->Schorsing->read(null, $id);
        }

        $violent_options = $this->Schorsing->Reden->get_violent_options();
        $locaties = $this->Schorsing->Locatie->find('list', [
            'conditions' => [
                ['datum_van <>' => '0000-00-00'],
                ['datum_van <=' => date('Y-m-d')],
                ['OR' => [
                    ['datum_tot' => '0000-00-00'],
                    ['datum_tot >=' => date('Y-m-d')],
                ]],
            ],
        ]);

        $klanten = $this->Schorsing->Klant->find('list');
        $redenen = $this->Schorsing->Reden->get_schorsing_redenen();
        $this->set(compact('locaties', 'klanten', 'redenen', 'violent_options'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for schorsing', true));
            $this->redirect(['action' => 'index']);
        }
        if ($this->Schorsing->delete($id)) {
            $this->flashError(__('Schorsing deleted', true));
            $this->redirect(['action' => 'index']);
        }
        $this->flashError(__('Schorsing was not deleted', true));
        $this->redirect(['action' => 'index']);
    }

    public function gezien($id)
    {
        $this->Schorsing->gezien($id);
        $this->autoLayout = false;
        $this->autoRender = false;
        echo '{success: true}';
    }

    /*
     * Generates a printable pdf of schorsing.
     * If @eng is set to true, the get_eng_pdf view is rendered, and the pdf
     * is generated in English.
     */

    public function get_pdf($schorsing_id = null, $eng = 0)
    {
        if (empty($schorsing_id)) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect('/');
        }

        $schorsing = &$this->Schorsing->find('first', [
            'conditions' => ['Schorsing.id' => $schorsing_id],
            'contain' => [
                'Klant' => [
                    'fields' => ['name'],
                    'LasteIntake' => [
                        'fields' => ['postcode', 'woonplaats', 'postadres'],
                    ],
                    'Geslacht' => [
                        'fields' => ['afkorting'],
                    ],
                ],
                'Locatie' => [
                    'fields' => ['naam'],
                ],
                'Reden' => [
                    'fields' => ['naam'],
                ],
            ],
        ]);

        if (empty($schorsing)) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect('/');
        }

        $redenen = [];
        if (!empty($schorsing['Reden'])) {
            foreach ($schorsing['Reden'] as $reden) {
                if ($reden['SchorsingenReden']['reden_id'] == 100) {
                    $redenen[] = $reden['naam'].': '.$schorsing['Schorsing']['overig_reden'];
                } else {
                    $redenen[] = $reden['naam'];
                }
            }
        }

        $opmerking_uit_schorsing = $schorsing['Schorsing']['remark'];
        $bijzonderheden = $schorsing['Schorsing']['bijzonderheden'];
        $locatiehoofd = $schorsing['Schorsing']['locatiehoofd'];

        //schorsing start date
        $begindatum_schorsing = $schorsing['Schorsing']['datum_van'];

        //calculating the other times
        $begin = new DateTime($schorsing['Schorsing']['datum_van']);

        //schorsing end date
        $end = new DateTime($schorsing['Schorsing']['datum_tot']);

        if ($eng) {
            // for english version we use DateTime format to get the proper format of the dates
            $format = 'F j, Y';
            $begindatum_schorsing = $begin->format($format);
        } else {
            //for Dutch we just send DB date format back to the view where it's formatted
            $format = 'Y-m-d';
        }

        // a day when the schorsing will have expired:
        $end->add(new DateInterval('P1D'));
        $einddatum_schorsing_pp = $end->format($format);

        // calculating the period
        $difference = $end->diff($begin);
        $lengte_schorsing = $difference->format('%a');

        // formatting the text dependend on the number of days:
        if ($lengte_schorsing == 1) {
            if ($eng) {
                $lengte_schorsing = '1 day';
            } else {
                $lengte_schorsing = __('1 day', true);
            }
        } else {
            if ($eng) {
                $lengte_schorsing .= ' days';
            } else {
                $lengte_schorsing =
                    sprintf(__('%s days', true), $lengte_schorsing);
            }
        }

        // client data
        $klant_naam = $schorsing['Klant']['name'];
        $adres = $schorsing['Klant']['LasteIntake']['postadres'];
        $postcode = $schorsing['Klant']['LasteIntake']['postcode'];
        $woonplaats = $schorsing['Klant']['LasteIntake']['woonplaats'];
        $geslacht = $schorsing['Klant']['Geslacht']['afkorting'];

        $locaties = [];
        foreach ($schorsing['Locatie'] as $locatie) {
            $locaties[] = $locatie['naam'];
        }
        $locatie = implode(', ', $locaties);

        // setting everything to the view
        $this->set(compact(
            'bijzonderheden',
            'locatiehoofd',
            'klant_naam',
            'locatie',
            'adres',
            'postcode',
            'woonplaats',
            'redenen',
            'opmerking_uit_schorsing',
            'begindatum_schorsing',
            'einddatum_schorsing_pp',
            'lengte_schorsing',
            'geslacht'
        ));

        $this->layout = 'pdf'; //this will use the pdf.ctp layout
        if ($eng) {
            $this->render('/schorsingen/get_eng_pdf');
        }
    }
}

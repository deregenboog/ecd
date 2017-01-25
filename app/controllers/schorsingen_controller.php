<?php

class SchorsingenController extends AppController
{
    public $name = 'Schorsingen';

    public function index($klant_id, $locatie_id = null)
    {
        if ($klant_id == null && $locatie_id == null) {
            $this->flashError(__('Missing client id and location id', true));
            $this->redirect(array('controller' => 'klanten')); //TODO: redirect to correct place
        }
        $this->Schorsing->recursive = -1;

        $active_schorsingen = $this->Schorsing->getActiveSchorsingen($klant_id);
        $expired_schorsingen = $this->Schorsing->getExpiredSchorsingen($klant_id);

        $klant = $this->Schorsing->Klant->find('first', array(
            'conditions' => array('Klant.id' => $klant_id),
            'contain' => $this->Schorsing->Klant->contain,
            'fields' => array('voornaam', 'tussenvoegsel', 'achternaam',
                'roepnaam', 'geboortedatum', 'BSN', 'laatste_TBC_controle', 'id',
            ),
        ));

        $this->set(compact('active_schorsingen', 'expired_schorsingen', 'klant'));
        $this->set(compact('locatie_id', 'klant_id'));
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('schorsing', $this->Schorsing->read(null, $id));
    }

    public function add($klant_id = null, $locatie_id = null)
    {
        if ($klant_id == null && $locatie_id == null) {
            $this->flashError(__('Missing client id and location id', true));
            $this->redirect(array('controller' => 'klanten')); //TODO: redirect to correct place
        }
        $redenen = $this->Schorsing->Reden->get_schorsing_redenen();

        if (!empty($this->data)) {

            //changing the days fields data into dates:
            if (!$this->Schorsing->calculateDates($this->data)) {
                $this->flashError(__('Please choose the number of days', true));
            } else {
                $this->Schorsing->create();

                if ($this->Schorsing->save($this->data)) {
                    //debug($this->data); die;
                    $this->flash(__('The schorsing has been saved', true));
                    $redirect_url = array('action' => 'index', $klant_id);
                    $addresses = [];
                    $addresses[] = Configure::read('agressie_mail');
                    if (isset($locatie_id)) {
                        $redirect_url[] = $locatie_id;
                    }

                    if (!isset($this->Medewerkers)) {
                        $this->Medewerker = ClassRegistry::init('Medewerker');
                    }
                    $medewerkers = $this->Medewerker->getMedewerkers(null, null, true);
                    $medewerker = $medewerkers[$this->Session->read('Auth.Medewerker.id')];
                    $klant = [];
                    $locatie = $this->Schorsing->Locatie->getById($this->data['Schorsing']['locatie_id']);
                    $content = [];
                    $content['Message'] = array('dit is een mail verstuurd nav een fysieke of verbale agressie schorsing');
                    $content['medewerker'] = $medewerker;
                    $content['Schorsing'] = $this->data['Schorsing'];
                    if (is_array($content['Schorsing']['datum_tot'])) {
                        $content['Schorsing']['datum_tot'] = $content['Schorsing']['datum_tot']['year'].'-'.$content['Schorsing']['datum_tot']['month'].'-'.$content['Schorsing']['datum_tot']['day'];
                    }
                    $content['Schorsing']['reden'] = '';
                    foreach ($this->data['Reden']['Reden'] as $reden) {
                        $content['Schorsing']['reden'] .= $redenen[$reden].', ';
                    }
                    $options_medewerker = Configure::read('options_medewerker');
                    if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker'] ])) {
                        $content['Schorsing']['aggressie_tegen_medewerker'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker'] ];
                    }
                    if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker2'] ])) {
                        $content['Schorsing']['aggressie_tegen_medewerker2'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker2'] ];
                    }
                    if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker3'] ])) {
                        $content['Schorsing']['aggressie_tegen_medewerker3'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker3'] ];
                    }
                    if (isset($options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker4'] ])) {
                        $content['Schorsing']['aggressie_tegen_medewerker4'] = $options_medewerker[$content['Schorsing']['aggressie_tegen_medewerker4'] ];
                    }
                    //$content['Reden'] = $this->
                    $content['Schorsing']['locatie_naam'] = $locatie['naam'];
                    if (!empty($content['Schorsing']['aangifte'])) {
                        $content['Schorsing']['aangifte'] = 'ja';
                    } else {
                        $content['Schorsing']['aangifte'] = 'nee';
                    }
                    $content['Schorsing']['locatie_naam'] = $locatie['naam'];
                    if (!empty($content['Schorsing']['nazorg'])) {
                        $content['Schorsing']['nazorg'] = 'ja';
                    } else {
                        $content['Schorsing']['nazorg'] = 'nee';
                    }
                    unset($content['Schorsing']['id']);
                    unset($content['Schorsing']['locatie_id']);
                    unset($content['Schorsing']['klant_id']);
                    unset($content['Schorsing']['id']);
                    $content['Klant'] = $this->Schorsing->Klant->getAllById($klant_id, array('Geslacht'));

                    $url = array('controller' => 'schorsingen', 'action' => 'index', $klant_id, $locatie_id);
                    $content['url'] = Router::url($url, true);
                    $this->_genericSendEmail(array(
                            'to' => $addresses,
                            'content' => $content,
                            'template' => 'agressie',
                            'subject' => "Agressiemelding {$locatie['naam']}, door {$medewerker}",
                    ));
                    $this->redirect($redirect_url);
                } else {
                    $this->flashError(__('The schorsing could not be saved. Please, try again.', true));
                }
            }
        }

        if ($klant_id != null) {
            //$redenen = $this->Schorsing->Reden->find('list');

            $violent_options = $this->Schorsing->Reden->get_violent_options();
            $this->Schorsing->Klant->recursive = -1;
            $klant = $this->Schorsing->Klant->find('first', array(
                'conditions' => array('Klant.id' => $klant_id),
                'fields' => array('voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam', 'id'),
            ));

            //if locatie_id is given we add schorsing to a particular locatie
            //and the locatie_id field is hidden in the form
            //otherwise we provide a dropdown with locations
            if ($locatie_id != null) {
                $locatie = $this->Schorsing->Locatie->find('first', array(
                    'conditions' => array('Locatie.id' => $locatie_id),
                    'recursive' => '-1',
                ));
                $this->set(compact('locatie_id', 'locatie'));
            } else {
                $this->set('locaties', $this->Schorsing->Locatie->find('list'));
            }

            $this->set(compact('klant_id', 'redenen', 'klant', 'violent_options'));
        } else { //when there's some id missing
            //$klant_id = $locatie_id = 1;
            $this->flashError(__('missing klant_id.', true));
            //$this->redirect(array('controller' => 'registraties', 'action' => 'index'));
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Schorsing->save($this->data)) {
                $this->flash(__('The schorsing has been saved', true));
                $this->redirect(array('action' => 'index', $this->data['Schorsing']['klant_id']));
            } else {
                $this->flashError(__('The schorsing could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Schorsing->read(null, $id);
        }
        $violent_options = $this->Schorsing->Reden->get_violent_options();
        $locaties = $this->Schorsing->Locatie->find('list');
        $klanten = $this->Schorsing->Klant->find('list');
        $redenen = $this->Schorsing->Reden->find('list');
        $this->set(compact('locaties', 'klanten', 'redenen', 'violent_options'));
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for schorsing', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Schorsing->delete($id)) {
            $this->flashError(__('Schorsing deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->flashError(__('Schorsing was not deleted', true));
        $this->redirect(array('action' => 'index'));
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

//		  Configure::write('debug', 0);

        if (empty($schorsing_id)) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect('/');
        }

        $schorsing = &$this->Schorsing->find('first', array(
            'conditions' => array('Schorsing.id' => $schorsing_id),
            'contain' => array(
                'Klant' => array(
                    'fields' => array('name'),
                    'LasteIntake' => array(
                        'fields' => array('postcode', 'woonplaats', 'postadres'),
                    ),
                    'Geslacht' => array(
                        'fields' => array('afkorting'),
                    ),
                ),
                'Locatie' => array(
                    'fields' => array('naam'),
                ),
            ),
        ));
        if (empty($schorsing)) {
            $this->flashError(__('Invalid schorsing', true));
            $this->redirect('/');
        }
        $opmerking_uit_schorsing = $schorsing['Schorsing']['remark'];
        $bijzonderheden = $schorsing['Schorsing']['bijzonderheden'];
        $locatiehoofd = $schorsing['Schorsing']['locatiehoofd'];

    //schorsing data:
        //note

        //dates

        //schorsing start date
        $begindatum_schorsing = $schorsing['Schorsing']['datum_van'];

        //calculating the other times

        $begin = new DateTime($schorsing['Schorsing']['datum_van']);

        //schorsing end date
        $end = new DateTime($schorsing['Schorsing']['datum_tot']);

        //for english version we use DateTime format to get the proper format
        //of the dates
        if ($eng) {
            $format = 'F j, Y';
            $begindatum_schorsing = $begin->format($format);

        //for Dutch we just send DB date format back to the view where it's
        //formatted
        } else {
            $format = 'Y-m-d';
        }

        //a day when the schorsing will have expired:
        $end->add(new DateInterval('P1D'));
        $einddatum_schorsing_pp = $end->format($format);

        //calculating the period
        $difference = $end->diff($begin);
        $lengte_schorsing = $difference->format('%a');

        //formatting the text dependend on the number of days:
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

    //client data
        $klant_naam = $schorsing['Klant']['name'];
        $locatie = $schorsing['Locatie']['naam'];
        $adres = $schorsing['Klant']['LasteIntake']['postadres'];
        $postcode = $schorsing['Klant']['LasteIntake']['postcode'];
        $woonplaats = $schorsing['Klant']['LasteIntake']['woonplaats'];
        $geslacht = $schorsing['Klant']['Geslacht']['afkorting'];

    //setting everything to the view
        $this->set(compact(
            'bijzonderheden', 'locatiehoofd',
            'klant_naam', 'locatie', 'adres', 'postcode', 'woonplaats',
            'opmerking_uit_schorsing', 'begindatum_schorsing',
            'einddatum_schorsing_pp', 'lengte_schorsing', 'geslacht'
        ));

        $this->layout = 'pdf'; //this will use the pdf.ctp layout
        if ($eng) {
            $this->render('/schorsingen/get_eng_pdf');
        }
    }
}

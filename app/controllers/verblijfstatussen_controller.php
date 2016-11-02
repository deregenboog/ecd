<?php

class VerblijfstatussenController extends AppController
{
    public $name = 'Verblijfstatussen';

    public function index()
    {
        $this->Verblijfstatus->recursive = 0;
        $this->set('verblijfstatussen', $this->paginate());
    }

    public function view($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid verblijfstatus', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('verblijfstatus', $this->Verblijfstatus->read(null, $id));
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->Verblijfstatus->create();
            if ($this->Verblijfstatus->save($this->data)) {
                $this->flashError(__('The verblijfstatus has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The verblijfstatus could not be saved. Please, try again.', true));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->flashError(__('Invalid verblijfstatus', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Verblijfstatus->save($this->data)) {
                $this->flashError(__('The verblijfstatus has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->flashError(__('The verblijfstatus could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Verblijfstatus->read(null, $id);
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            $this->flashError(__('Invalid id for verblijfstatus', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Verblijfstatus->delete($id)) {
            $this->flashError(__('Verblijfstatus deleted', true));
            $this->redirect(array('action'=>'index'));
        }
        $this->flashError(__('Verblijfstatus was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    /*
     * This method updates the verblijfstatussen table with new metadata
     * and takes care to update the intakes accordingly. These are the changes
     * that it makes to the DB:
     *
     *	 The dropdown needs to have 5 options:
     *	 “Legaal”,
     *	 “Illegaal (uit buiten Europa)”,
     *	 “Niet rechthebbend (uit Europa, behalve Nederland)”,
     *	 “Niet rechthebbend (uit Nederland, behalve Amsterdam)”
     *	 “Onbekend”.
     *
     *	 Conversion:
     *	 'zonder verblijfsvergunning' => 'niet rechthebbend' (to determine which
     *	 of the two, we check whether the klant is from The Netherlands or not)
     *	 'werkvergunning' => 'onbekend'
     *
     *	The method is supposed to be single use. Though it doesn't break
     *	anything if you run it several times.
     *	To run it go to /verblijfstatussen/update (only for developers) and do
     *	not mind the "missing view" errors.
     *
     *	NOTE: It doesn't remove the old statuses from the table - this is left
     *		to be done manually, just in case. To remove the unwanted records
     *		go to /verblijfstatussen/ (baked - only for developers).
     *
     */

    public function update()
    {

      //setting debug to be able to see what's going on:
        Configure::write('debug', 1);

        $this->Verblijfstatus->Intake->Klant->Geboorteland->recursive = -1;
        $eu = $this->Verblijfstatus->Intake->Klant->Geboorteland->find('list', array(
            'conditions'=> array('Geboorteland.land' => array(
                'België', 'Bulgarije', 'Cyprus', 'Denemarken', 'Duitsland',
                'Estland', 'Finland', 'Frankrijk', 'Griekenland', 'Hongarije',
                'Ierland', 'Italië', 'Letland', 'Litouwen', 'Luxemburg',
                'Malta', 'Nederland', 'Oostenrijk', 'Polen', 'Portugal',
                'Roemenië', 'Slovenië', 'Slowakije', 'Spanje', 'Tsjechië',
                'Zweden', 'Grootbrittannië', 'Bondsrepubliek Duitsland',
            )),
            'order' => 'Geboorteland.land ASC',
        ));
        if (count($eu) < 27) {
            debug('Couldn\'t find all the EU countries!');
        }
        debug('EU countries found:');
        debug($eu);

        debug('If you want to update the verblijfstatuses first BACKUP the DB.'.
            ' Then go to the code and remove this line! (safety measure)');
        die;

        $eu = array_keys($eu);

        debug('1. new fields:');

      //renaming field 'zonder verblijfsvergunning' => 'niet rechthebbend'
        $this->Verblijfstatus->recursive = -1;
        $renamed =
            & $this->Verblijfstatus->findByNaam('Zonder verblijfsvergunning');

        debug('- renaming field \'zonder verblijfsvergunning\' => \'niet rechthebbend\'');
        if (!empty($renamed)) {
            $renamed['Verblijfstatus']['naam'] =
                'Niet rechthebbend (uit Nederland, behalve Amsterdam)';
            if (!($this->Verblijfstatus->save($renamed))) {
                debug('Something went wrong, check the database and the code!');
            }
            debug('renamed:');
            debug($renamed['Verblijfstatus']['naam']);
            $niet_recht_nl_id = $renamed['Verblijfstatus']['id']; //just to be sure
        } else {
            $niet_recht_nl = $this->Verblijfstatus->find('first', array(
                'conditions' => array(
                    'Verblijfstatus.naam' => 'Niet rechthebbend (uit Nederland, behalve Amsterdam)',
                ),
            ));
            $niet_recht_nl_id = $niet_recht_nl['Verblijfstatus']['id'];
        }

      //creating new fields:
      //	'Illegaal (uit buiten Europa)' (id 6)
      //	and 'Niet rechthebbend (uit Europa, behalve Nederland)' (id 7)
        debug("- creating new field:\nIllegaal (uit buiten Europa)' (id 6)");
        $illegal = array(
            'Verblijfstatus' => array(
                'id' => 6,
                'naam' => 'Illegaal (uit buiten Europa)',
            ),
        );

        if (!($this->Verblijfstatus->save($illegal))) {
            debug('Something went wrong, check the database and the code!');
        }

        $niet_rech = array(
            'Verblijfstatus' => array(
                'id' => 7,
                'naam'=>'Niet rechthebbend (uit EU, behalve Nederland)',
            ),
        );

        debug("- creating new field:\n'Niet rechthebbend (uit Europa, behalve Nederland)' (id 7)");
        if (!($this->Verblijfstatus->save($niet_rech))) {
            debug('Something went wrong, check the database and the code!');
        } else {
            $niet_recht_europa_id = $this->Verblijfstatus->id; //just to be sure
        }

        $niet_rech = array(
            'Verblijfstatus' => array(
                'id' => 8,
                'naam'=>'Niet rechthebbend (niet uit EU)',
            ),
        );

        debug("- creating new field:\n'Niet rechthebbend (niet uit EU)' (id 8)");
        if (!($this->Verblijfstatus->save($niet_rech))) {
            debug('Something went wrong, check the database and the code.');
        } else {
            $niet_recht_world_id = $this->Verblijfstatus->id; //just to be sure
        }

      //updating intakes
        debug('3. updating intakes:');

      //'werkvergunning' => 'onbekend'
        debug('- "werkvergunning" => "onbekend"');
        $intakes2update = & $this->Verblijfstatus->Intake->find('all', array(
            'conditions' => array('Verblijfstatus.naam' => 'Werkvergunning'),
            'contain' => array(
                'Verblijfstatus' => array('fields' => array('naam')),
            ),
        ));
      //getting the id of the right verblijfstatus
        $new_verblijfstatus = $this->Verblijfstatus->find('first', array(
            'conditions' => array('naam' => 'Onbekend'),
            'fields' => array('id'),
        ));

        debug('intakes to update:');
        debug(count($intakes2update));

        foreach ($intakes2update as &$intake) {
            unset($intake['Verblijfsatus']);
            $intake['Intake']['verblijfstatus_id'] = $new_verblijfstatus['Verblijfstatus']['id'];
            debug($intake);
            if (!$this->Verblijfstatus->Intake->save($intake)) {
                debug('intake '.$intake['Intake']['id'].
                    ': Something went wrong!');
            } else {
                debug('intake '.$intake['Intake']['id'].': updated');
            }
        }

      //determining whether it should be "Niet rechthebbend (uit Europa,
      //behalve Nederland)" or "Niet rechthebbend (uit Nederland, behalve
      //Amsterdam)" and updating each intake accordingly.
      //Also fixing intakes that had the statuses misassigned initially.

        debug('- "Niet rechthebbend (uit Europa, behalve Nederland)" / "Niet '.
            ' rechthebbend (uit Nederland, behalve Amsterdam)" / "Niet '.
            'rechthebbend (behalve EU)"');
        $intakes2update = &
            $this->Verblijfstatus->Intake->find('all', array(
                'conditions' => array(
                    'Verblijfstatus.naam' => array(
                        'Niet rechthebbend (uit Nederland, behalve Amsterdam)',
                        'Niet rechthebbend (uit EU, behalve Nederland)',
                        'Niet rechthebbend (niet uit EU)',
                    ),
                ),
                'contain' => array(
                    'Klant' => array(
                        'Geboorteland' => array('fields' => 'land', 'id'),
                    ),
                    'Verblijfstatus' => array('fields' => array('naam')),
                ),
            ));
    //for each of the "suspect" intakes, check where the client was born
    //and change the verblijfstatus accordingly
        $this->Verblijfstatus->Intake->recursive = -1;
        foreach ($intakes2update as &$intake) {
            $save = true;
          //clients born in the NL
            if ($intake['Klant']['Geboorteland']['land'] == 'Nederland') {
                //if the status is already correct - do not save
                if ($intake['Intake']['verblijfstatus_id'] == $niet_recht_nl_id) {
                    $save = false;
                } else { //otherwise update the status id and save
                    $intake['Intake']['verblijfstatus_id'] = $niet_recht_nl_id;
                }
          //clients born in the EU
            } elseif (in_array($intake['Klant']['Geboorteland']['id'], $eu)) {
                if ($intake['Intake']['verblijfstatus_id'] == $niet_recht_europa_id) {
                    $save = false;
                } else {
                    $intake['Intake']['verblijfstatus_id'] = $niet_recht_europa_id;
                }
          //clients from outside of the EU
            } else {
                if ($intake['Intake']['verblijfstatus_id'] == $niet_recht_world_id) {
                    $save = false;
                } else {
                    $intake['Intake']['verblijfstatus_id'] = $niet_recht_world_id;
                }
            }
          //save and report it
            if ($save) {
                $msg_str = 'intake '.$intake['Intake']['id'].' (klant_id: '.
                    $intake['Klant']['id'].' geboorteland '.
                    $intake['Klant']['Geboorteland']['land'].'): ';
                if ($this->Verblijfstatus->Intake->save($intake)) {
                    $msg_str .= 'updated - verblijfstatus_id = '.
                        $intake['Intake']['verblijfstatus_id'];
                    $id = $this->Verblijfstatus->Intake->id;
                } else {
                    $msg_str .= 'something went wrong!';
                }
                debug($msg_str);
            }
        }//foreach
    }
}

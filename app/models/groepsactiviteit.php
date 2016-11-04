<?php

class groepsactiviteit extends AppModel
{
    public $name = 'Groepsactiviteit';
    public $displayField = 'naam';

    public $actsAs = array('Containable');

    public $belongsTo = array(
        'GroepsactiviteitenGroep' => array(
            'className' => 'GroepsactiviteitenGroep',
            'foreignKey' => 'groepsactiviteiten_groep_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $hasMany = array(
        'GroepsactiviteitenVrijwilliger' => array(
            'className' => 'GroepsactiviteitenVrijwilliger',
            'foreignKey' => 'groepsactiviteit_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
        'GroepsactiviteitenKlant' => array(
            'className' => 'GroepsactiviteitenKlant',
            'foreignKey' => 'groepsactiviteit_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ),
    );

    public $paginate = array(
        'contain' => array(
            'GroepsactiviteitenGroep',
        ),
    );

    public $validate = array(
        'groepsactiviteiten_groep_id' => array(
            'notempty' => array(
                    'rule' => array(
                            'notEmpty',
                    ),
                    'message' => 'Voer een groep in',
                    'allowEmpty' => false,
                    'required' => true,
            ),
        ),
        'datum' => array(
            'notempty' => array(
                'rule' => array(
                    'notEmpty',
                ),
                'message' => 'Voer een datum in',
                'allowEmpty' => false,
                'required' => true,
            ),
        ),
        'naam' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een naam in',
                            'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
    );

    public function addCount($groepsactiviteiten)
    {
        foreach ($groepsactiviteiten as $key => $g) {
            $groepsactiviteiten[$key]['Groepsactiviteit']['klanten_count'] =
                $this->GroepsactiviteitenKlant->get_count($g['Groepsactiviteit']['id']);
            $groepsactiviteiten[$key]['Groepsactiviteit']['vrijwilligers_count'] =
                $this->GroepsactiviteitenVrijwilliger->get_count($g['Groepsactiviteit']['id']);
        }

        return $groepsactiviteiten;
    }

    public function groeps_activiteiten()
    {
        $result = $this->find('all', array(
            'contain' => array(),
        ));

        return $result;
    }

    public function groeps_activiteiten_list($active_groeps = array())
    {
        $result = array();
        $data = $this->find('all', array(
                'contain' => array('GroepsactiviteitenGroep'),
                'order' => 'GroepsactiviteitenGroep.naam asc, Groepsactiviteit.datum asc',

        ));
        $all = $this->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $keys = array_keys($all);
        $group = array();

        foreach ($data as $tmp) {
            $date = '';

            if (!empty($tmp['Groepsactiviteit']['datum'])) {
                $date = substr($tmp['Groepsactiviteit']['datum'], 8, 2).'-'.substr($tmp['Groepsactiviteit']['datum'], 5, 2).'-'.substr($tmp['Groepsactiviteit']['datum'], 0, 4);
            }

            if (!empty($date)) {
                $date = " ({$date})";
            }

            $werkgebied = '';

            if (!empty($tmp['GroepsactiviteitenGroep']['werkgebied'])) {
                $werkgebied = ' ('.$tmp['GroepsactiviteitenGroep']['werkgebied'].')';
            }

            $group[$tmp['GroepsactiviteitenGroep']['naam'].$werkgebied][$tmp['Groepsactiviteit']['id']] = $tmp['Groepsactiviteit']['naam'].$date;
        }

        return $group;
    }

    public function get_personen($data, $only_email = false)
    {
        $personen = array();
        $geslachten = array();

        if (in_array('Klant', $data['Groepsactiviteit']['persoon_model'])) {
            if (!isset($this->Klant)) {
                App::import('Model', 'Klant');
                $this->Klant = new Klant();
            }

            $klanten = $this->Klant->get_selectie($data, $only_email);

            if ($klanten) {
                foreach ($klanten as $klant) {
                    $klant['Klant']['model'] = 'Klant';
                    $klant['Klant']['gezin_met_kinderen'] = $klant['GroepsactiviteitenIntake']['gezin_met_kinderen'];
                    $klant['Klant']['startdatum'] = $klant[0]['startdatum'];
                    $klant['Klant']['intakedatum'] = $klant['GroepsactiviteitenIntake']['intakedatum'];
                    $klant['Klant']['afsluitdatum'] = $klant['GroepsactiviteitenIntake']['afsluitdatum'];

                    $personen[] = $klant['Klant'];
                }
            }

            if (empty($geslachten)) {
                $geslachten = $this->Klant->Geslacht->find('list');
            }
        }
        if (in_array('Vrijwilliger', $data['Groepsactiviteit']['persoon_model'])) {
            if (!isset($this->Vrijwilliger)) {
                App::import('Model', 'Vrijwilliger');
                $this->Vrijwilliger = new Vrijwilliger();
            }

            $vrijwilligers = $this->Vrijwilliger->get_selectie($data, $only_email);

            if ($vrijwilligers) {
                foreach ($vrijwilligers as $vrijwilliger) {
                    $vrijwilliger['Vrijwilliger']['model'] = 'Vrijwilliger';
                    $vrijwilliger['Vrijwilliger']['gezin_met_kinderen'] = '';
                    $vrijwilliger['Vrijwilliger']['intakedatum'] = '';
                    $vrijwilliger['Vrijwilliger']['afsluitdatum'] = '';

                    if (isset($vrijwilliger['GroepsactiviteitenIntake'])) {
                        $vrijwilliger['Vrijwilliger']['startdatum'] = $vrijwilliger[0]['startdatum'];
                        $vrijwilliger['Vrijwilliger']['intakedatum'] = '';
                        $vrijwilliger['Vrijwilliger']['afsluitdatum'] = $vrijwilliger['GroepsactiviteitenIntake']['afsluitdatum'];
                    }

                    $personen[] = $vrijwilliger['Vrijwilliger'];
                }
            }

            if (empty($geslachten)) {
                $geslachten = $this->Vrijwilliger->Geslacht->find('list');
            }
        }

        foreach ($personen as $key => $persoon) {
            $personen[$key]['geslacht'] = $geslachten[$persoon['geslacht_id']];
        }

        return $personen;
    }
}

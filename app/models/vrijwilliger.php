<?php

class vrijwilliger extends AppModel
{
    public $name = 'Vrijwilliger';
    public $displayField = 'achternaam';

    public $virtualFields = array(
            'name' => "CONCAT_WS(' ', `Vrijwilliger`.`voornaam`, `Vrijwilliger`.`tussenvoegsel`, `Vrijwilliger`.`achternaam`)",
            'name1st_part' => "CONCAT_WS(' ', `Vrijwilliger`.`voornaam`, `Vrijwilliger`.`roepnaam`)",
            'name2nd_part' => "CONCAT_WS(' ', `Vrijwilliger`.`tussenvoegsel`, `Vrijwilliger`.`achternaam`)",
            'klant_nummer' => "CONCAT('V',`Vrijwilliger`.`id`)",
    );

    public $watchfields = array(
        'voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam',
        'geslacht_id', 'geboortedatum', 'land_id', 'nationaliteit_id',
        'BSN', 'adres', 'postcode', 'plaats', 'email', 'mobiel',
        'telefoon', 'geen_post', 'geen_email', 'overleden',
    );

    public $actsAs = array(
            'Containable',
    );

    public $paginate = array(
            'contain' => array('Geslacht'),
            //'limit' => 2,
    );
    public $validate = array(
            'achternaam' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Voer een achternaam in',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ),
            ),
            'land_id' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Kies een land',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ),
            ),
            'medewerker_id' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Kies een medewerker',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ),
            ),
            'nationaliteit_id' => array(
                    'notempty' => array(
                            'rule' => array(
                                    'notEmpty',
                            ),
                            'message' => 'Kies een nationaliteit',
                            //'allowEmpty' => false,
                            'required' => true,
                            //'last' => false, // Stop validation after this rule
                    ),
            ),
            'email' => array(
                    'email' => array(
                            'rule' => array(
                                    'email',
                            ),
                            'message' => 'Een geldig E-Mail adres invoeren',
                            'allowEmpty' => true,
                            'required' => false,
                            //'last' => false, // Stop validation after this rule
                    ),
            ),
    );

    public $belongsTo = array(
        'Geslacht' => array(
            'className' => 'Geslacht',
            'foreignKey' => 'geslacht_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Geboorteland' => array(
            'className' => 'Land',
            'foreignKey' => 'land_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Nationaliteit' => array(
            'className' => 'Nationaliteit',
            'foreignKey' => 'nationaliteit_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'Medewerker' => array(
            'className' => 'Medewerker',
            'foreignKey' => 'medewerker_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
    );

    public $hasMany = array(
        'GroepsactiviteitenVrijwilliger' => array(
            'className' => 'GroepsactiviteitenVrijwilliger',
            'foreignKey' => 'vrijwilliger_id',
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
        'GroepsactiviteitenGroepenVrijwilliger' => array(
            'className' => 'GroepsactiviteitenGroepenVrijwilliger',
            'foreignKey' => 'vrijwilliger_id',
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
        'GroepsactiviteitenVerslag' => array(
            'className' => 'GroepsactiviteitenVerslag',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'GroepsactiviteitenVerslag.model' => 'Vrijwilliger',
            ),
            'order' => 'GroepsactiviteitenVerslag.created DESC',
            'dependent' => true,
        ),
        'GroepsactiviteitenDocument' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'GroepsactiviteitenDocument.model' => 'Vrijwilliger',
                'is_active' => 1,
            ),
            'dependent' => true,
            'order' => 'created desc',
        ),
    );
    public $hasOne = array(
        'IzDeelnemer' => array(
                    'className' => 'IzDeelnemer',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                            'IzDeelnemer.model' => 'Vrijwilliger',
                    ),
                    'order' => '',
                    'dependent' => true,
           ),
            'GroepsactiviteitenIntake' => array(
                    'className' => 'GroepsactiviteitenIntake',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'GroepsactiviteitenIntake.model' => 'Vrijwilliger',
                    ),
                    'order' => '',
                    'dependent' => true,
            ),

    );
    public function beforeSave($options = array())
    {
        if (empty($this->id) && empty($this->data['Vrijwilliger']['id'])) {
            $this->send_admin_email = true;
            $this->changes = $this->data;
            if (isset($this->data['Vrijwilliger'])) {
                $this->changes = array();
                foreach ($this->watchfields as $watch) {
                    if (isset($this->data['Vrijwilliger'][$watch])) {
                        $this->changes[$watch] = $this->data['Vrijwilliger'][$watch];
                    }
                }
            }
        } else {
            $current = $this->getById($this->data['Vrijwilliger']['id']);
            $compare = $this->data;
            if (isset($this->data['Vrijwilliger'])) {
                $compare = $this->data['Vrijwilliger'];
            }
            $this->send_admin_email = false;
            $this->changes = array();
            foreach ($this->watchfields as $watch) {
                if (!isset($current[$watch]) || !isset($compare[$watch])) {
                    continue;
                }
                if ($current[$watch] != $compare[$watch]) {
                    $this->send_admin_email = true;
                    $this->changes[$watch] = $compare[$watch];
                }
            }
        }

        return parent::beforeSave($options);
    }

    public function get_selectie($data, $only_email = false)
    {
        $conditions = array();

        if (!empty($data['Groepsactiviteit']['werkgebieden'])) {
            $conditions['Vrijwilliger.werkgebied'] = $data['Groepsactiviteit']['werkgebieden'];
        }

        if (!empty($only_email)) {
            $conditions['email NOT'] = null;
            $conditions['email NOT'] = '';
        }

        $join_conditions = array(
                'Vrijwilliger.id = GroepsactiviteitenGroepenVrijwilliger.vrijwilliger_id',
        );

        $join_table = Inflector::pluralize(Inflector::underscore('GroepsactiviteitenGroepenVrijwilliger'));

        if (!empty($data['Groepsactiviteit']['activiteitengroepen'])) {
            $join_conditions['GroepsactiviteitenGroepenVrijwilliger.groepsactiviteiten_groep_id'] = $data['Groepsactiviteit']['activiteitengroepen'];
        }

        if (!empty($data['Groepsactiviteit']['communicatie_type'])) {
            $or = array();
            if (in_array('communicatie_email', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenVrijwilliger.communicatie_email'] = 1;
            }

            if (in_array('communicatie_post', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenVrijwilliger.communicatie_post'] = 1;
            }

            if (in_array('communicatie_telefoon', $data['Groepsactiviteit']['communicatie_type'])) {
                $or['GroepsactiviteitenGroepenVrijwilliger.communicatie_telefoon'] = 1;
            }

            $join_conditions['OR'] = $or;
        }

        $join_conditions['OR'] = array(
                'GroepsactiviteitenGroepenVrijwilliger.einddatum' => null,
                'GroepsactiviteitenGroepenVrijwilliger.einddatum >=' => date('Y-m-d'),
        );

        $contain = array('GroepsactiviteitenIntake');

        $joins = array();

        $joins[] = array(
            'table' => $join_table,
            'alias' => 'GroepsactiviteitenGroepenVrijwilliger',
            'type' => 'INNER',
            'conditions' => $join_conditions,

        );

        $options = array(
            'conditions' => $conditions,
            'joins' => $joins,
            'contain' => $contain,
            'group' => array('Vrijwilliger.id'),
            'fields' => array('id', 'voornaam', 'tussenvoegsel', 'achternaam', 'roepnaam', 'geslacht_id',
                'geboortedatum', 'land_id', 'nationaliteit_id', 'BSN', 'medewerker_id', 'adres',
                'postcode', 'werkgebied', 'postcodegebied', 'plaats', 'email', 'mobiel', 'telefoon',
                'opmerking', 'geen_post', 'geen_email', 'disabled', 'created', 'modified', 'geen_email', 'name',
                'name1st_part', 'name2nd_part', 'klant_nummer',
                'GroepsactiviteitenIntake.id',
                'GroepsactiviteitenIntake.model',
                'GroepsactiviteitenIntake.foreign_key',
                'GroepsactiviteitenIntake.groepsactiviteiten_afsluiting_id',
                'GroepsactiviteitenIntake.medewerker_id',
                'GroepsactiviteitenIntake.gespreksverslag',
                'GroepsactiviteitenIntake.ondernemen',
                'GroepsactiviteitenIntake.overdag',
                'GroepsactiviteitenIntake.ontmoeten',
                'GroepsactiviteitenIntake.regelzaken',
                'GroepsactiviteitenIntake.informele_zorg',
                'GroepsactiviteitenIntake.dagbesteding',
                'GroepsactiviteitenIntake.inloophuis',
                'GroepsactiviteitenIntake.hulpverlening',
                'GroepsactiviteitenIntake.gezin_met_kinderen',
                'GroepsactiviteitenIntake.intakedatum',
                'GroepsactiviteitenIntake.afsluitdatum',
                'count(*) as count',
                'min(GroepsactiviteitenGroepenVrijwilliger.startdatum) as startdatum',
            ),
        );

        $personen = $this->find('all', $options);

        return $personen;
    }
}

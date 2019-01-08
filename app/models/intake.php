<?php

class Intake extends AppModel
{
    public $name = 'Intake';

    public $order = 'datum_intake DESC';

    public $validate = [
        'doelgroep' => [
            'notempty' => [
                'rule' => 'notEmpty',
                'message' => 'Dit veld is verplicht',
                'allowEmpty' => false,
                'required' => true,
            ],
        ],
    ];

    public function dateNotInFuture($date)
    {
        $datevalue = array_values($date);
        if (strtotime($datevalue[0]) < strtotime('now')) {
            return true;
        } else {
            return false;
        }
    }

    public function getIntakeCountForClant($id)
    {
        return $this->find('count', [
            'conditions' => ['klant_id' => $id], ]);
    }

    public function afterSave($created)
    {
        parent::afterSave($created);

        $k_id = $this->data['Intake']['klant_id'];
        $klant = $this->Klant->read(null, $k_id);

        $last_intake_id = $klant['Intake'][0]['id'];
        $this->Klant->saveField('laste_intake_id', $last_intake_id);
        if (isset($this->data['Intake']['datum_intake']) && !empty($this->data['Intake']['datum_intake'])) {
            if (empty($klant['Klant']['first_intake_date'])) {
                $this->Klant->saveField('first_intake_date', $this->data['Intake']['datum_intake']);
            } else {
                $f_date = strtotime($klant['Klant']['first_intake_date']);
                $n_date = strtotime($this->data['Intake']['datum_intake']);
                if ($n_date < $f_date) {
                    $this->Klant->saveField('first_intake_date', $this->data['Intake']['datum_intake']);
                }
            }
        }
    }

    public function set_last_intake($klant_id)
    {
        if (empty($klant_id)) {
            return false;
        }

        $this->Klant->recursive = -1;
        if ($this->Klant->read('laste_intake_id', $klant_id)) {
            return false;
        }

        $this->Klant->set('laste_intake_id', $this->id);
        if ($this->Klant->save()) {
            return false;
        }

        return true;
    }

    public function uniqueForUser($data)
    {
        $conditions = [
            'Intake.klant_id' => $this->data['Intake']['klant_id'],
            'Intake.datum_intake' => $data['datum_intake'],
        ];
        if (array_key_exists('id', $this->data['Intake'])) {
            $conditions['Intake.id !='] = $this->data['Intake']['id'];
        }
        $check = $this->find('count', ['conditions' => $conditions]);
        if ($check > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function completeKlantenIntakesWithLocationNames($klanten)
    {
        if (!$klanten) {
            return $klanten;
        }

        foreach ($klanten as $key => $k) {
            if (!isset($k[$this->alias])) {
                continue;
            }

            for ($li = 1; $li < 4; ++$li) {
                $relation = 'Locatie'.$li;
                $relInfo = $this->belongsTo[$relation];
                $field = $relInfo['foreignKey'];
                if (empty($k[$this->alias][$field])) {
                    continue;
                }
                $relId = $k[$this->alias][$field];
                $rel = $this->$relation->getById($relId);
                $klanten[$key][$this->alias][$relation] = $rel;
            }
        }

        return $klanten;
    }
}

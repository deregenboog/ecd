<?php

class PfoAardRelatie extends AppModel
{
    public $name = 'PfoAardRelatie';
    public $displayField = 'name';

    public function get_list($current_id = null)
    {
        $dr = [];
        $all = $this->find('all', [
        ]);
        $now = time();
        foreach ($all as $a) {
            $startdatum = strtotime($a['PfoAardRelatie']['startdatum']);
            $einddatum = strtotime($a['PfoAardRelatie']['einddatum']);

            $actual = true;
            if (!empty($startdatum)) {
                if ($now < $startdatum) {
                    $actual = false;
                }
            }
            if (!empty($einddatum)) {
                if ($now > $einddatum) {
                    $actual = false;
                }
            }

            if (!empty($current_id)) {
                $dr[$a['PfoAardRelatie']['id']] = $a['PfoAardRelatie']['naam'];
                if ($actual == false) {
                    $dr[$a['PfoAardRelatie']['id']] = $a['PfoAardRelatie']['naam'].' (verlopen)';
                }
            } else {
                if ($actual == true) {
                    $dr[$a['PfoAardRelatie']['id']] = $a['PfoAardRelatie']['naam'];
                }
            }
        }

        return $dr;
    }
}

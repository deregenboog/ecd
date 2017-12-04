<?php

class PfoGroep extends AppModel
{
    public $name = 'PfoGroep';
    public $displayField = 'naam';

    public function get_list($current_id = null)
    {
        $dr = [];
        $all = $this->find('all', [
        ]);
        $now = time();
        foreach ($all as $a) {
            $startdatum = strtotime($a['PfoGroep']['startdatum']);
            $einddatum = strtotime($a['PfoGroep']['einddatum']);

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
                $dr[$a['PfoGroep']['id']] = $a['PfoGroep']['naam'];
                if ($actual == false) {
                    $dr[$a['PfoGroep']['id']] = $a['PfoGroep']['naam'].' (verlopen)';
                }
            } else {
                if ($actual == true) {
                    $dr[$a['PfoGroep']['id']] = $a['PfoGroep']['naam'];
                }
            }
        }
        //debug($dr);
        return $dr;
    }
}

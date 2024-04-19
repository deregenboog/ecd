<?php

namespace OekBundle\Report;

use AppBundle\Report\Table;

class DeelnemersPerTrainingStatus extends DeelnemersPerTrainingGroep
{
    protected $title = 'Deelnemers per naam training en status';

    protected $xPath = 'status';

    protected $xDescription = 'Status';


    protected function build()
    {
        // build data structure (to make sure all statuses are present in all tables)
        $statussen = array_keys($this->tables);
        $tables = [];
        foreach ($this->tables as $table) {
            foreach ($table as $data) {
                foreach ($statussen as $status) {
                    if (!array_key_exists($data['groepnaam'], $tables)) {
                        $groepen[$data['groepnaam']] = [];
                    }
                    $tables[$data['groepnaam']][] = [
                        'trainingnaam' => $data['trainingnaam'],
                        'status' => $status,
                        'aantal' => 0,
                    ];
                }
            }
        }

        // fill structure with actual values
        foreach ($this->tables as $status => $table) {
            foreach ($table as $data) {
                // find this entry in structure and replace value
                array_walk($tables, function (&$table, $groep, $arg) {
                    [$status, $data] = $arg;
                    foreach ($table as &$row) {
                        if ($groep == $data['groepnaam']
                            && $row['trainingnaam'] == $data['trainingnaam']
                            && $row['status'] == $status
                        ) {
                            $row['aantal'] = $data['aantal'];
                        }
                    }
                }, [$status, $data]);
            }
        }

        foreach ($tables as $groep => $table) {
            $table = new Table($table, $this->xPath, $this->yPath, $this->nPath);
            $table->setStartDate($this->startDate)->setEndDate($this->endDate);
            $table->setXSort(false);

            $this->reports[] = [
                'title' => $groep,
                'xDescription' => $this->xDescription,
                'yDescription' => $this->yDescription,
                'data' => $table->render(),
            ];
        }
    }
}

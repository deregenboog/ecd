<?php

namespace AppBundle\Report;

class Ladis extends AbstractSqlFileReport
{
    protected $title = 'Ladis';

    /**
     * Calculate management reports by running the sqls.
     */
    protected function calculateManagementReport()
    {
        $reports = $this->readManagementReportConfig();

        foreach ($reports as $key => $config) {
            if ($config['isDisabled']) {
                continue;
            }
            $sql = $config['sql'];
            $reports[$key]['run'] = $sql;

            $result = $this->em->getConnection()->fetchAll($sql);
            $reports[$key]['result'] = $result;
        }

        return $reports;
    }
}

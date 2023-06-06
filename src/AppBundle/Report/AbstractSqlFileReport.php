<?php

namespace AppBundle\Report;

use Doctrine\ORM\EntityManager;

class AbstractSqlFileReport extends AbstractReport
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $sqlFile;

    protected $params = [];


    public function __construct(EntityManager $em, $sqlFile)
    {
        $this->em = $em;
        $this->sqlFile = $sqlFile;
    }

    protected function init()
    {
        $this->initParams();

        $reports = $this->getData();

        foreach ($reports as $report) {
            if (!array_key_exists('result', $report)) {
                continue;
            }

            $columns = [];
            foreach ($report['fields'] as $value) {
                $columns[$value[1]] = preg_replace('/.*\.(.*)/', '$1', $value[0]);
            }

            $listing = new Listing($report['result'], $columns);
            $listing->setStartDate($this->startDate)->setEndDate($this->endDate);
            $this->reports[] = [
                'title' => $report['head'],
                'yDescription' => current(array_keys($columns)),
                'data' => $listing->render(),
            ];
        }

    }

    private function initParams()
    {
        $this->params += [
            ':from' => sprintf("'%s'", $this->startDate->format('Y-m-d')),
            ':until' =>  sprintf("'%s'", $this->endDate->format('Y-m-d')),
        ];
    }
    protected function build()
    {
        foreach ($this->reports as $i => $report) {
            foreach ($report['data'] as $j => $values) {
                unset($this->reports[$i]['data'][$j]);
                $key = array_shift($values);
                $this->reports[$i]['data'][$key] = $values;
            }
        }
    }

    /**
     * Calculate management reports by running the sqls.
     */
    protected function getData()
    {
        $reports = $this->getConfiguration();

        foreach ($reports as $key => $config) {
            if ($config['isDisabled']) {
                continue;
            }
            $sql = $this->replacePlaceholders($config['sql']);

            if ($sql) {
                $reports[$key]['run'] = $sql;
                $result = $this->em->getConnection()->fetchAllAssociative($sql);
                $reports[$key]['result'] = $result;
            }
        }

        return $reports;
    }

    protected function replacePlaceholders($sql)
    {
        $k = array_keys($this->params);
        $v = array_values($this->params);

        return str_replace(array_keys($this->params), array_values($this->params), $sql);
    }

    /**
     * Reads and parses the report config from the file.
     */
    protected function getConfiguration()
    {
        $reports = [];
        $config = preg_split('/-- START.*\n/m', file_get_contents($this->sqlFile));
        foreach ($config as $report) {
            $report = trim($report);
            if (!$report) {
                continue;
            }

            $matches = [];
            preg_match('/-- HEAD:\s*(.*)\n/m', $report, $matches);
            if (empty($matches[1])) {
                debug('Head not found:');
                debug($report);
            }
            $head = $matches[1];

            $matches = [];
            preg_match('/-- FIELDS:\s*(.*)\n/m', $report, $matches);
            if (empty($matches[1])) {
                debug('Fields not found:');
                debug($report);
            }
            $fields = $matches[1];
            $fields = preg_split("/[\s]*[;][\s]*/", $fields);
            foreach ($fields as $key => $field) {
                $fields[$key] = preg_split("/[\s]*[-][\s]*/", $field, 2);
            }

            $isArray = preg_match('/-- ARRAY/m', $report, $matches);
            $isDisabled = preg_match('/-- DISABLE/m', $report, $matches);
            $hasSummary = preg_match('/-- SUMMARY/m', $report, $matches);

            preg_match_all('/^([^-].*)\n/m', $report, $matches);
            $sql = implode("\n", $matches[1]);

            $reports[] = [
                'head' => $head,
                'fields' => $fields,
                'isArray' => $isArray,
                'isDisabled' => $isDisabled,
                'hasSummary' => $hasSummary,
                'sql' => $sql,
            ];
        }

        return $reports;
    }
}

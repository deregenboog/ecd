<?php

/**
 * Component for running sql reports and displaying them in a table.
 */
class SqlReportComponent extends Component
{
    /**
     * Controller.
     *
     * @var AppController
     */
    private $controller;

    /**
     * Cake callback for component initialization.
     *
     * @param AppController $controller Controller coupled to this component
     * @param array         $settings   Settings for the component
     */
    public function initialize(&$controller, $settings = [])
    {
        $this->controller = $controller;
    }

    /**
     * Reads the management report config from the file and parses them.
     *
     * @param string $file Xml file to load
     */
    private function _readManagementReportConfig($file)
    {
        $reports = [];
        $fieldProperties = ['name', 'label', 'helper', 'function'];
        $queries = simplexml_load_file(APP.'/config/'.$file.'.xml');
        foreach ($queries as $query) {
            $head = $query->head->__toString();
            $fields = [];
            foreach ($query->fields->field as $field) {
                $fld = [];
                foreach ($fieldProperties as $fieldName) {
                    if ($field[$fieldName]) {
                        $fld[$fieldName] = $field[$fieldName]->__toString();
                    }
                }
                $fields[] = $fld;
            }

            $reports[] = [
                'head' => $head,
                'fields' => $fields,
                'isArray' => 'array' == $query['type'],
                'isDisabled' => 1 == $query['disabled'],
                'hasSummary' => 1 == $query['summary'],
                'sql' => $query->sql->__toString(),
            ];
        }

        return $reports;
    }

    /**
     * Calculate management reports by running the sqls.
     *
     * @param array  $condition Conditions from the report filter
     * @param string $file      Xml file to load
     */
    private function _calculateManagementReport($conditions, $file, $datasource = 'default')
    {
        $dataSource = ConnectionManager::getDataSource($datasource);
        $reports = $this->_readManagementReportConfig($file);
        foreach ($reports as $key => $config) {
            if ($config['isDisabled']) {
                continue;
            }
            $sql = String::insert($config['sql'], $conditions);
            $reports[$key]['result'] = $dataSource->query($sql);
        }

        return $reports;
    }

    /**
     * Displays the report output as an ajax response (no layout).
     *
     * @param array  $condition Conditions from the report filter
     * @param string $file      Xml file to load
     */
    public function ajaxDisplay($conditions, $file)
    {
        $reports = $this->_calculateManagementReport($conditions, $file);

        $this->controller->autoLayout = false;

        $this->controller->set(compact('reports'));
        $this->controller->render('ajax_sql_report');
    }

    /**
     * Displays the report output as an excel file.
     *
     * @param array  $condition Conditions from the report filter
     * @param string $file      Xml file to load
     * @param string $excelFile Name of the excel output
     */
    public function excelDisplay($conditions, $file, $excelFile)
    {
        $reports = $this->_calculateManagementReport($conditions, $file);

        $this->controller->autoLayout = false;

        $this->controller->set(compact('reports'));
        $this->layout = false;
        $file = 'management_report.xls';
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$excelFile\";");
        header('Content-Transfer-Encoding: binary');
        $this->controller->render('ajax_excel_report');
    }
}

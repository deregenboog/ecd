<?php

class FilterComponent extends Object
{
    public $fieldFormatting = array(
        "string"    => "LIKE '%s%%'",
        "text"        => "LIKE '%%%s%%'",
        "datetime"    => "LIKE '%%%s%%'",
        "date"    => "LIKE '%%%s%%'",
        'datetimegt' => '%s',
    );

    public $paginatorParams = array(
        'page',
        'sort',
        'direction',
    );

    public $url = '';

    public $filterData = array();

    public $default = array(
        'persoon_model' => 'Klant',
    );

    public function initialize(&$controller,  $settings)
    {
        if ($controller->action != 'index' && $controller->action != 'vrijwilligers_index') {
            return;
        }
        
        $this->default = array_merge($this->default, $settings);
        $this->filterData = $this->process($controller);
        
        $url = $this->url;
        $this->filterDataForRegenboog();

        $url .='/';
        $controller->set('filter_options', array('url'=>array($url)));

        if (isset($controller->data['reset']) || isset($controller->data['cancel'])) {
            $controller->redirect(array('action'=>'index'));
        }
    }

    public function filterDataForRegenboog()
    {
        $persoon_model = $this->default['persoon_model'];
        
        foreach ($this->filterData as $i => $position) {
            if (strpos($position, $persoon_model.'.voornaam') !== false) {
                $roepnaam = str_replace('voornaam', 'roepnaam', $position);
                
                if (isset($this->filterData['OR'])) {
                    array_push($this->filterData['OR'], $position);
                    array_push($this->filterData['OR'], $roepnaam);
                } else {
                    $this->filterData['OR'] = array($position, $roepnaam);
                }
                
                unset($this->filterData[$i]);
            }
            if (strpos($i, $persoon_model.'.show_all') !== false) {
                if ($position) {
                    array_push($this->filterData, true);
                }

                unset($this->filterData[$i]);
            }
        }
    }

    public function process(&$controller, $whiteList = null)
    {
        $controller = $this->_prepareFilter($controller);
        $ret = array();
        if (isset($controller->data)) {
            foreach ($controller->data as $key=>$value) {
                $columns = array();
                
                if (isset($controller->{$key})) {
                    $columns = $controller->{$key}->getColumnTypes();
                } elseif (isset($controller->{$controller->modelClass}->belongsTo[$key])) {
                    $columns = $controller->{$controller->modelClass}->{$key}->getColumnTypes();
                }
                
                if ($key == 'verslagen') {
                    $columns = array('laatste_rapportage' => 'datetimegt');
                }

                if (!empty($columns)) {
                    foreach ($value as $k=>$v) {
                        if ($k === 'rowUrl') {
                            continue;
                        }

                        if (is_array($v) && $columns[$k]=='datetime') {
                            $v = $this->_prepare_datetime($v);
                        }

                        if ($v != '') {
                            if (is_array($whiteList) && !in_array($k, $whiteList)) {
                                continue;
                            }

                            if (isset($columns[$k]) && isset($this->fieldFormatting[$columns[$k]])) {
                                $tmp = sprintf($this->fieldFormatting[$columns[$k]], $v);

                                if (substr($tmp, 0, 4)=='LIKE') {
                                    $ret[] = $key.'.'.$k." ".$tmp;
                                } elseif ($columns[$k] == 'datetimegt') {
                                    $ret[$key.'.'.$k." >= "] = $tmp;
                                } else {
                                    $ret[$key.'.'.$k] = $tmp;
                                }
                            } else {
                                $ret[$key.'.'.$k] = $v;
                            }

                            $this->url .= '/'.$key.'.'.$k.':'.$v;
                        }
                    }

                    if (count($value) == 0) {
                        unset($controller->data[$key]);
                    }
                }
            }
        }

        return $ret;
    }

    public function _prepareFilter(&$controller)
    {
        $filter = array();
        if (isset($controller->data)) {
            foreach ($controller->data as $model=>$fields) {
                if (is_array($fields)) {
                    foreach ($fields as $key=>$field) {
                        if ($field == '') {
                            unset($controller->data[$model][$key]);
                        }
                    }
                }
            }

            App::import('Sanitize');
            
            $sanit = new Sanitize();
            
            $controller->data = $sanit->clean($controller->data, array('encode' => false));
            $filter = $controller->data;
        }

        if (empty($filter)) {
            $filter = $this->_checkParams($controller);
        }
        
        $controller->data = $filter;

        return $controller;
    }

    public function _checkParams(&$controller)
    {
        if (empty($controller->params['named'])) {
            $filter = array();
        }

        App::import('Sanitize');
        $sanit = new Sanitize();
        $controller->params['named'] = $sanit->clean($controller->params['named'], array('encode' => false));

        foreach ($controller->params['named'] as $field => $value) {
            if (!in_array($field, $this->paginatorParams)) {
                $fields = explode('.', $field);
                 
                if (sizeof($fields) == 1) {
                    $filter[$controller->modelClass][$field] = $value;
                } else {
                    $filter[$fields[0]][$fields[1]] = $value;
                }
            }
        }

        if (!empty($filter)) {
            return $filter;
        } else {
            return array();
        }
    }


    public function _prepare_datetime($date)
    {
        $str = '';

        $date = array_reverse($date);

        foreach ($date as $key=>$value) {
            if (!empty($value)) {
                $str .= '-'.$value;

                if ($key=='year') {
                    $str = str_replace('-', '', $str);
                }

                if ($key=='month' && empty($date['day'])) {
                    $str .= '-';
                }

                if ($key=='day') {
                    $str.=' ';
                }
            }
        }

        return $str;
    }

    public function process_datetime($fieldname)
    {
        $selected = null;
        
        if (isset($this->params['named'][$fieldname])) {
            $exploded = explode('-', $this->params['named'][$fieldname]);
            
            if (!empty($exploded)) {
                $selected = '';
                
                foreach ($exploded as $k=>$e) {
                    if (empty($e)) {
                        $selected .= (($k==0) ? '0000' : '00');
                    } else {
                        $selected .= $e;
                    }
                    
                    if ($k!=2) {
                        $selected.='-';
                    }
                }
            }
        }
        
        return $selected;
    }
}

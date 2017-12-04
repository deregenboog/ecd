<?php

class FilterComponent extends Object
{
    public $fieldFormatting = [
        'string' => "LIKE '%s%%'",
        'text' => "LIKE '%%%s%%'",
        'datetime' => "LIKE '%%%s%%'",
        'date' => "LIKE '%%%s%%'",
        'datetimegt' => '%s',
    ];

    public $paginatorParams = [
        'page',
        'sort',
        'direction',
    ];

    public $url = '';

    public $filterData = [];

    public $default = [
        'persoon_model' => 'Klant',
    ];

    public function initialize(&$controller, $settings)
    {
        if ('index' != $controller->action && 'vrijwilligers_index' != $controller->action) {
            return;
        }

        $this->default = array_merge($this->default, $settings);
        $this->filterData = $this->process($controller);

        $url = $this->url;
        $this->filterDataForRegenboog();

        $url .= '/';
        $controller->set('filter_options', ['url' => [$url]]);

        if (isset($controller->data['reset']) || isset($controller->data['cancel'])) {
            $controller->redirect(['action' => 'index']);
        }
    }

    public function filterDataForRegenboog()
    {
        $persoon_model = $this->default['persoon_model'];

        foreach ($this->filterData as $i => $position) {
            if (false !== strpos($position, $persoon_model.'.voornaam')) {
                $roepnaam = str_replace('voornaam', 'roepnaam', $position);

                if (isset($this->filterData['OR'])) {
                    array_push($this->filterData['OR'], $position);
                    array_push($this->filterData['OR'], $roepnaam);
                } else {
                    $this->filterData['OR'] = [$position, $roepnaam];
                }

                unset($this->filterData[$i]);
            }
            if (false !== strpos($i, $persoon_model.'.show_all')) {
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
        $ret = [];
        if (isset($controller->data)) {
            foreach ($controller->data as $key => $value) {
                $columns = [];

                if (isset($controller->{$key})) {
                    $columns = $controller->{$key}->getColumnTypes();
                } elseif (isset($controller->{$controller->modelClass}->belongsTo[$key])) {
                    $columns = $controller->{$controller->modelClass}->{$key}->getColumnTypes();
                }

                if ('verslagen' == $key) {
                    $columns = ['laatste_rapportage' => 'datetimegt'];
                }

                if (!empty($columns)) {
                    foreach ($value as $k => $v) {
                        if ('rowUrl' === $k) {
                            continue;
                        }

                        if (is_array($v) && 'datetime' == $columns[$k]) {
                            $v = $this->_prepare_datetime($v);
                        }

                        if ('' != $v) {
                            if (is_array($whiteList) && !in_array($k, $whiteList)) {
                                continue;
                            }

                            if (isset($columns[$k]) && isset($this->fieldFormatting[$columns[$k]])) {
                                $tmp = sprintf($this->fieldFormatting[$columns[$k]], $v);

                                if ('LIKE' == substr($tmp, 0, 4)) {
                                    $ret[] = $key.'.'.$k.' '.$tmp;
                                } elseif ('datetimegt' == $columns[$k]) {
                                    $ret[$key.'.'.$k.' >= '] = $tmp;
                                } else {
                                    $ret[$key.'.'.$k] = $tmp;
                                }
                            } else {
                                $ret[$key.'.'.$k] = $v;
                            }

                            $this->url .= '/'.$key.'.'.$k.':'.$v;
                        }
                    }

                    if (0 == count($value)) {
                        unset($controller->data[$key]);
                    }
                }
            }
        }

        return $ret;
    }

    public function _prepareFilter(&$controller)
    {
        $filter = [];
        if (isset($controller->data)) {
            foreach ($controller->data as $model => $fields) {
                if (is_array($fields)) {
                    foreach ($fields as $key => $field) {
                        if ('' == $field) {
                            unset($controller->data[$model][$key]);
                        }
                    }
                }
            }

            App::import('Sanitize');

            $sanit = new Sanitize();

            $controller->data = $sanit->clean($controller->data, ['encode' => false]);
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
            $filter = [];
        }

        App::import('Sanitize');
        $sanit = new Sanitize();
        $controller->params['named'] = $sanit->clean($controller->params['named'], ['encode' => false]);

        foreach ($controller->params['named'] as $field => $value) {
            if (!in_array($field, $this->paginatorParams)) {
                $fields = explode('.', $field);

                if (1 == sizeof($fields)) {
                    $filter[$controller->modelClass][$field] = $value;
                } else {
                    $filter[$fields[0]][$fields[1]] = $value;
                }
            }
        }

        if (!empty($filter)) {
            return $filter;
        } else {
            return [];
        }
    }

    public function _prepare_datetime($date)
    {
        $str = '';

        $date = array_reverse($date);

        foreach ($date as $key => $value) {
            if (!empty($value)) {
                $str .= '-'.$value;

                if ('year' == $key) {
                    $str = str_replace('-', '', $str);
                }

                if ('month' == $key && empty($date['day'])) {
                    $str .= '-';
                }

                if ('day' == $key) {
                    $str .= ' ';
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

                foreach ($exploded as $k => $e) {
                    if (empty($e)) {
                        $selected .= ((0 == $k) ? '0000' : '00');
                    } else {
                        $selected .= $e;
                    }

                    if (2 != $k) {
                        $selected .= '-';
                    }
                }
            }
        }

        return $selected;
    }
}

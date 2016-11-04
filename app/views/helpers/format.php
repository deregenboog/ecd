<?php


/*
 * /app/views/helpers/format.php
 *
 *
 * */

class FormatHelper extends AppHelper
{

    /**
     * Date format.
     */
    const DATE = 'date';
    /**
     * Ja oder Nee format.
     */
    const JANEE = 'janee';
    /**
     * Ul list format.
     */
    const UL_LIST = 'ullist';
    /**
     * Ul list with radios.
     */
    const UL_LIST_RADIO = 'ullistradio';
    /**
     * Ul list with checkboxes.
     */
    const UL_LIST_CHECKBOXES = 'ullistcheckboxes';

    public $helpers = array('date', 'html');

    /*
     * prints a '-' when the DB data is empty.
    */
    public function printData(&$value)
    {
        if (strlen($value) == 0) {
            return '-';
        } else {
            return $value;
        }
    }

    /**
     * Prints Ja or Nee based on the value of $value.
     * @param bool $value True for Ja
     * @return string
     */
    public function printJaNee($value)
    {
        return $value == '1' ? 'Ja' : 'Nee';
    }

    /*
     * Prints out the first part of the name like this: voornaam (roepnaam)
     */
    public function name1st(&$klant)
    {
        if (!isset($klant['voornaam']) && !isset($klant['roepnaam'])) {
            return null;
        }

        if (!empty($klant['roepnaam']) &&
            $klant['roepnaam'] != $klant['voornaam']
        ) {
            $roepnaam = ' ('.$klant['roepnaam'].')';
        } else {
            $roepnaam = '';
        }

        return $klant['voornaam'].$roepnaam;
    }

    /**
     * Returns a table line with two cells. First one is the lable second is
     * the value.
     * The value is displayed based on the type:
     *   null
     *   FormatHelper::JANEE   Value is bool: true -> Ja, false -> Nee
     *   FormatHelper::DATE    Value is a date, it is formatted with the
     *                         DateHelper before outputting
     *   FormatHelper::UL_LIST Value is an array, items are put into li tags
     *
     * @param string $label Untranslated label (goes to the first column)
     * @param mixed  $value Value to be displaye in the second column
     * @param string $type  Type of the value (see class constants)
     *
     * @return string
     */
    public function printTableLine($label, $value, $type = null)
    {
        //what type?
        switch ($type) {
            case self::DATE:
                //format through the date->show()
                $value = $this->date->show($value);
                break;
            case self::JANEE:
                //ja or nee
                $value = $this->printJaNee($value);
                break;
            case self::UL_LIST:
                //if it is an array create the list
                if (is_array($value) && count($value)) {
                    $value = $this->html->nestedList($value);
                } else {
                    $value = '-';
                }
                break;
            default:
                $value = $this->printData($value);
        }

        //return the line html
        return sprintf('<tr><td>%1$s</td><td>%2$s</td></tr>',
            __($label, true),
            $value);
    }

    /**
     * Returns a table line with two cells. First one is the lable second is
     * the place for the value. This is intended for empty form printing
     * The value is displayed based on the type:
     *   null
     *   FormatHelper::JANEE   Value is bool: true -> Ja, false -> Nee
     *   FormatHelper::DATE    Value is a date, it is formatted with the
     *                         DateHelper before outputting
     *   FormatHelper::UL_LIST Value is an array, items are put into li tags
     *
     * @param string $label Untranslated label (goes to the first column)
     * @param mixed  $value Value to be displaye in the second column
     * @param string $type  Type of the value (see class constants)
     *
     * @return string
     */
    public function printEmptyTableLine($label, $value = null, $type = null)
    {
        //what type?
        switch ($type) {
            case self::JANEE:
                //ja or nee
                $value = '[ ] Ja [ ] Nee';
                break;
            case self::UL_LIST:
                //if it is an array create the list
                if (is_array($value) && count($value)) {
                    $value = $this->html->nestedList($value);
                }
                break;
            case self::UL_LIST_RADIO:
                //if it is an array create the list
                if (is_array($value) && count($value)) {
                    foreach ($value as $k => $v) {
                        $value[$k] = '( ) '.$v;
                    }
                    $value = $this->html->nestedList($value);
                }
                break;
            case self::UL_LIST_CHECKBOXES:
                //if it is an array create the list
                if (is_array($value) && count($value)) {
                    foreach ($value as $k => $v) {
                        $value[$k] = '[ ] '.$v;
                    }
                    $value = $this->html->nestedList($value);
                }
                break;
            default:
                $value = '';
        }

        //return the line html
        return sprintf('<tr><td>%1$s</td><td>%2$s</td></tr>',
            __($label, true),
            $value);
    }

    /**
     * Collects a certain field from an array.
     *
     * @param array  $array Array of array to collect from
     * @param string $field Field name to collect
     *
     * @return array
     */
    public function collect($array, $field = 'naam')
    {
        if (! is_array($array)) {
            return array();
        }

        $result = array();
        foreach ($array as $item) {
            $result[] = $item[$field];
        }
        return $result;
    }
} //class

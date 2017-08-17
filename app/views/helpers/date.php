<?php


/*
 * /app/views/helpers/date_picker.php
 *
 * version 1.5
 *
 *
 * */

class DateHelper extends AppHelper
{
    public $helpers = ['Form', 'Js', 'Html'];

    public $datePickerClasses =
            'highlight-days-67 split-date no-transparency';

    public $field_names = //to fill the name= attribute according to the marker
            ['m' => '[month]', 'M' => '[month]', 'd' => '[day]', 'y' => '[year]'];

    public $picker_count = 0;

    ////////////////
    //creates the date input fields

    //@name - mandatory! "name" of the fields (Model.field), you have to specify
    //        the model name always! Otherwise the field won't be populated

    //@date - date string retrieved from DB (YYYY-MM-DD), when $date == 'empty'
    //        the default date is set to empty field, when date == null, default
    //        date is populated with data or set back to 'empty'

    //@options:

    //'format' - 'd-y-m', 'y-M-d' etc. M = words for months, m = numbers for months
    //				BEWARE! the calendar always appears after the year box!
    //				thus it's recommended to place the year box at the end always
    //'label' - field label
    //'disabled'
    //'class'
    //'minYear' & 'maxYear'
    public function input($name = null, $date = null, $options = null)
    {
        //checking name
        if ($name == null) {
            throw new Exception('missing name! check the /app/views/helpers/date_picker.php');
            return;
        }

        $view = &ClassRegistry::getObject('view');
        $items = explode('.', $name);
        $errormsg = '';

        /*
         * TODO: this below will have to change when there are more request periods!
        */
        $modelName = $items[0];
        $fieldName = $items[count($items) - 1];

        if (isset($view->validationErrors[$modelName])
            && isset($view->validationErrors[$modelName][$fieldName])) {
            $errormsg = __($view->validationErrors[$modelName][$fieldName], true);
        }

        $elementId = $this->getInputId($name);
        //changing name to data[Model][0][something]
        $name = $this->_calculateName($name);
          //checking & preparing date
        if (is_array($date)) {
            $date = $this->_dateFromArray($date);
        }

        if ($date == null) {
            $date = $this->_getDateFromData($name);
        }
        $regexp = '/^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/';
        if ($date != 'empty' && !preg_match($regexp, $date)) {
            throw new Exception('wrong date! check the /app/views/helpers/date.php | '.$date.' |');
            return;
        }

        //dealing with the rest of the attributes

        $maxYear = date('Y') + 5;
        $minYear = date('Y') - 5;

        $rangeHigh = '';

        $rangeLow = '';

        $label = $disabled = $class = $format = null;
        if (is_array($options)) {
            foreach ($options as $option => $value) {
                switch ($option) {
                    case 'format':
                        $format = $value;
                        break;
                    case 'id':
                        $elementId = $value;
                        break;
                    case 'label':
                        $label = $value;
                        break;
                    case 'disabled':
                        $disabled = $value;
                        break;
                    case 'class':
                        $class = $value;
                        break;
                    case 'required':
                        $required = $value;
                        break;
                    case 'rangeHigh':
                        if (!preg_match($regexp, $value)) {
                            throw new Exception('wrong date! check the /app/views/helpers/date_picker.php');
                            return;
                        }
                        $rangeHigh = ' range-high-'.$value;
                        $maxYear = explode('-', $value);
                        $maxYear = $maxYear[0];
                        break;
                    case 'rangeLow':
                        if (!preg_match($regexp, $value)) {
                            throw new Exception('wrong date! check the /app/views/helpers/date_picker.php');
                            return;
                        }
                        $rangeLow = ' range-low-'.$value;
                        $minYear = explode('-', $value);
                        $minYear = $minYear[0];
                        break;
                } //switch
            } //foreach
        } //if
        $output_string = '';

        //checking & preparing date format
        $format = $this->_prepareFormat($format);

        $id = $format[0];

        $id = strtolower($id);
        $id .= $id;

        //whether the field is required (just a CSS simple class set):
        if (isset($required) && $required == true) {
            $required = ' required';
        } else {
            $required = '';
        }

        //writing label
        if ($label != null) {
            $labelFor = str_replace(['[', ']'], ['', ''], $name);
            $labelFor = substr($labelFor, 4);
            $output_string = '<div class="input date'.$required.'"><label for="'.$labelFor.'-'.$id.'">'.$label.'</label>';
        }

        //creating fields
        $output_string .= '<span style="white-space: nowrap">';
        foreach ($format as $field) {
            $output_string .= '<select id="'.$elementId;

            if ($field != 'y') { //for year the id must be without suffix (this means that the box is always next to year!)
                $output_string .= '-'.strtolower($field).strtolower($field);
            }

            $output_string .= '" name="'.$name.$this->field_names[$field].'" ';

            if ($disabled) {
                $output_string .= 'disabled="disabled"';
            }

            if ($class != null || $field == 'y') { //adding classes
                $output_string .= 'class="';
                if ($class != null) { //if some class is set by the user
                    $output_string .= $class;
                }
                if ($field == 'y' && !$disabled) { //if year (disabled <=> no picker)
                    $output_string .= ' '.$this->datePickerClasses;
                    $output_string .= $rangeLow.$rangeHigh;
                }
                $output_string .= '"';
            }

            $output_string .= '>';

            $this->_createInputs($field, $date, $disabled, $minYear, $maxYear, $output_string);
            $output_string .= '</select>'."\n";
        }
        $output_string .= '</span>';
        if ($label != null) {
            $output_string .= '</div>';
        }
        if ($errormsg != '') {
            $output_string .= '<div class="error-message">'.$errormsg.'</div>';
        }

        return $this->output($output_string);
    }

    /*
     * Shows formatted date in accordance to Calendar.dateDisplayFormat
     *
     * @options:	(array, default null)
     * nonbreaking - tells if the date is to be separated with &nbsp;
     * 					if not it's separated with just a space
     * short - short months (only when Calendar.dateDisplayFormat is set to full months
    */
    public function show($date, $options = null)
    {
        if (empty($date) || $date == '0000-00-00') {
            return '';
        }
        //defaults:
        $sep = ' ';
        $short = false;
        $format = Configure::read('Calendar.dateDisplayFormat');
        //collecting options:
        if (is_array($options)) {
            foreach ($options as $option => $val) {
                switch ($option) {
                    case 'separator':
                        $sep = $val;
                        break;
                    case 'short':
                        if ($val === true || $val === false) {
                            $short = $val;
                        }
                        break;
                    case 'format':
                        $format = $val;
                }//switch
            }//foreach
        }//is_array check
        elseif ($options === false) { //backwards compatibility
            $sep = ' ';
        }

        if (!$short) {
            $format = str_replace('M', 'F', $format);
        }
        $format = str_replace('y', 'Y', $format);
        $format = str_replace('d', 'j', $format);
        $format = explode('-', $format);

        foreach ($format as $key => $field) {
            if ($field == 'M' || $field == 'F') {
                $format[$key] = __(date($field, strtotime($date)), true);
            } else {
                $format[$key] = date($field, strtotime($date));
            }
        }
        $output = $format[0].$sep.$format[1].$sep.$format[2];

        return $output;
    }

//show

    public function show_time($date, $user_options = null)
    {
        if (empty($date) || $date == '0000-00-00') {
            return '';
        }
        $defaults = [
                'sep' => '&nbsp;',
                'short' => false,
                'format' => 'Calendar.timeDisplayFormat',
                ];
        $options = Set::merge($defaults, $user_options);
        $format = Configure::read($options['format']);

        return date($format, strtotime($date));
    }

    public function _getDateFromData($name)
    {
        $name = str_replace(']', '\']', $name);
        $name = str_replace('[', '[\'', $name);
        $evalString = 'if (!empty($this->'.$name.')) return $this->'.$name.';';
        $result = eval($evalString);
        if ($result == null) {
            //in case the data is empty
            return 'empty';
        }
        if (is_array($result) && (empty($result['year'])
                                    || empty($result['day'])
                                    || empty($result['month'])
                                 )
            ) {
            return 'empty';
        }

        if (is_array($result)) { // if the data is in an array form
            $result = $this->_dateFromArray($result);
        }

        return $result;
    }

    public function _dateFromArray($date)
    {
        return $date['year'].'-'.$date['month'].'-'.$date['day'];
    }

    public function _calculateName($str)
    { //Model.0.field_name -> data[Model][0][field_name]
        $result = str_replace('.', '][', $str);
        $result = 'data['.$result.']';

        return $result;
    }

    public function _calculateId($str)
    { //Model.0.field_name -> Model0FieldName
        $pieces = split('[\._]', $str);
        $result = '';
        foreach ($pieces as $piece) {
            $piece = ucfirst($piece);
            $result .= $piece;
        }

        return $result;
    }

    public function _prepareFormat($format_str)
    {
        if ($format_str == null) {
            $format_str = Configure::read('Calendar.dateDisplayFormat');
        }

        return explode('-', $format_str);
    }

    public function _createInputs($type, $default, $disabled, $minYear, $maxYear, &$str)
    { //$default like YYYY-MM-DD
        if ($default == 'empty'); elseif ($default != null) {
            $default = explode('-', $default);
        } else {
            $default = [
                date('Y'),
                '01',
                '01',
            ];
        }

        $months = [
            __('January', true),
            __('February', true),
            __('March', true),
            __('April', true),
            __('May', true),
            __('June', true),
            __('July', true),
            __('August', true),
            __('September', true),
            __('October', true),
            __('November', true),
            __('December', true),
        ];
        if ($disabled) { //when the form is disabled:
            switch ($type) {
                case 'm':
                    $str .= '<option value="'.
                    $default[1].'" selected="selected">'.
                    (int) $default[1].'</option>'."\n";
                    break;
                case 'M':
                    $str .= '<option value="'.
                    $default[1].'" selected="selected">'.
                    $months[$default[1] - 1].'</option>'."\n";
                    break;
                case 'd':
                    $str .= '<option value="'.
                    $default[2].'" selected="selected">'.
                    (int) $default[2].'</option>'."\n";
                    break;
                case 'y':
                    $str .= '<option value="'.
                    $default[0].'" selected="selected">'.
                    $default[0].'</option>'."\n";
            } //switch
        } else { //when the field is active
            switch ($type) {
                case 'm':
                    $str .= '<option value="0" class="disabled"';
                    if ($default == 'empty') {
                        $str .= ' selected="selected"';
                    }
                    $str .= '>'.__('month', true).'</option>';
                    for ($i = 1; $i <= 12; ++$i) {
                        $str .= '<option value="';
                        if ($i < 10) {
                            $str .= '0';
                        }
                        $str .= $i.'"';
                        if ($default != null && $default != 'empty' && $default[1] == $i) {
                            $str .= ' selected="selected"';
                        }
                        $str .= '>'.$i.'</option>'."\n";
                    }
                    break;
                case 'M':
                    $str .= '<option value="0" class="disabled"';
                    if ($default == 'empty') {
                        $str .= ' selected="selected"';
                    }
                    $str .= '>'.__('month', true).'</option>';
                    for ($i = 1; $i <= 12; ++$i) {
                        $str .= '<option value="';
                        if ($i < 10) {
                            $str .= '0';
                        }
                        $str .= $i.'"';
                        if ($default != null && $default != 'empty' && $default[1] == $i) {
                            $str .= ' selected="selected"';
                        }
                        $str .= '>'.$months[$i - 1].'</option>'."\n";
                    }
                    break;
                case 'd':
                    $str .= '<option value="0" class="disabled"';
                    if ($default == 'empty') {
                        $str .= ' selected="selected"';
                    }
                    $str .= '>'.__('day', true).'</option>';
                    for ($i = 1; $i <= 31; ++$i) {
                        $str .= '<option value="';
                        if ($i < 10) {
                            $str .= '0';
                        }
                        $str .= $i.'"';
                        if ($default != null && $default != 'empty' && $default[2] == $i) {
                            $str .= ' selected="selected"';
                        }
                        $str .= '>'.$i.'</option>'."\n";
                    }
                    break;
                case 'y':
                    $str .= '<option value="0" class="disabled"';
                    if ($default == 'empty') {
                        $str .= ' selected="selected"';
                    }
                    $str .= '>'.__('year', true).'</option>';
                    for ($i = $maxYear; $i >= $minYear; --$i) {
                        $str .= '<option';
                        if ($default != null && $default != 'empty' && $default[0] == $i) {
                            $str .= ' selected="selected"';
                        }
                        $str .= ' value="'.$i.'">'.$i.'</option>'."\n";
                    } //for
            } //switch
        }//else
    }

 //create inputs

/*
 * generate cake-style ids
*/
    public function getInputId($model)
    {
        $fieldId = '';
        foreach (explode('.', $model) as $piece) {
            $fieldId .= Inflector::camelize($piece);
        }

        return $fieldId;
    }

    /*
     * This is the new jQuery UI datepicker, that is supposed to replace the
     * "Unobtursie".
     *
     * It prints out two inputs. Invisible text input stores the DB-friendly
     * format of the date, the name of the field is compliant with cakePHP
     * form naming convention. The visible text input has a suffix "-datepicker"
     * and is the one that we attach the jQuery datepicker to.
     * When user selects a date on the datepicker the human readable date is
     * put in the visible field and the DB-friendly is put in the "hidden"
     * cakePHP-compliant one.
     *
     * Default jQuery UI widget settings are in the script.js file,
     * in the $(document).ready block. The JavaScript function that attaches the
     * datepicker and the event listeners to the input is called add_datepicker
     * and can also be found in the script.js.
     *
     * PROBLEM:
     *      to force user to use the datepicker to select the date we disable
     *      the input on focus. This has obvious drawback of blocking the
     *      fallback when the datepicker code fails. Also when user uses TAB key
     *      to navigate across the form the onfocus events don't work the way
     *      they're expected to and the input gets disabled permanently.
     *      To overcome that problem we put a calendar icon next to the input,
     *      so whenever the input breaks user is not stuck and can still select
     *      the date.
     *      Solution to that would be to use some alternative to the field
     *      disabling.
     *
     */

    public function datepicker($fieldname, $options = [])
    {
        $defaults = [
            'required' => false,
        ];

        $options = $options + $defaults;

        $datepicker_fieldname = $fieldname.'-datepicker';

        if ($options['required']) {
            $required_class = ' required';
        } else {
            $required_class = '';
        }

    //wrapping div
        $o = '<div class="input text'.$required_class.'">';

    //input options common for both fields
        $inv_field_opts['type'] = $dp_field_opts['type'] = 'text';
        $inv_field_opts['div'] = $dp_field_opts['div'] = false;

    //visible field - ignored by cake
        if (isset($options['label']) && !empty($options['label'])) {
            $dp_field_opts['label'] = $options['label'];
        } else {
            $dp_field_opts['label'] = Inflector::humanize($fieldname);
        }
        $o .= $this->Form->input($datepicker_fieldname, $dp_field_opts);

    //invisible field (not type="hidden")
        $inv_field_opts['label'] = false;
        $inv_field_opts['style'] = 'display: none';
        $o .= $this->Form->input($fieldname, $inv_field_opts);

    //retrieving the id of the last input generated by the Form helper
        $field_id = $this->Form->domId();

    //showing the eraser to clear the field when needed
        if (!$options['required']) {
            $o .= $this->Html->image('/img/eraser.png', [
                'id' => $field_id.'-clear',
                'class' => 'clear-date',
            ]);
        }

    //end of the wrapping div
        $o .= '</div>';

    //JavaScript

        //defaults
        if ($this->picker_count == 0) {
            $this->Js->buffer('
                $.datepicker.setDefaults( $.datepicker.regional[ "nl" ] );
                $.datepicker.setDefaults( {
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    altFormat: "yy-mm-dd",
                    buttonImage: "'.Router::url('/img/calendar_1.png').'",
                    buttonImageOnly: true,
                    buttonText: "kalender",
                    showOn: "both"
                } );
            ');
        }

        //attaching the datepicker and the listeners
        $this->Js->buffer('
            var field_id = "#'.$field_id.'";

            var datepicker_field = $(field_id+"-datepicker");
            datepicker_field.datepicker({
                altField: field_id,
                onClose: function(dateText, inst){
                    inst.input.removeAttr("disabled");
                }
            }).focus(function(e){
                $(this).attr("disabled", "disabled");
            });

        //for preset dates (ex. when editing)
            var preset_val = $(field_id).val();
            if(preset_val){
                datepicker_field.datepicker("setDate", new Date(preset_val));
            }

            $(field_id+"-clear").click(function(e){
                $(field_id).val("");
                $(field_id+"-datepicker").val("");
            });

        ');

        ++$this->picker_count;

        return $this->output($o);
    }

    /**
     * Humanizes the number of days: translates it to days, weeks, months or
     * years depending on the number of days.
     *
     * @param int $days Number of days
     *
     * @return string
     */
    public function humanDays($days)
    {
        if ($days == 0) {
            return 'vandaag';
        } elseif ($days < 7) {
            return __tr(':days dagen', ['days' => floor($days)]);
        } elseif ($days < 30) {
            return __tr(':weeks weken', ['weeks' => floor($days / 7)]);
        } elseif ($days < 365) {
            return __tr(':months maanden', ['months' => floor($days / 30)]);
        } else {
            return __tr(':years jaren', ['years' => floor($days / 365)]);
        }
    }
} //class

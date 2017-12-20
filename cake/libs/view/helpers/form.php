<?php
/**
 * Automatic generation of HTML FORMs from given data.
 *
 * Used for scaffolding.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * @see          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.10.0.1076
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Form helper library.
 *
 * Automatic generation of HTML FORMs from given data.
 *
 * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#Form
 */
class FormHelper extends AppHelper
{
    /**
     * Other helpers used by FormHelper.
     *
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Holds the fields array('field_name' => array('type'=> 'string', 'length'=> 100),
     * primaryKey and validates array('field_name').
     */
    public $fieldset = [];

    /**
     * Options used by DateTime fields.
     *
     * @var array
     */
    public $__options = [
        'day' => [], 'minute' => [], 'hour' => [],
        'month' => [], 'year' => [], 'meridian' => [],
    ];

    /**
     * List of fields created, used with secure forms.
     *
     * @var array
     */
    public $fields = [];

    /**
     * Defines the type of form being created.  Set by FormHelper::create().
     *
     * @var string
     */
    public $requestType = null;

    /**
     * The default model being used for the current form.
     *
     * @var string
     */
    public $defaultModel = null;

    /**
     * Persistent default options used by input(). Set by FormHelper::create().
     *
     * @var array
     */
    public $_inputDefaults = [];

    /**
     * The action attribute value of the last created form.
     * Used to make form/request specific hashes for SecurityComponent.
     *
     * @var string
     */
    public $_lastAction = '';

    /**
     * Introspects model information and extracts information related
     * to validation, field length and field type. Appends information into
     * $this->fieldset.
     *
     * @return Model Returns a model instance
     */
    public function &_introspectModel($model)
    {
        $object = null;
        if (is_string($model) && false !== strpos($model, '.')) {
            $path = explode('.', $model);
            $model = end($path);
        }

        if (ClassRegistry::isKeySet($model)) {
            $object = &ClassRegistry::getObject($model);
        }

        if (!empty($object)) {
            $fields = $object->schema();
            foreach ($fields as $key => $value) {
                unset($fields[$key]);
                $fields[$key] = $value;
            }

            if (!empty($object->hasAndBelongsToMany)) {
                foreach ($object->hasAndBelongsToMany as $alias => $assocData) {
                    $fields[$alias] = ['type' => 'multiple'];
                }
            }
            $validates = [];
            if (!empty($object->validate)) {
                foreach ($object->validate as $validateField => $validateProperties) {
                    if ($this->_isRequiredField($validateProperties)) {
                        $validates[] = $validateField;
                    }
                }
            }
            $defaults = ['fields' => [], 'key' => 'id', 'validates' => []];
            $key = $object->primaryKey;
            $this->fieldset[$model] = array_merge($defaults, compact('fields', 'key', 'validates'));
        }

        return $object;
    }

    /**
     * Returns if a field is required to be filled based on validation properties from the validating object.
     *
     * @return bool true if field is required to be filled, false otherwise
     */
    public function _isRequiredField($validateProperties)
    {
        $required = false;
        if (is_array($validateProperties)) {
            $dims = Set::countDim($validateProperties);
            if (1 == $dims || (2 == $dims && isset($validateProperties['rule']))) {
                $validateProperties = [$validateProperties];
            }

            foreach ($validateProperties as $rule => $validateProp) {
                if (isset($validateProp['allowEmpty']) && true === $validateProp['allowEmpty']) {
                    return false;
                }
                $rule = isset($validateProp['rule']) ? $validateProp['rule'] : false;
                $required = $rule || empty($validateProp);
                if ($required) {
                    break;
                }
            }
        }

        return $required;
    }

    /**
     * Returns an HTML FORM element.
     *
     * ### Options:
     *
     * - `type` Form method defaults to POST
     * - `action`  The controller action the form submits to, (optional).
     * - `url`  The url the form submits to. Can be a string or a url array.  If you use 'url'
     *    you should leave 'action' undefined.
     * - `default`  Allows for the creation of Ajax forms. Set this to false to prevent the default event handler.
     *   Will create an onsubmit attribute if it doesn't not exist. If it does, default action suppression
     *   will be appended.
     * - `onsubmit` Used in conjunction with 'default' to create ajax forms.
     * - `inputDefaults` set the default $options for FormHelper::input(). Any options that would
     *    be set when using FormHelper::input() can be set here.  Options set with `inputDefaults`
     *    can be overridden when calling input()
     * - `encoding` Set the accept-charset encoding for the form.  Defaults to `Configure::read('App.encoding')`
     *
     * @param string $model   The model object which the form is being defined for
     * @param array  $options an array of html attributes and options
     *
     * @return string an formatted opening FORM tag
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#Creating-Forms
     */
    public function create($model = null, $options = [])
    {
        $created = $id = false;
        $append = '';
        $view = &ClassRegistry::getObject('view');

        if (is_array($model) && empty($options)) {
            $options = $model;
            $model = null;
        }
        if (empty($model) && false !== $model && !empty($this->params['models'])) {
            $model = $this->params['models'][0];
            $this->defaultModel = $this->params['models'][0];
        } elseif (empty($model) && empty($this->params['models'])) {
            $model = false;
        }

        $models = ClassRegistry::keys();
        foreach ($models as $currentModel) {
            if (ClassRegistry::isKeySet($currentModel)) {
                $currentObject = &ClassRegistry::getObject($currentModel);
                if (is_a($currentObject, 'Model') && !empty($currentObject->validationErrors)) {
                    $this->validationErrors[Inflector::camelize($currentModel)] = &$currentObject->validationErrors;
                }
            }
        }

        $object = $this->_introspectModel($model);
        $this->setEntity($model.'.', true);

        $modelEntity = $this->model();
        if (isset($this->fieldset[$modelEntity]['key'])) {
            $data = $this->fieldset[$modelEntity];
            $recordExists = (
                isset($this->data[$model]) &&
                !empty($this->data[$model][$data['key']]) &&
                !is_array($this->data[$model][$data['key']])
            );

            if ($recordExists) {
                $created = true;
                $id = $this->data[$model][$data['key']];
            }
        }

        $options = array_merge([
            'type' => ($created && empty($options['action'])) ? 'put' : 'post',
            'action' => null,
            'url' => null,
            'default' => true,
            'encoding' => strtolower(Configure::read('App.encoding')),
            'inputDefaults' => [], ],
        $options);
        $this->_inputDefaults = $options['inputDefaults'];
        unset($options['inputDefaults']);

        if (empty($options['url']) || is_array($options['url'])) {
            if (empty($options['url']['controller'])) {
                if (!empty($model) && $model != $this->defaultModel) {
                    $options['url']['controller'] = Inflector::underscore(Inflector::pluralize($model));
                } elseif (!empty($this->params['controller'])) {
                    $options['url']['controller'] = Inflector::underscore($this->params['controller']);
                }
            }
            if (empty($options['action'])) {
                $options['action'] = $this->params['action'];
            }

            $actionDefaults = [
                'plugin' => $this->plugin,
                'controller' => $view->viewPath,
                'action' => $options['action'],
            ];
            if (!empty($options['action']) && !isset($options['id'])) {
                $options['id'] = $this->domId($options['action'].'Form');
            }
            $options['action'] = array_merge($actionDefaults, (array) $options['url']);
            if (empty($options['action'][0])) {
                $options['action'][0] = $id;
            }
        } elseif (is_string($options['url'])) {
            $options['action'] = $options['url'];
        }
        unset($options['url']);

        switch (strtolower($options['type'])) {
            case 'get':
                $htmlAttributes['method'] = 'get';
            break;
            case 'file':
                $htmlAttributes['enctype'] = 'multipart/form-data';
                $options['type'] = ($created) ? 'put' : 'post';
                // no break
            case 'post':
            case 'put':
            case 'delete':
                $append .= $this->hidden('_method', [
                    'name' => '_method', 'value' => strtoupper($options['type']), 'id' => null,
                ]);
                // no break
            default:
                $htmlAttributes['method'] = 'post';
            break;
        }
        $this->requestType = strtolower($options['type']);

        $action = $this->url($options['action']);
        unset($options['type'], $options['action']);

        if (false == $options['default']) {
            if (!isset($options['onsubmit'])) {
                $options['onsubmit'] = '';
            }
            $htmlAttributes['onsubmit'] = $options['onsubmit'].'event.returnValue = false; return false;';
        }
        unset($options['default']);

        if (!empty($options['encoding'])) {
            $htmlAttributes['accept-charset'] = $options['encoding'];
            unset($options['encoding']);
        }

        $htmlAttributes = array_merge($options, $htmlAttributes);

        $this->fields = [];
        if (isset($this->params['_Token']) && !empty($this->params['_Token'])) {
            $append .= $this->hidden('_Token.key', [
                'value' => $this->params['_Token']['key'], 'id' => 'Token'.mt_rand(), ]
            );
        }

        if (!empty($append)) {
            $append = sprintf($this->Html->tags['block'], ' style="display:none;"', $append);
        }

        $this->_lastAction($action);
        $this->setEntity($model.'.', true);
        $attributes = sprintf('action="%s" ', $action).$this->_parseAttributes($htmlAttributes, null, '');

        return sprintf($this->Html->tags['form'], $attributes).$append;
    }

    /**
     * Sets the last created form action.
     *
     * @param string|array $url URL
     */
    public function _lastAction($url)
    {
        $action = FULL_BASE_URL.$url;
        $parts = parse_url($action);
        $this->_lastAction = $parts['path'];
    }

    /**
     * Closes an HTML form, cleans up values set by FormHelper::create(), and writes hidden
     * input fields where appropriate.
     *
     * If $options is set a form submit button will be created. Options can be either a string or an array.
     *
     * {{{
     * array usage:
     *
     * array('label' => 'save'); value="save"
     * array('label' => 'save', 'name' => 'Whatever'); value="save" name="Whatever"
     * array('name' => 'Whatever'); value="Submit" name="Whatever"
     * array('label' => 'save', 'name' => 'Whatever', 'div' => 'good') <div class="good"> value="save" name="Whatever"
     * array('label' => 'save', 'name' => 'Whatever', 'div' => array('class' => 'good')); <div class="good"> value="save" name="Whatever"
     * }}}
     *
     * @param mixed $options as a string will use $options as the value of button,
     *
     * @return string a closing FORM tag optional submit button
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#Closing-the-Form
     */
    public function end($options = null)
    {
        if (!empty($this->params['models'])) {
            $models = $this->params['models'][0];
        }
        $out = null;
        $submit = null;

        if (null !== $options) {
            $submitOptions = [];
            if (is_string($options)) {
                $submit = $options;
            } else {
                if (isset($options['label'])) {
                    $submit = $options['label'];
                    unset($options['label']);
                }
                $submitOptions = $options;
            }
            $out .= $this->submit($submit, $submitOptions);
        }
        if (isset($this->params['_Token']) && !empty($this->params['_Token'])) {
            $out .= $this->secure($this->fields);
            $this->fields = [];
        }
        $this->setEntity(null);
        $out .= $this->Html->tags['formend'];

        $view = &ClassRegistry::getObject('view');
        $view->modelScope = false;

        return $out;
    }

    /**
     * Generates a hidden field with a security hash based on the fields used in the form.
     *
     * @param array $fields The list of fields to use when generating the hash
     *
     * @return string A hidden input field with a security hash
     */
    public function secure($fields = [])
    {
        if (!isset($this->params['_Token']) || empty($this->params['_Token'])) {
            return;
        }
        $locked = [];

        foreach ($fields as $key => $value) {
            if (!is_int($key)) {
                $locked[$key] = $value;
                unset($fields[$key]);
            }
        }
        sort($fields, SORT_STRING);
        ksort($locked, SORT_STRING);
        $fields += $locked;

        $fields = Security::hash(
            $this->_lastAction.
            serialize($fields).
            Configure::read('Security.salt')
        );
        $locked = implode(array_keys($locked), '|');

        $out = $this->hidden('_Token.fields', [
            'value' => urlencode($fields.':'.$locked),
            'id' => 'TokenFields'.mt_rand(),
        ]);
        $out = sprintf($this->Html->tags['block'], ' style="display:none;"', $out);

        return $out;
    }

    /**
     * Determine which fields of a form should be used for hash.
     * Populates $this->fields.
     *
     * @param mixed $field Reference to field to be secured
     * @param mixed $value field value, if value should not be tampered with
     */
    public function __secure($field = null, $value = null)
    {
        if (!$field) {
            $view = &ClassRegistry::getObject('view');
            $field = $view->entity();
        } elseif (is_string($field)) {
            $field = Set::filter(explode('.', $field), true);
        }

        if (!empty($this->params['_Token']['disabledFields'])) {
            foreach ((array) $this->params['_Token']['disabledFields'] as $disabled) {
                $disabled = explode('.', $disabled);
                if (array_values(array_intersect($field, $disabled)) === $disabled) {
                    return;
                }
            }
        }

        $last = end($field);
        if (is_numeric($last) || empty($last)) {
            array_pop($field);
        }

        $field = implode('.', $field);
        if (!in_array($field, $this->fields)) {
            if (null !== $value) {
                return $this->fields[$field] = $value;
            }
            $this->fields[] = $field;
        }
    }

    /**
     * Returns true if there is an error for the given field, otherwise false.
     *
     * @param string $field This should be "Modelname.fieldname"
     *
     * @return bool if there are errors this method returns true, else false
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#isFieldError
     */
    public function isFieldError($field)
    {
        $this->setEntity($field);

        return (bool) $this->tagIsInvalid();
    }

    /**
     * Returns a formatted error message for given FORM field, NULL if no errors.
     *
     * ### Options:
     *
     * - `escape`  bool  Whether or not to html escape the contents of the error.
     * - `wrap`  mixed  Whether or not the error message should be wrapped in a div. If a
     *   string, will be used as the HTML tag to use.
     * - `class` string  The classname for the error message
     *
     * @param string $field   A field name, like "Modelname.fieldname"
     * @param mixed  $text    Error message or array of $options. If array, `attributes` key
     *                        will get used as html attributes for error container
     * @param array  $options Rendering options for <div /> wrapper tag
     *
     * @return string if there are errors this method returns an error message, otherwise null
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#error
     */
    public function error($field, $text = null, $options = [])
    {
        $defaults = ['wrap' => true, 'class' => 'error-message', 'escape' => true];
        $options = array_merge($defaults, $options);
        $this->setEntity($field);

        $error = $this->tagIsInvalid();
        if (null !== $error) {
            if (is_array($error)) {
                list(, , $field) = explode('.', $field);
                if (isset($error[$field])) {
                    $error = $error[$field];
                } else {
                    return null;
                }
            }

            if (is_array($text) && is_numeric($error) && $error > 0) {
                --$error;
            }
            if (is_array($text)) {
                $options = array_merge($options, array_intersect_key($text, $defaults));
                if (isset($text['attributes']) && is_array($text['attributes'])) {
                    $options = array_merge($options, $text['attributes']);
                }
                $text = isset($text[$error]) ? $text[$error] : null;
                unset($options[$error]);
            }

            if (null !== $text) {
                $error = $text;
            } elseif (is_numeric($error)) {
                $error = sprintf(__('Error in field %s', true), Inflector::humanize($this->field()));
            }
            if ($options['escape']) {
                $error = h($error);
                unset($options['escape']);
            }
            if ($options['wrap']) {
                $tag = is_string($options['wrap']) ? $options['wrap'] : 'div';
                unset($options['wrap']);

                return $this->Html->tag($tag, $error, $options);
            } else {
                return $error;
            }
        } else {
            return null;
        }
    }

    /**
     * Returns a formatted LABEL element for HTML FORMs. Will automatically generate
     * a for attribute if one is not provided.
     *
     * @param string $fieldName This should be "Modelname.fieldname"
     * @param string $text      text that will appear in the label field
     * @param mixed  $options   an array of HTML attributes, or a string, to be used as a class name
     *
     * @return string The formatted LABEL element
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#label
     */
    public function label($fieldName = null, $text = null, $options = [])
    {
        if (empty($fieldName)) {
            $view = ClassRegistry::getObject('view');
            $fieldName = implode('.', $view->entity());
        }

        if (null === $text) {
            if (false !== strpos($fieldName, '.')) {
                $text = array_pop(explode('.', $fieldName));
            } else {
                $text = $fieldName;
            }
            if ('_id' == substr($text, -3)) {
                $text = substr($text, 0, strlen($text) - 3);
            }
            $text = __(Inflector::humanize(Inflector::underscore($text)), true);
        }

        if (is_string($options)) {
            $options = ['class' => $options];
        }

        if (isset($options['for'])) {
            $labelFor = $options['for'];
            unset($options['for']);
        } else {
            $labelFor = $this->domId($fieldName);
        }

        return sprintf(
            $this->Html->tags['label'],
            $labelFor,
            $this->_parseAttributes($options), $text
        );
    }

    /**
     * Generate a set of inputs for `$fields`.  If $fields is null the current model
     * will be used.
     *
     * In addition to controller fields output, `$fields` can be used to control legend
     * and fieldset rendering with the `fieldset` and `legend` keys.
     * `$form->inputs(array('legend' => 'My legend'));` Would generate an input set with
     * a custom legend.  You can customize individual inputs through `$fields` as well.
     *
     * {{{
     *	$form->inputs(array(
     *		'name' => array('label' => 'custom label')
     *	));
     * }}}
     *
     * In addition to fields control, inputs() allows you to use a few additional options.
     *
     * - `fieldset` Set to false to disable the fieldset. If a string is supplied it will be used as
     *    the classname for the fieldset element.
     * - `legend` Set to false to disable the legend for the generated input set. Or supply a string
     *    to customize the legend text.
     *
     * @param mixed $fields    an array of fields to generate inputs for, or null
     * @param array $blacklist a simple array of fields to not create inputs for
     *
     * @return string completed form inputs
     */
    public function inputs($fields = null, $blacklist = null)
    {
        $fieldset = $legend = true;
        $model = $this->model();
        if (is_array($fields)) {
            if (array_key_exists('legend', $fields)) {
                $legend = $fields['legend'];
                unset($fields['legend']);
            }

            if (isset($fields['fieldset'])) {
                $fieldset = $fields['fieldset'];
                unset($fields['fieldset']);
            }
        } elseif (null !== $fields) {
            $fieldset = $legend = $fields;
            if (!is_bool($fieldset)) {
                $fieldset = true;
            }
            $fields = [];
        }

        if (empty($fields)) {
            $fields = array_keys($this->fieldset[$model]['fields']);
        }

        if (true === $legend) {
            $actionName = __('New %s', true);
            $isEdit = (
                false !== strpos($this->action, 'update') ||
                false !== strpos($this->action, 'edit')
            );
            if ($isEdit) {
                $actionName = __('Edit %s', true);
            }
            $modelName = Inflector::humanize(Inflector::underscore($model));
            $legend = sprintf($actionName, __($modelName, true));
        }

        $out = null;
        foreach ($fields as $name => $options) {
            if (is_numeric($name) && !is_array($options)) {
                $name = $options;
                $options = [];
            }
            $entity = explode('.', $name);
            $blacklisted = (
                is_array($blacklist) &&
                (in_array($name, $blacklist) || in_array(end($entity), $blacklist))
            );
            if ($blacklisted) {
                continue;
            }
            $out .= $this->input($name, $options);
        }

        if (is_string($fieldset)) {
            $fieldsetClass = sprintf(' class="%s"', $fieldset);
        } else {
            $fieldsetClass = '';
        }

        if ($fieldset && $legend) {
            return sprintf(
                $this->Html->tags['fieldset'],
                $fieldsetClass,
                sprintf($this->Html->tags['legend'], $legend).$out
            );
        } elseif ($fieldset) {
            return sprintf($this->Html->tags['fieldset'], $fieldsetClass, $out);
        } else {
            return $out;
        }
    }

    /**
     * Generates a form input element complete with label and wrapper div.
     *
     * ### Options
     *
     * See each field type method for more information. Any options that are part of
     * $attributes or $options for the different **type** methods can be included in `$options` for input().i
     * Additionally, any unknown keys that are not in the list below, or part of the selected type's options
     * will be treated as a regular html attribute for the generated input.
     *
     * - `type` - Force the type of widget you want. e.g. `type => 'select'`
     * - `label` - Either a string label, or an array of options for the label. See FormHelper::label()
     * - `div` - Either `false` to disable the div, or an array of options for the div.
     *    See HtmlHelper::div() for more options.
     * - `options` - for widgets that take options e.g. radio, select
     * - `error` - control the error message that is produced
     * - `empty` - String or boolean to enable empty select box options.
     * - `before` - Content to place before the label + input.
     * - `after` - Content to place after the label + input.
     * - `between` - Content to place between the label + input.
     * - `format` - format template for element order. Any element that is not in the array, will not be in the output.
     *    - Default input format order: array('before', 'label', 'between', 'input', 'after', 'error')
     *    - Default checkbox format order: array('before', 'input', 'between', 'label', 'after', 'error')
     *    - Hidden input will not be formatted
     *    - Radio buttons cannot have the order of input and label elements controlled with these settings.
     *
     * @param string $fieldName This should be "Modelname.fieldname"
     * @param array  $options   each type of input takes different options
     *
     * @return string completed form widget
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#Automagic-Form-Elements
     */
    public function input($fieldName, $options = [])
    {
        $this->setEntity($fieldName);

        $options = array_merge(
            ['before' => null, 'between' => null, 'after' => null, 'format' => null],
            $this->_inputDefaults,
            $options
        );

        $modelKey = $this->model();
        $fieldKey = $this->field();
        if (!isset($this->fieldset[$modelKey])) {
            $this->_introspectModel($modelKey);
        }

        if (!isset($options['type'])) {
            $magicType = true;
            $options['type'] = 'text';
            if (isset($options['options'])) {
                $options['type'] = 'select';
            } elseif (in_array($fieldKey, ['psword', 'passwd', 'password'])) {
                $options['type'] = 'password';
            } elseif (isset($this->fieldset[$modelKey]['fields'][$fieldKey])) {
                $fieldDef = $this->fieldset[$modelKey]['fields'][$fieldKey];
                $type = $fieldDef['type'];
                $primaryKey = $this->fieldset[$modelKey]['key'];
            }

            if (isset($type)) {
                $map = [
                    'string' => 'text',     'datetime' => 'datetime',
                    'boolean' => 'checkbox', 'timestamp' => 'datetime',
                    'text' => 'textarea', 'time' => 'time',
                    'date' => 'date',     'float' => 'text',
                ];

                if (isset($this->map[$type])) {
                    $options['type'] = $this->map[$type];
                } elseif (isset($map[$type])) {
                    $options['type'] = $map[$type];
                }
                if ($fieldKey == $primaryKey) {
                    $options['type'] = 'hidden';
                }
            }
            if (preg_match('/_id$/', $fieldKey) && 'hidden' !== $options['type']) {
                $options['type'] = 'select';
            }

            if ($modelKey === $fieldKey) {
                $options['type'] = 'select';
                if (!isset($options['multiple'])) {
                    $options['multiple'] = 'multiple';
                }
            }
        }
        $types = ['checkbox', 'radio', 'select'];

        if (
            (!isset($options['options']) && in_array($options['type'], $types)) ||
            (isset($magicType) && 'text' == $options['type'])
        ) {
            $view = &ClassRegistry::getObject('view');
            $varName = Inflector::variable(
                Inflector::pluralize(preg_replace('/_id$/', '', $fieldKey))
            );
            $varOptions = $view->getVar($varName);
            if (is_array($varOptions)) {
                if ('radio' !== $options['type']) {
                    $options['type'] = 'select';
                }
                $options['options'] = $varOptions;
            }
        }

        $autoLength = (!array_key_exists('maxlength', $options) && isset($fieldDef['length']));
        if ($autoLength && 'text' == $options['type']) {
            $options['maxlength'] = $fieldDef['length'];
        }
        if ($autoLength && 'float' == $fieldDef['type']) {
            $options['maxlength'] = array_sum(explode(',', $fieldDef['length'])) + 1;
        }

        $divOptions = [];
        $div = $this->_extractOption('div', $options, true);
        unset($options['div']);

        if (!empty($div)) {
            $divOptions['class'] = 'input';
            $divOptions = $this->addClass($divOptions, $options['type']);
            if (is_string($div)) {
                $divOptions['class'] = $div;
            } elseif (is_array($div)) {
                $divOptions = array_merge($divOptions, $div);
            }
            if (
                isset($this->fieldset[$modelKey]) &&
                in_array($fieldKey, $this->fieldset[$modelKey]['validates'])
            ) {
                $divOptions = $this->addClass($divOptions, 'required');
            }
            if (!isset($divOptions['tag'])) {
                $divOptions['tag'] = 'div';
            }
        }

        $label = null;
        if (isset($options['label']) && 'radio' !== $options['type']) {
            $label = $options['label'];
            unset($options['label']);
        }

        if ('radio' === $options['type']) {
            $label = false;
            if (isset($options['options'])) {
                $radioOptions = (array) $options['options'];
                unset($options['options']);
            }
        }

        if (false !== $label) {
            $label = $this->_inputLabel($fieldName, $label, $options);
        }

        $error = $this->_extractOption('error', $options, null);
        unset($options['error']);

        $selected = $this->_extractOption('selected', $options, null);
        unset($options['selected']);

        if (isset($options['rows']) || isset($options['cols'])) {
            $options['type'] = 'textarea';
        }

        if ('datetime' === $options['type'] || 'date' === $options['type'] || 'time' === $options['type'] || 'select' === $options['type']) {
            $options += ['empty' => false];
        }
        if ('datetime' === $options['type'] || 'date' === $options['type'] || 'time' === $options['type']) {
            $dateFormat = $this->_extractOption('dateFormat', $options, 'MDY');
            $timeFormat = $this->_extractOption('timeFormat', $options, 12);
            unset($options['dateFormat'], $options['timeFormat']);
        }

        $type = $options['type'];
        $out = array_merge(
            ['before' => null, 'label' => null, 'between' => null, 'input' => null, 'after' => null, 'error' => null],
            ['before' => $options['before'], 'label' => $label, 'between' => $options['between'], 'after' => $options['after']]
        );
        $format = null;
        if (is_array($options['format']) && in_array('input', $options['format'])) {
            $format = $options['format'];
        }
        unset($options['type'], $options['before'], $options['between'], $options['after'], $options['format']);

        switch ($type) {
            case 'hidden':
                $input = $this->hidden($fieldName, $options);
                $format = ['input'];
                unset($divOptions);
            break;
            case 'checkbox':
                $input = $this->checkbox($fieldName, $options);
                $format = $format ? $format : ['before', 'input', 'between', 'label', 'after', 'error'];
            break;
            case 'radio':
                $input = $this->radio($fieldName, $radioOptions, $options);
            break;
            case 'text':
            case 'password':
            case 'file':
                $input = $this->{$type}($fieldName, $options);
            break;
            case 'select':
                $options += ['options' => []];
                $list = $options['options'];
                unset($options['options']);
                $input = $this->select($fieldName, $list, $selected, $options);
            break;
            case 'time':
                $input = $this->dateTime($fieldName, null, $timeFormat, $selected, $options);
            break;
            case 'date':
                $input = $this->dateTime($fieldName, $dateFormat, null, $selected, $options);
            break;
            case 'datetime':
                $input = $this->dateTime($fieldName, $dateFormat, $timeFormat, $selected, $options);
            break;
            case 'textarea':
            default:
                $input = $this->textarea($fieldName, $options + ['cols' => '30', 'rows' => '6']);
            break;
        }

        if ('hidden' != $type && false !== $error) {
            $errMsg = $this->error($fieldName, $error);
            if ($errMsg) {
                $divOptions = $this->addClass($divOptions, 'error');
                $out['error'] = $errMsg;
            }
        }

        $out['input'] = $input;
        $format = $format ? $format : ['before', 'label', 'between', 'input', 'after', 'error'];
        $output = '';
        foreach ($format as $element) {
            $output .= $out[$element];
            unset($out[$element]);
        }

        if (!empty($divOptions['tag'])) {
            $tag = $divOptions['tag'];
            unset($divOptions['tag']);
            $output = $this->Html->tag($tag, $output, $divOptions);
        }

        return $output;
    }

    /**
     * Extracts a single option from an options array.
     *
     * @param string $name    the name of the option to pull out
     * @param array  $options the array of options you want to extract
     * @param mixed  $default The default option value
     *
     * @return the contents of the option or default
     */
    public function _extractOption($name, $options, $default = null)
    {
        if (array_key_exists($name, $options)) {
            return $options[$name];
        }

        return $default;
    }

    /**
     * Generate a label for an input() call.
     *
     * @param array $options options for the label element
     *
     * @return string Generated label element
     */
    public function _inputLabel($fieldName, $label, $options)
    {
        $labelAttributes = $this->domId([], 'for');
        if ('date' === $options['type'] || 'datetime' === $options['type']) {
            if (isset($options['dateFormat']) && 'NONE' === $options['dateFormat']) {
                $labelAttributes['for'] .= 'Hour';
                $idKey = 'hour';
            } else {
                $labelAttributes['for'] .= 'Month';
                $idKey = 'month';
            }
            if (isset($options['id']) && isset($options['id'][$idKey])) {
                $labelAttributes['for'] = $options['id'][$idKey];
            }
        } elseif ('time' === $options['type']) {
            $labelAttributes['for'] .= 'Hour';
            if (isset($options['id']) && isset($options['id']['hour'])) {
                $labelAttributes['for'] = $options['id']['hour'];
            }
        }

        if (is_array($label)) {
            $labelText = null;
            if (isset($label['text'])) {
                $labelText = $label['text'];
                unset($label['text']);
            }
            $labelAttributes = array_merge($labelAttributes, $label);
        } else {
            $labelText = $label;
        }

        if (isset($options['id']) && is_string($options['id'])) {
            $labelAttributes = array_merge($labelAttributes, ['for' => $options['id']]);
        }

        return $this->label($fieldName, $labelText, $labelAttributes);
    }

    /**
     * Creates a checkbox input widget.
     *
     * ### Options:
     *
     * - `value` - the value of the checkbox
     * - `checked` - boolean indicate that this checkbox is checked.
     * - `hiddenField` - boolean to indicate if you want the results of checkbox() to include
     *    a hidden input with a value of ''.
     * - `disabled` - create a disabled input.
     *
     * @param string $fieldName Name of a field, like this "Modelname.fieldname"
     * @param array  $options   array of HTML attributes
     *
     * @return string an HTML text input element
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#checkbox
     */
    public function checkbox($fieldName, $options = [])
    {
        $options = $this->_initInputField($fieldName, $options) + ['hiddenField' => true];
        $value = current($this->value());
        $output = '';

        if (empty($options['value'])) {
            $options['value'] = 1;
        } elseif (
            (!isset($options['checked']) && !empty($value) && $value === $options['value']) ||
            !empty($options['checked'])
        ) {
            $options['checked'] = 'checked';
        }
        if ($options['hiddenField']) {
            $hiddenOptions = [
                'id' => $options['id'].'_', 'name' => $options['name'],
                'value' => '0', 'secure' => false,
            ];
            if (isset($options['disabled']) && true == $options['disabled']) {
                $hiddenOptions['disabled'] = 'disabled';
            }
            $output = $this->hidden($fieldName, $hiddenOptions);
        }
        unset($options['hiddenField']);

        return $output.sprintf(
            $this->Html->tags['checkbox'],
            $options['name'],
            $this->_parseAttributes($options, ['name'], null, ' ')
        );
    }

    /**
     * Creates a set of radio widgets. Will create a legend and fieldset
     * by default.  Use $options to control this.
     *
     * ### Attributes:
     *
     * - `separator` - define the string in between the radio buttons
     * - `legend` - control whether or not the widget set has a fieldset & legend
     * - `value` - indicate a value that is should be checked
     * - `label` - boolean to indicate whether or not labels for widgets show be displayed
     * - `hiddenField` - boolean to indicate if you want the results of radio() to include
     *    a hidden input with a value of ''. This is useful for creating radio sets that non-continuous
     *
     * @param string $fieldName  Name of a field, like this "Modelname.fieldname"
     * @param array  $options    radio button options array
     * @param array  $attributes array of HTML attributes, and special attributes above
     *
     * @return string completed radio widget set
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#radio
     */
    public function radio($fieldName, $options = [], $attributes = [])
    {
        $attributes = $this->_initInputField($fieldName, $attributes);
        $legend = false;

        if (isset($attributes['legend'])) {
            $legend = $attributes['legend'];
            unset($attributes['legend']);
        } elseif (count($options) > 1) {
            $legend = __(Inflector::humanize($this->field()), true);
        }
        $label = true;

        if (isset($attributes['label'])) {
            $label = $attributes['label'];
            unset($attributes['label']);
        }
        $inbetween = null;

        if (isset($attributes['separator'])) {
            $inbetween = $attributes['separator'];
            unset($attributes['separator']);
        }

        if (isset($attributes['value'])) {
            $value = $attributes['value'];
        } else {
            $value = $this->value($fieldName);
        }
        $out = [];

        $hiddenField = isset($attributes['hiddenField']) ? $attributes['hiddenField'] : true;
        unset($attributes['hiddenField']);

        foreach ($options as $optValue => $optTitle) {
            $optionsHere = ['value' => $optValue];

            if (isset($value) && '' !== $value && $optValue == $value) {
                $optionsHere['checked'] = 'checked';
            }
            $parsedOptions = $this->_parseAttributes(
                array_merge($attributes, $optionsHere),
                ['name', 'type', 'id'], '', ' '
            );
            $tagName = Inflector::camelize(
                $attributes['id'].'_'.Inflector::slug($optValue)
            );

            if ($label) {
                $optTitle = sprintf($this->Html->tags['label'], $tagName, null, $optTitle);
            }
            $out[] = sprintf(
                $this->Html->tags['radio'], $attributes['name'],
                $tagName, $parsedOptions, $optTitle
            );
        }
        $hidden = null;

        if ($hiddenField) {
            if (!isset($value) || '' === $value) {
                $hidden = $this->hidden($fieldName, [
                    'id' => $attributes['id'].'_', 'value' => '', 'name' => $attributes['name'],
                ]);
            }
        }
        $out = $hidden.implode($inbetween, $out);

        if ($legend) {
            $out = sprintf(
                $this->Html->tags['fieldset'], '',
                sprintf($this->Html->tags['legend'], $legend).$out
            );
        }

        return $out;
    }

    /**
     * Creates a text input widget.
     *
     * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
     * @param array  $options   array of HTML attributes
     *
     * @return string A generated HTML text input element
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#text
     */
    public function text($fieldName, $options = [])
    {
        $options = $this->_initInputField($fieldName, array_merge(
            ['type' => 'text'], $options
        ));

        return sprintf(
            $this->Html->tags['input'],
            $options['name'],
            $this->_parseAttributes($options, ['name'], null, ' ')
        );
    }

    /**
     * Creates a password input widget.
     *
     * @param string $fieldName Name of a field, like in the form "Modelname.fieldname"
     * @param array  $options   array of HTML attributes
     *
     * @return string a generated password input
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#password
     */
    public function password($fieldName, $options = [])
    {
        $options = $this->_initInputField($fieldName, $options);

        return sprintf(
            $this->Html->tags['password'],
            $options['name'],
            $this->_parseAttributes($options, ['name'], null, ' ')
        );
    }

    /**
     * Creates a textarea widget.
     *
     * ### Options:
     *
     * - `escape` - Whether or not the contents of the textarea should be escaped. Defaults to true.
     *
     * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
     * @param array  $options   array of HTML attributes, and special options above
     *
     * @return string A generated HTML text input element
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#textarea
     */
    public function textarea($fieldName, $options = [])
    {
        $options = $this->_initInputField($fieldName, $options);
        $value = null;

        if (array_key_exists('value', $options)) {
            $value = $options['value'];
            if (!array_key_exists('escape', $options) || false !== $options['escape']) {
                $value = h($value);
            }
            unset($options['value']);
        }

        return sprintf(
            $this->Html->tags['textarea'],
            $options['name'],
            $this->_parseAttributes($options, ['type', 'name'], null, ' '),
            $value
        );
    }

    /**
     * Creates a hidden input field.
     *
     * @param string $fieldName Name of a field, in the form of "Modelname.fieldname"
     * @param array  $options   array of HTML attributes
     *
     * @return string A generated hidden input
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#hidden
     */
    public function hidden($fieldName, $options = [])
    {
        $secure = true;

        if (isset($options['secure'])) {
            $secure = $options['secure'];
            unset($options['secure']);
        }
        $options = $this->_initInputField($fieldName, array_merge(
            $options, ['secure' => false]
        ));
        $model = $this->model();

        if ('_method' !== $fieldName && '_Token' !== $model && $secure) {
            $this->__secure(null, ''.$options['value']);
        }

        return sprintf(
            $this->Html->tags['hidden'],
            $options['name'],
            $this->_parseAttributes($options, ['name', 'class'], '', ' ')
        );
    }

    /**
     * Creates file input widget.
     *
     * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
     * @param array  $options   array of HTML attributes
     *
     * @return string a generated file input
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#file
     */
    public function file($fieldName, $options = [])
    {
        $options = array_merge($options, ['secure' => false]);
        $options = $this->_initInputField($fieldName, $options);
        $view = &ClassRegistry::getObject('view');
        $field = $view->entity();

        foreach (['name', 'type', 'tmp_name', 'error', 'size'] as $suffix) {
            $this->__secure(array_merge($field, [$suffix]));
        }

        $attributes = $this->_parseAttributes($options, ['name'], '', ' ');

        return sprintf($this->Html->tags['file'], $options['name'], $attributes);
    }

    /**
     * Creates a `<button>` tag.  The type attribute defaults to `type="submit"`
     * You can change it to a different value by using `$options['type']`.
     *
     * ### Options:
     *
     * - `escape` - HTML entity encode the $title of the button. Defaults to false.
     *
     * @param string $title   The button's caption. Not automatically HTML encoded
     * @param array  $options array of options and HTML attributes
     *
     * @return string a HTML button tag
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#button
     */
    public function button($title, $options = [])
    {
        $options += ['type' => 'submit', 'escape' => false];
        if ($options['escape']) {
            $title = h($title);
        }

        return sprintf(
            $this->Html->tags['button'],
            $options['type'],
            $this->_parseAttributes($options, ['type'], ' ', ''),
            $title
        );
    }

    /**
     * Creates a submit button element.  This method will generate `<input />` elements that
     * can be used to submit, and reset forms by using $options.  image submits can be created by supplying an
     * image path for $caption.
     *
     * ### Options
     *
     * - `div` - Include a wrapping div?  Defaults to true.  Accepts sub options similar to
     *   FormHelper::input().
     * - `before` - Content to include before the input.
     * - `after` - Content to include after the input.
     * - `type` - Set to 'reset' for reset inputs.  Defaults to 'submit'
     * - Other attributes will be assigned to the input element.
     *
     * ### Options
     *
     * - `div` - Include a wrapping div?  Defaults to true.  Accepts sub options similar to
     *   FormHelper::input().
     * - Other attributes will be assigned to the input element.
     *
     * @param string $caption The label appearing on the button OR if string contains :// or the
     *                        extension .jpg, .jpe, .jpeg, .gif, .png use an image if the extension
     *                        exists, AND the first character is /, image is relative to webroot,
     *                        OR if the first character is not /, image is relative to webroot/img.
     * @param array  $options Array of options.  See above.
     *
     * @return string A HTML submit button
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#submit
     */
    public function submit($caption = null, $options = [])
    {
        if (!is_string($caption) && empty($caption)) {
            $caption = __('Submit', true);
        }
        $out = null;
        $div = true;

        if (isset($options['div'])) {
            $div = $options['div'];
            unset($options['div']);
        }
        $options += ['type' => 'submit', 'before' => null, 'after' => null];
        $divOptions = ['tag' => 'div'];

        if (true === $div) {
            $divOptions['class'] = 'submit';
        } elseif (false === $div) {
            unset($divOptions);
        } elseif (is_string($div)) {
            $divOptions['class'] = $div;
        } elseif (is_array($div)) {
            $divOptions = array_merge(['class' => 'submit', 'tag' => 'div'], $div);
        }

        $before = $options['before'];
        $after = $options['after'];
        unset($options['before'], $options['after']);

        if (false !== strpos($caption, '://')) {
            unset($options['type']);
            $out .= $before.sprintf(
                $this->Html->tags['submitimage'],
                $caption,
                $this->_parseAttributes($options, null, '', ' ')
            ).$after;
        } elseif (preg_match('/\.(jpg|jpe|jpeg|gif|png|ico)$/', $caption)) {
            unset($options['type']);
            if ('/' !== $caption[0]) {
                $url = $this->webroot(IMAGES_URL.$caption);
            } else {
                $caption = trim($caption, '/');
                $url = $this->webroot($caption);
            }
            $out .= $before.sprintf(
                $this->Html->tags['submitimage'],
                $this->assetTimestamp($url),
                $this->_parseAttributes($options, null, '', ' ')
            ).$after;
        } else {
            $options['value'] = $caption;
            $out .= $before.sprintf(
                $this->Html->tags['submit'],
                $this->_parseAttributes($options, null, '', ' ')
            ).$after;
        }

        if (isset($divOptions)) {
            $tag = $divOptions['tag'];
            unset($divOptions['tag']);
            $out = $this->Html->tag($tag, $out, $divOptions);
        }

        return $out;
    }

    /**
     * Returns a formatted SELECT element.
     *
     * ### Attributes:
     *
     * - `showParents` - If included in the array and set to true, an additional option element
     *   will be added for the parent of each option group. You can set an option with the same name
     *   and it's key will be used for the value of the option.
     * - `multiple` - show a multiple select box.  If set to 'checkbox' multiple checkboxes will be
     *   created instead.
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     * - `escape` - If true contents of options will be HTML entity encoded. Defaults to true.
     * - `class` - When using multiple = checkbox the classname to apply to the divs. Defaults to 'checkbox'.
     *
     * ### Using options
     *
     * A simple array will create normal options:
     *
     * {{{
     * $options = array(1 => 'one', 2 => 'two);
     * $this->Form->select('Model.field', $options));
     * }}}
     *
     * While a nested options array will create optgroups with options inside them.
     * {{{
     * $options = array(
     *    1 => 'bill',
     *    'fred' => array(
     *        2 => 'fred',
     *        3 => 'fred jr.'
     *     )
     * );
     * $this->Form->select('Model.field', $options);
     * }}}
     *
     * In the above `2 => 'fred'` will not generate an option element.  You should enable the `showParents`
     * attribute to show the fred option.
     *
     * @param string $fieldName  Name attribute of the SELECT
     * @param array  $options    Array of the OPTION elements (as 'value'=>'Text' pairs) to be used in the
     *                           SELECT element
     * @param mixed  $selected   The option selected by default.  If null, the default value
     *                           from POST data will be used when available.
     * @param array  $attributes the HTML attributes of the select element
     *
     * @return string Formatted SELECT element
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#select
     */
    public function select($fieldName, $options = [], $selected = null, $attributes = [])
    {
        $select = [];
        $style = null;
        $tag = null;
        $attributes += [
            'class' => null,
            'escape' => true,
            'secure' => null,
            'empty' => '',
            'showParents' => false,
            'hiddenField' => true,
        ];

        $escapeOptions = $this->_extractOption('escape', $attributes);
        $secure = $this->_extractOption('secure', $attributes);
        $showEmpty = $this->_extractOption('empty', $attributes);
        $showParents = $this->_extractOption('showParents', $attributes);
        $hiddenField = $this->_extractOption('hiddenField', $attributes);
        unset($attributes['escape'], $attributes['secure'], $attributes['empty'], $attributes['showParents'], $attributes['hiddenField']);

        $attributes = $this->_initInputField($fieldName, array_merge(
            (array) $attributes, ['secure' => false]
        ));

        if (is_string($options) && isset($this->__options[$options])) {
            $options = $this->__generateOptions($options);
        } elseif (!is_array($options)) {
            $options = [];
        }
        if (isset($attributes['type'])) {
            unset($attributes['type']);
        }

        if (!isset($selected)) {
            $selected = $attributes['value'];
        }

        if (!empty($attributes['multiple'])) {
            $style = ('checkbox' === $attributes['multiple']) ? 'checkbox' : null;
            $template = ($style) ? 'checkboxmultiplestart' : 'selectmultiplestart';
            $tag = $this->Html->tags[$template];
            if ($hiddenField) {
                $hiddenAttributes = [
                    'value' => '',
                    'id' => $attributes['id'].($style ? '' : '_'),
                    'secure' => false,
                    'name' => $attributes['name'],
                ];
                $select[] = $this->hidden(null, $hiddenAttributes);
            }
        } else {
            $tag = $this->Html->tags['selectstart'];
        }

        if (!empty($tag) || isset($template)) {
            if (!isset($secure) || true == $secure) {
                $this->__secure();
            }
            $select[] = sprintf($tag, $attributes['name'], $this->_parseAttributes(
                $attributes, ['name', 'value'])
            );
        }
        $emptyMulti = (
            null !== $showEmpty && false !== $showEmpty && !(
                empty($showEmpty) && (isset($attributes) &&
                array_key_exists('multiple', $attributes))
            )
        );

        if ($emptyMulti) {
            $showEmpty = (true === $showEmpty) ? '' : $showEmpty;
            $options = array_reverse($options, true);
            $options[''] = $showEmpty;
            $options = array_reverse($options, true);
        }

        $select = array_merge($select, $this->__selectOptions(
            array_reverse($options, true),
            $selected,
            [],
            $showParents,
            ['escape' => $escapeOptions, 'style' => $style, 'name' => $attributes['name'], 'class' => $attributes['class']]
        ));

        $template = ('checkbox' == $style) ? 'checkboxmultipleend' : 'selectend';
        $select[] = $this->Html->tags[$template];

        return implode("\n", $select);
    }

    /**
     * Returns a SELECT element for days.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     *
     * @param string $fieldName  Prefix name for the SELECT element
     * @param string $selected   option which is selected
     * @param array  $attributes HTML attributes for the select element
     *
     * @return string a generated day select box
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#day
     */
    public function day($fieldName, $selected = null, $attributes = [])
    {
        $attributes += ['empty' => true];
        $selected = $this->__dateTimeSelected('day', $fieldName, $selected, $attributes);

        if (strlen($selected) > 2) {
            $selected = date('d', strtotime($selected));
        } elseif (false === $selected) {
            $selected = null;
        }

        return $this->select($fieldName.'.day', $this->__generateOptions('day'), $selected, $attributes);
    }

    /**
     * Returns a SELECT element for years.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     * - `orderYear` - Ordering of year values in select options.
     *   Possible values 'asc', 'desc'. Default 'desc'
     *
     * @param string $fieldName  Prefix name for the SELECT element
     * @param int    $minYear    First year in sequence
     * @param int    $maxYear    Last year in sequence
     * @param string $selected   option which is selected
     * @param array  $attributes attribute array for the select elements
     *
     * @return string Completed year select input
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#year
     */
    public function year($fieldName, $minYear = null, $maxYear = null, $selected = null, $attributes = [])
    {
        $attributes += ['empty' => true];
        if ((empty($selected) || true === $selected) && $value = $this->value($fieldName)) {
            if (is_array($value)) {
                extract($value);
                $selected = $year;
            } else {
                if (empty($value)) {
                    if (!$attributes['empty'] && !$maxYear) {
                        $selected = 'now';
                    } elseif (!$attributes['empty'] && $maxYear && !$selected) {
                        $selected = $maxYear;
                    }
                } else {
                    $selected = $value;
                }
            }
        }

        if (strlen($selected) > 4 || 'now' === $selected) {
            $selected = date('Y', strtotime($selected));
        } elseif (false === $selected) {
            $selected = null;
        }
        $yearOptions = ['min' => $minYear, 'max' => $maxYear, 'order' => 'desc'];
        if (isset($attributes['orderYear'])) {
            $yearOptions['order'] = $attributes['orderYear'];
            unset($attributes['orderYear']);
        }

        return $this->select(
            $fieldName.'.year', $this->__generateOptions('year', $yearOptions),
            $selected, $attributes
        );
    }

    /**
     * Returns a SELECT element for months.
     *
     * ### Attributes:
     *
     * - `monthNames` - If false, 2 digit numbers will be used instead of text.
     *   If a array, the given array will be used.
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     *
     * @param string $fieldName  Prefix name for the SELECT element
     * @param string $selected   option which is selected
     * @param array  $attributes Attributes for the select element
     *
     * @return string a generated month select dropdown
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#month
     */
    public function month($fieldName, $selected = null, $attributes = [])
    {
        $attributes += ['empty' => true];
        $selected = $this->__dateTimeSelected('month', $fieldName, $selected, $attributes);

        if (strlen($selected) > 2) {
            $selected = date('m', strtotime($selected));
        } elseif (false === $selected) {
            $selected = null;
        }
        $defaults = ['monthNames' => true];
        $attributes = array_merge($defaults, (array) $attributes);
        $monthNames = $attributes['monthNames'];
        unset($attributes['monthNames']);

        return $this->select(
            $fieldName.'.month',
            $this->__generateOptions('month', ['monthNames' => $monthNames]),
            $selected, $attributes
        );
    }

    /**
     * Returns a SELECT element for hours.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     *
     * @param string $fieldName     Prefix name for the SELECT element
     * @param bool   $format24Hours True for 24 hours format
     * @param string $selected      option which is selected
     * @param array  $attributes    List of HTML attributes
     *
     * @return string Completed hour select input
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#hour
     */
    public function hour($fieldName, $format24Hours = false, $selected = null, $attributes = [])
    {
        $attributes += ['empty' => true];
        $selected = $this->__dateTimeSelected('hour', $fieldName, $selected, $attributes);

        if (strlen($selected) > 2) {
            if ($format24Hours) {
                $selected = date('H', strtotime($selected));
            } else {
                $selected = date('g', strtotime($selected));
            }
        } elseif (false === $selected) {
            $selected = null;
        }

        return $this->select(
            $fieldName.'.hour',
            $this->__generateOptions($format24Hours ? 'hour24' : 'hour'),
            $selected, $attributes
        );
    }

    /**
     * Returns a SELECT element for minutes.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     *
     * @param string $fieldName  Prefix name for the SELECT element
     * @param string $selected   option which is selected
     * @param string $attributes Array of Attributes
     *
     * @return string completed minute select input
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#minute
     */
    public function minute($fieldName, $selected = null, $attributes = [])
    {
        $attributes += ['empty' => true];
        $selected = $this->__dateTimeSelected('min', $fieldName, $selected, $attributes);

        if (strlen($selected) > 2) {
            $selected = date('i', strtotime($selected));
        } elseif (false === $selected) {
            $selected = null;
        }
        $minuteOptions = [];

        if (isset($attributes['interval'])) {
            $minuteOptions['interval'] = $attributes['interval'];
            unset($attributes['interval']);
        }

        return $this->select(
            $fieldName.'.min', $this->__generateOptions('minute', $minuteOptions),
            $selected, $attributes
        );
    }

    /**
     * Selects values for dateTime selects.
     *
     * @param string $select     Name of element field. ex. 'day'
     * @param string $fieldName  Name of fieldName being generated ex. Model.created
     * @param mixed  $selected   the current selected value
     * @param array  $attributes array of attributes, must contain 'empty' key
     *
     * @return string currently selected value
     */
    public function __dateTimeSelected($select, $fieldName, $selected, $attributes)
    {
        if ((empty($selected) || true === $selected) && $value = $this->value($fieldName)) {
            if (is_array($value) && isset($value[$select])) {
                $selected = $value[$select];
            } else {
                if (empty($value)) {
                    if (!$attributes['empty']) {
                        $selected = 'now';
                    }
                } else {
                    $selected = $value;
                }
            }
        }

        return $selected;
    }

    /**
     * Returns a SELECT element for AM or PM.
     *
     * ### Attributes:
     *
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     *
     * @param string $fieldName  Prefix name for the SELECT element
     * @param string $selected   option which is selected
     * @param string $attributes Array of Attributes
     * @param bool   $showEmpty  Show/Hide an empty option
     *
     * @return string Completed meridian select input
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#meridian
     */
    public function meridian($fieldName, $selected = null, $attributes = [])
    {
        $attributes += ['empty' => true];
        if ((empty($selected) || true === $selected) && $value = $this->value($fieldName)) {
            if (is_array($value)) {
                extract($value);
                $selected = $meridian;
            } else {
                if (empty($value)) {
                    if (!$attribues['empty']) {
                        $selected = date('a');
                    }
                } else {
                    $selected = date('a', strtotime($value));
                }
            }
        }

        if (false === $selected) {
            $selected = null;
        }

        return $this->select(
            $fieldName.'.meridian', $this->__generateOptions('meridian'),
            $selected, $attributes
        );
    }

    /**
     * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
     *
     * ### Attributes:
     *
     * - `monthNames` If false, 2 digit numbers will be used instead of text.
     *   If a array, the given array will be used.
     * - `minYear` The lowest year to use in the year select
     * - `maxYear` The maximum year to use in the year select
     * - `interval` The interval for the minutes select. Defaults to 1
     * - `separator` The contents of the string between select elements. Defaults to '-'
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     * - `value` | `default` The default value to be used by the input.  A value in `$this->data`
     *   matching the field name will override this value.  If no default is provided `time()` will be used.
     *
     * @param string $fieldName  Prefix name for the SELECT element
     * @param string $dateFormat DMY, MDY, YMD
     * @param string $timeFormat 12, 24
     * @param string $selected   option which is selected
     * @param string $attributes array of Attributes
     *
     * @return string generated set of select boxes for the date and time formats chosen
     *
     * @see http://book.cakephp.org/1.3/en/The-Manual/Core-Helpers/Form.html#dateTime
     */
    public function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $selected = null, $attributes = [])
    {
        $attributes += ['empty' => true];
        $year = $month = $day = $hour = $min = $meridian = null;

        if (empty($selected)) {
            $selected = $this->value($attributes, $fieldName);
            if (isset($selected['value'])) {
                $selected = $selected['value'];
            } else {
                $selected = null;
            }
        }

        if (null === $selected && true != $attributes['empty']) {
            $selected = time();
        }

        if (!empty($selected)) {
            if (is_array($selected)) {
                extract($selected);
            } else {
                if (is_numeric($selected)) {
                    $selected = strftime('%Y-%m-%d %H:%M:%S', $selected);
                }
                $meridian = 'am';
                $pos = strpos($selected, '-');
                if (false !== $pos) {
                    $date = explode('-', $selected);
                    $days = explode(' ', $date[2]);
                    $day = $days[0];
                    $month = $date[1];
                    $year = $date[0];
                } else {
                    $days[1] = $selected;
                }

                if (!empty($timeFormat)) {
                    $time = explode(':', $days[1]);

                    if (($time[0] > 12) && '12' == $timeFormat) {
                        $time[0] = $time[0] - 12;
                        $meridian = 'pm';
                    } elseif ('12' == $time[0] && '12' == $timeFormat) {
                        $meridian = 'pm';
                    } elseif ('00' == $time[0] && '12' == $timeFormat) {
                        $time[0] = 12;
                    } elseif ($time[0] > 12) {
                        $meridian = 'pm';
                    }
                    if (0 == $time[0] && '12' == $timeFormat) {
                        $time[0] = 12;
                    }
                    $hour = $min = null;
                    if (isset($time[1])) {
                        $hour = $time[0];
                        $min = $time[1];
                    }
                }
            }
        }

        $elements = ['Day', 'Month', 'Year', 'Hour', 'Minute', 'Meridian'];
        $defaults = [
            'minYear' => null, 'maxYear' => null, 'separator' => '-',
            'interval' => 1, 'monthNames' => true,
        ];
        $attributes = array_merge($defaults, (array) $attributes);
        if (isset($attributes['minuteInterval'])) {
            $attributes['interval'] = $attributes['minuteInterval'];
            unset($attributes['minuteInterval']);
        }
        $minYear = $attributes['minYear'];
        $maxYear = $attributes['maxYear'];
        $separator = $attributes['separator'];
        $interval = $attributes['interval'];
        $monthNames = $attributes['monthNames'];
        $attributes = array_diff_key($attributes, $defaults);

        if (isset($attributes['id'])) {
            if (is_string($attributes['id'])) {
                // build out an array version
                foreach ($elements as $element) {
                    $selectAttrName = 'select'.$element.'Attr';
                    ${$selectAttrName} = $attributes;
                    ${$selectAttrName}['id'] = $attributes['id'].$element;
                }
            } elseif (is_array($attributes['id'])) {
                // check for missing ones and build selectAttr for each element
                $attributes['id'] += [
                    'month' => '', 'year' => '', 'day' => '',
                    'hour' => '', 'minute' => '', 'meridian' => '',
                ];
                foreach ($elements as $element) {
                    $selectAttrName = 'select'.$element.'Attr';
                    ${$selectAttrName} = $attributes;
                    ${$selectAttrName}['id'] = $attributes['id'][strtolower($element)];
                }
            }
        } else {
            // build the selectAttrName with empty id's to pass
            foreach ($elements as $element) {
                $selectAttrName = 'select'.$element.'Attr';
                ${$selectAttrName} = $attributes;
            }
        }

        $selects = [];
        foreach (preg_split('//', $dateFormat, -1, PREG_SPLIT_NO_EMPTY) as $char) {
            switch ($char) {
                case 'Y':
                    $selects[] = $this->year(
                        $fieldName, $minYear, $maxYear, $year, $selectYearAttr
                    );
                break;
                case 'M':
                    $selectMonthAttr['monthNames'] = $monthNames;
                    $selects[] = $this->month($fieldName, $month, $selectMonthAttr);
                break;
                case 'D':
                    $selects[] = $this->day($fieldName, $day, $selectDayAttr);
                break;
            }
        }
        $opt = implode($separator, $selects);

        if (!empty($interval) && $interval > 1 && !empty($min)) {
            $min = round($min * (1 / $interval)) * $interval;
        }
        $selectMinuteAttr['interval'] = $interval;
        switch ($timeFormat) {
            case '24':
                $opt .= $this->hour($fieldName, true, $hour, $selectHourAttr).':'.
                $this->minute($fieldName, $min, $selectMinuteAttr);
            break;
            case '12':
                $opt .= $this->hour($fieldName, false, $hour, $selectHourAttr).':'.
                $this->minute($fieldName, $min, $selectMinuteAttr).' '.
                $this->meridian($fieldName, $meridian, $selectMeridianAttr);
            break;
            default:
                $opt .= '';
            break;
        }

        return $opt;
    }

    /**
     * Gets the input field name for the current tag.
     *
     * @param array  $options
     * @param string $key
     *
     * @return array
     */
    public function _name($options = [], $field = null, $key = 'name')
    {
        if ('get' == $this->requestType) {
            if (null === $options) {
                $options = [];
            } elseif (is_string($options)) {
                $field = $options;
                $options = 0;
            }

            if (!empty($field)) {
                $this->setEntity($field);
            }

            if (is_array($options) && isset($options[$key])) {
                return $options;
            }

            $view = ClassRegistry::getObject('view');
            $name = !empty($view->field) ? $view->field : $view->model;
            if (!empty($view->fieldSuffix)) {
                $name .= '['.$view->fieldSuffix.']';
            }

            if (is_array($options)) {
                $options[$key] = $name;

                return $options;
            } else {
                return $name;
            }
        }

        return parent::_name($options, $field, $key);
    }

    /**
     * Returns an array of formatted OPTION/OPTGROUP elements.
     *
     * @return array
     */
    public function __selectOptions($elements = [], $selected = null, $parents = [], $showParents = null, $attributes = [])
    {
        $select = [];
        $attributes = array_merge(['escape' => true, 'style' => null, 'class' => null], $attributes);
        $selectedIsEmpty = ('' === $selected || null === $selected);
        $selectedIsArray = is_array($selected);

        foreach ($elements as $name => $title) {
            $htmlOptions = [];
            if (is_array($title) && (!isset($title['name']) || !isset($title['value']))) {
                if (!empty($name)) {
                    if ('checkbox' === $attributes['style']) {
                        $select[] = $this->Html->tags['fieldsetend'];
                    } else {
                        $select[] = $this->Html->tags['optiongroupend'];
                    }
                    $parents[] = $name;
                }
                $select = array_merge($select, $this->__selectOptions(
                    $title, $selected, $parents, $showParents, $attributes
                ));

                if (!empty($name)) {
                    $name = $attributes['escape'] ? h($name) : $name;
                    if ('checkbox' === $attributes['style']) {
                        $select[] = sprintf($this->Html->tags['fieldsetstart'], $name);
                    } else {
                        $select[] = sprintf($this->Html->tags['optiongroup'], $name, '');
                    }
                }
                $name = null;
            } elseif (is_array($title)) {
                $htmlOptions = $title;
                $name = $title['value'];
                $title = $title['name'];
                unset($htmlOptions['name'], $htmlOptions['value']);
            }

            if (null !== $name) {
                if (
                    (!$selectedIsArray && !$selectedIsEmpty && (string) $selected == (string) $name) ||
                    ($selectedIsArray && in_array($name, $selected))
                ) {
                    if ('checkbox' === $attributes['style']) {
                        $htmlOptions['checked'] = true;
                    } else {
                        $htmlOptions['selected'] = 'selected';
                    }
                }

                if ($showParents || (!in_array($title, $parents))) {
                    $title = ($attributes['escape']) ? h($title) : $title;

                    if ('checkbox' === $attributes['style']) {
                        $htmlOptions['value'] = $name;

                        $tagName = Inflector::camelize(
                            $this->model().'_'.$this->field().'_'.Inflector::slug($name)
                        );
                        $htmlOptions['id'] = $tagName;
                        $label = ['for' => $tagName];

                        if (isset($htmlOptions['checked']) && true === $htmlOptions['checked']) {
                            $label['class'] = 'selected';
                        }

                        $name = $attributes['name'];

                        if (empty($attributes['class'])) {
                            $attributes['class'] = 'checkbox';
                        } elseif ('form-error' === $attributes['class']) {
                            $attributes['class'] = 'checkbox '.$attributes['class'];
                        }
                        $label = $this->label(null, $title, $label);
                        $item = sprintf(
                            $this->Html->tags['checkboxmultiple'], $name,
                            $this->_parseAttributes($htmlOptions)
                        );
                        $select[] = $this->Html->div($attributes['class'], $item.$label);
                    } else {
                        $select[] = sprintf(
                            $this->Html->tags['selectoption'],
                            $name, $this->_parseAttributes($htmlOptions), $title
                        );
                    }
                }
            }
        }

        return array_reverse($select, true);
    }

    /**
     * Generates option lists for common <select /> menus.
     */
    public function __generateOptions($name, $options = [])
    {
        if (!empty($this->options[$name])) {
            return $this->options[$name];
        }
        $data = [];

        switch ($name) {
            case 'minute':
                if (isset($options['interval'])) {
                    $interval = $options['interval'];
                } else {
                    $interval = 1;
                }
                $i = 0;
                while ($i < 60) {
                    $data[sprintf('%02d', $i)] = sprintf('%02d', $i);
                    $i += $interval;
                }
            break;
            case 'hour':
                for ($i = 1; $i <= 12; ++$i) {
                    $data[sprintf('%02d', $i)] = $i;
                }
            break;
            case 'hour24':
                for ($i = 0; $i <= 23; ++$i) {
                    $data[sprintf('%02d', $i)] = $i;
                }
            break;
            case 'meridian':
                $data = ['am' => 'am', 'pm' => 'pm'];
            break;
            case 'day':
                $min = 1;
                $max = 31;

                if (isset($options['min'])) {
                    $min = $options['min'];
                }
                if (isset($options['max'])) {
                    $max = $options['max'];
                }

                for ($i = $min; $i <= $max; ++$i) {
                    $data[sprintf('%02d', $i)] = $i;
                }
            break;
            case 'month':
                if (true === $options['monthNames']) {
                    $data['01'] = __('January', true);
                    $data['02'] = __('February', true);
                    $data['03'] = __('March', true);
                    $data['04'] = __('April', true);
                    $data['05'] = __('May', true);
                    $data['06'] = __('June', true);
                    $data['07'] = __('July', true);
                    $data['08'] = __('August', true);
                    $data['09'] = __('September', true);
                    $data['10'] = __('October', true);
                    $data['11'] = __('November', true);
                    $data['12'] = __('December', true);
                } elseif (is_array($options['monthNames'])) {
                    $data = $options['monthNames'];
                } else {
                    for ($m = 1; $m <= 12; ++$m) {
                        $data[sprintf('%02s', $m)] = strftime('%m', mktime(1, 1, 1, $m, 1, 1999));
                    }
                }
            break;
            case 'year':
                $current = intval(date('Y'));

                if (!isset($options['min'])) {
                    $min = $current - 20;
                } else {
                    $min = $options['min'];
                }

                if (!isset($options['max'])) {
                    $max = $current + 20;
                } else {
                    $max = $options['max'];
                }
                if ($min > $max) {
                    list($min, $max) = [$max, $min];
                }
                for ($i = $min; $i <= $max; ++$i) {
                    $data[$i] = $i;
                }
                if ('asc' != $options['order']) {
                    $data = array_reverse($data, true);
                }
            break;
        }
        $this->__options[$name] = $data;

        return $this->__options[$name];
    }

    /**
     * Sets field defaults and adds field to form security input hash.
     *
     * Options
     *
     *  - `secure` - boolean whether or not the field should be added to the security fields.
     *
     * @param string $field   name of the field to initialize options for
     * @param array  $options array of options to append options into
     *
     * @return array array of options for the input
     */
    public function _initInputField($field, $options = [])
    {
        if (isset($options['secure'])) {
            $secure = $options['secure'];
            unset($options['secure']);
        } else {
            $secure = (isset($this->params['_Token']) && !empty($this->params['_Token']));
        }

        $fieldName = null;
        if ($secure && !empty($options['name'])) {
            preg_match_all('/\[(.*?)\]/', $options['name'], $matches);
            if (isset($matches[1])) {
                $fieldName = $matches[1];
            }
        }

        $result = parent::_initInputField($field, $options);

        if ($secure) {
            $this->__secure($fieldName);
        }

        return $result;
    }
}

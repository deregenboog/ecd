<?php
/**
 * MooTools Engine Helper for JsHelper.
 *
 * Provides MooTools specific Javascript for JsHelper.
 * Assumes that you have the following MooTools packages
 *
 * - Remote, Remote.HTML, Remote.JSON
 * - Fx, Fx.Tween, Fx.Morph
 * - Selectors, DomReady,
 * - Drag, Drag.Move
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
 * @since         CakePHP(tm) v 1.3
 *
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::import('Helper', 'Js');

/**
 * Mootools Engine Helper for JsHelper.
 */
class MootoolsEngineHelper extends JsBaseEngineHelper
{
    /**
     * Option mappings for MooTools.
     *
     * @var array
     */
    public $_optionMap = [
        'request' => [
            'complete' => 'onComplete',
            'success' => 'onSuccess',
            'before' => 'onRequest',
            'error' => 'onFailure',
        ],
        'sortable' => [
            'distance' => 'snap',
            'containment' => 'constrain',
            'sort' => 'onSort',
            'complete' => 'onComplete',
            'start' => 'onStart',
        ],
        'drag' => [
            'snapGrid' => 'snap',
            'start' => 'onStart',
            'drag' => 'onDrag',
            'stop' => 'onComplete',
        ],
        'drop' => [
            'drop' => 'onDrop',
            'hover' => 'onEnter',
            'leave' => 'onLeave',
        ],
        'slider' => [
            'complete' => 'onComplete',
            'change' => 'onChange',
            'direction' => 'mode',
            'step' => 'steps',
        ],
    ];

    /**
     * Contains a list of callback names -> default arguments.
     *
     * @var array
     */
    public $_callbackArguments = [
        'slider' => [
            'onTick' => 'position',
            'onChange' => 'step',
            'onComplete' => 'event',
        ],
        'request' => [
            'onRequest' => '',
            'onComplete' => '',
            'onCancel' => '',
            'onSuccess' => 'responseText, responseXML',
            'onFailure' => 'xhr',
            'onException' => 'headerName, value',
        ],
        'drag' => [
            'onBeforeStart' => 'element',
            'onStart' => 'element',
            'onSnap' => 'element',
            'onDrag' => 'element, event',
            'onComplete' => 'element, event',
            'onCancel' => 'element',
        ],
        'drop' => [
            'onBeforeStart' => 'element',
            'onStart' => 'element',
            'onSnap' => 'element',
            'onDrag' => 'element, event',
            'onComplete' => 'element, event',
            'onCancel' => 'element',
            'onDrop' => 'element, droppable, event',
            'onLeave' => 'element, droppable',
            'onEnter' => 'element, droppable',
        ],
        'sortable' => [
            'onStart' => 'element, clone',
            'onSort' => 'element, clone',
            'onComplete' => 'element',
        ],
    ];

    /**
     * Create javascript selector for a CSS rule.
     *
     * @param string $selector The selector that is targeted
     *
     * @return object instance of $this. Allows chained methods.
     */
    public function get($selector)
    {
        $this->_multipleSelection = false;
        if ('window' == $selector || 'document' == $selector) {
            $this->selection = '$('.$selector.')';

            return $this;
        }
        if (preg_match('/^#[^\s.]+$/', $selector)) {
            $this->selection = '$("'.substr($selector, 1).'")';

            return $this;
        }
        $this->_multipleSelection = true;
        $this->selection = '$$("'.$selector.'")';

        return $this;
    }

    /**
     * Add an event to the script cache. Operates on the currently selected elements.
     *
     * ### Options
     *
     * - 'wrap' - Whether you want the callback wrapped in an anonymous function. (defaults true)
     * - 'stop' - Whether you want the event to stopped. (defaults true)
     *
     * @param string $type     Type of event to bind to the current dom id
     * @param string $callback The Javascript function you wish to trigger or the function literal
     * @param array  $options  options for the event
     *
     * @return string completed event handler
     */
    public function event($type, $callback, $options = [])
    {
        $defaults = ['wrap' => true, 'stop' => true];
        $options = array_merge($defaults, $options);

        $function = 'function (event) {%s}';
        if ($options['wrap'] && $options['stop']) {
            $callback = "event.stop();\n".$callback;
        }
        if ($options['wrap']) {
            $callback = sprintf($function, $callback);
        }
        $out = $this->selection.".addEvent(\"{$type}\", $callback);";

        return $out;
    }

    /**
     * Create a domReady event. This is a special event in many libraries.
     *
     * @param string $functionBody The code to run on domReady
     *
     * @return string completed domReady method
     */
    public function domReady($functionBody)
    {
        $this->selection = 'window';

        return $this->event('domready', $functionBody, ['stop' => false]);
    }

    /**
     * Create an iteration over the current selection result.
     *
     * @param string $method   The method you want to apply to the selection
     * @param string $callback the function body you wish to apply during the iteration
     *
     * @return string completed iteration
     */
    public function each($callback)
    {
        return $this->selection.'.each(function (item, index) {'.$callback.'});';
    }

    /**
     * Trigger an Effect.
     *
     * @param string $name    the name of the effect to trigger
     * @param array  $options array of options for the effect
     *
     * @return string completed string with effect
     *
     * @see JsBaseEngineHelper::effect()
     */
    public function effect($name, $options = [])
    {
        $speed = null;
        if (isset($options['speed']) && in_array($options['speed'], ['fast', 'slow'])) {
            if ('fast' == $options['speed']) {
                $speed = '"short"';
            } elseif ('slow' == $options['speed']) {
                $speed = '"long"';
            }
        }
        $effect = '';
        switch ($name) {
            case 'hide':
                $effect = 'setStyle("display", "none")';
            break;
            case 'show':
                $effect = 'setStyle("display", "")';
            break;
            case 'fadeIn':
            case 'fadeOut':
            case 'slideIn':
            case 'slideOut':
                list($effectName, $direction) = preg_split('/([A-Z][a-z]+)/', $name, -1, PREG_SPLIT_DELIM_CAPTURE);
                $direction = strtolower($direction);
                if ($speed) {
                    $effect .= "set(\"$effectName\", {duration:$speed}).";
                }
                $effect .= "$effectName(\"$direction\")";
            break;
        }

        return $this->selection.'.'.$effect.';';
    }

    /**
     * Create an new Request.
     *
     * Requires `Request`.  If you wish to use 'update' key you must have ```Request.HTML```
     * if you wish to do Json requests you will need ```JSON``` and ```Request.JSON```.
     *
     * @param mixed $url
     * @param array $options
     *
     * @return string the completed ajax call
     */
    public function request($url, $options = [])
    {
        $url = $this->url($url);
        $options = $this->_mapOptions('request', $options);
        $type = $data = null;
        if (isset($options['type']) || isset($options['update'])) {
            if (isset($options['type']) && 'json' == strtolower($options['type'])) {
                $type = '.JSON';
            }
            if (isset($options['update'])) {
                $options['update'] = str_replace('#', '', $options['update']);
                $type = '.HTML';
            }
            unset($options['type']);
        }
        if (!empty($options['data'])) {
            $data = $options['data'];
            unset($options['data']);
        }
        $options['url'] = $url;
        $options = $this->_prepareCallbacks('request', $options);
        if (!empty($options['dataExpression'])) {
            $callbacks[] = 'data';
            unset($options['dataExpression']);
        } elseif (!empty($data)) {
            $data = $this->object($data);
        }
        $options = $this->_parseOptions($options, array_keys($this->_callbackArguments['request']));

        return "var jsRequest = new Request$type({{$options}}).send($data);";
    }

    /**
     * Create a sortable element.
     *
     * Requires the `Sortables` plugin from MootoolsMore
     *
     * @param array $options array of options for the sortable
     *
     * @return string completed sortable script
     *
     * @see JsBaseEngineHelper::sortable() for options list.
     */
    public function sortable($options = [])
    {
        $options = $this->_processOptions('sortable', $options);

        return 'var jsSortable = new Sortables('.$this->selection.', {'.$options.'});';
    }

    /**
     * Create a Draggable element.
     *
     * Requires the `Drag` plugin from MootoolsMore
     *
     * @param array $options array of options for the draggable
     *
     * @return string completed draggable script
     *
     * @see JsHelper::drag() for options list.
     */
    public function drag($options = [])
    {
        $options = $this->_processOptions('drag', $options);

        return $this->selection.'.makeDraggable({'.$options.'});';
    }

    /**
     * Create a Droppable element.
     *
     * Requires the `Drag` and `Drag.Move` plugins from MootoolsMore
     *
     * Droppables in Mootools function differently from other libraries.  Droppables
     * are implemented as an extension of Drag.  So in addtion to making a get() selection for
     * the droppable element. You must also provide a selector rule to the draggable element. Furthermore,
     * Mootools droppables inherit all options from Drag.
     *
     * @param array $options array of options for the droppable
     *
     * @return string completed droppable script
     *
     * @see JsBaseEngineHelper::drop() for options list.
     */
    public function drop($options = [])
    {
        if (empty($options['drag'])) {
            trigger_error(
                __('MootoolsEngine::drop() requires a "drag" option to properly function', true), E_USER_WARNING
            );

            return false;
        }
        $options['droppables'] = $this->selection;

        $this->get($options['drag']);
        unset($options['drag']);

        $options = $this->_mapOptions('drag', $this->_mapOptions('drop', $options));
        $options = $this->_prepareCallbacks('drop', $options);
        $safe = array_merge(array_keys($this->_callbackArguments['drop']), ['droppables']);
        $optionString = $this->_parseOptions($options, $safe);
        $out = $this->selection.'.makeDraggable({'.$optionString.'});';
        $this->selection = $options['droppables'];

        return $out;
    }

    /**
     * Create a slider control.
     *
     * Requires `Slider` from MootoolsMore
     *
     * @param array $options array of options for the slider
     *
     * @return string completed slider script
     *
     * @see JsBaseEngineHelper::slider() for options list.
     */
    public function slider($options = [])
    {
        $slider = $this->selection;
        $this->get($options['handle']);
        unset($options['handle']);

        if (isset($options['min']) && isset($options['max'])) {
            $options['range'] = [$options['min'], $options['max']];
            unset($options['min'], $options['max']);
        }
        $optionString = $this->_processOptions('slider', $options);
        if (!empty($optionString)) {
            $optionString = ', {'.$optionString.'}';
        }
        $out = 'var jsSlider = new Slider('.$slider.', '.$this->selection.$optionString.');';
        $this->selection = $slider;

        return $out;
    }

    /**
     * Serialize the form attached to $selector.
     *
     * @param array $options array of options
     *
     * @return string Completed serializeForm() snippet
     *
     * @see JsBaseEngineHelper::serializeForm()
     */
    public function serializeForm($options = [])
    {
        $options = array_merge(['isForm' => false, 'inline' => false], $options);
        $selection = $this->selection;
        if (!$options['isForm']) {
            $selection = '$('.$this->selection.'.form)';
        }
        $method = '.toQueryString()';
        if (!$options['inline']) {
            $method .= ';';
        }

        return $selection.$method;
    }
}

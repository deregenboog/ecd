<?php
/**
 * loads a component on the fly from within the controller.
 */
class ComponentLoaderComponent extends Object
{
    public $controller = null;

    public function initialize(&$controller)
    {
        // saving the controller reference for later use
        $this->controller = &$controller;
    }

    public function load($component_name, $settings = array())
    {
        App::import('Component', $component_name);
        $component2 = $component_name.'Component';
        $component = &new $component2(null);

        if (method_exists($component, 'initialize')) {
            $component->initialize($this->controller, $settings);
        }

        if (method_exists($component, 'startup')) {
            $component->startup($this->controller);
        }

        $this->controller->{$component_name} = &$component;
    }
}

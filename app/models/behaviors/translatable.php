<?php

/** Translate validation rule messages, and other stuff. */
class TranslatableBehavior extends ModelBehavior
{
    public $settings = [];
    public $defaults = [];

    public function setup(&$Model, $config = [])
    {
        if (!is_array($config)) {
            $config = [];
        }
        $this->settings[$Model->alias] = array_merge($this->defaults, $config);

        $this->translateValidationMessages($Model);
    }

    public function settings(&$Model)
    {
        return $this->settings[$Model->alias];
    }

    public function translateValidationMessages(&$Model)
    {
        if (!isset($Model->validate) || !is_array($Model->validate)) {
            return;
        }

        foreach ($Model->validate as &$rule) {
            if (isset($rule['message'])) {
                $rule['message'] = __($rule['message'], true);
                     // Make a method to dump all messages to a dummy file,
                     // so that we can generate po files with the cake console.
            }
        }
    }
}

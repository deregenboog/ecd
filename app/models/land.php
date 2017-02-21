<?php

class Land extends AppModel
{
    public $name = 'Land';

    public $displayField = 'land';

    public $order = 'land';

    public $cachekey = 'LandenList';

    private $preferredCountries = ['Onbekend'];

    public function beforeSave($options = [])
    {
        Cache::delete($this->cachekey);

        return parent::beforeSave($options);
    }

    public function findList()
    {
        $landen = Cache::read($this->cachekey);

        if (!empty($landen)) {
            return $landen;
        }

        $landen = $this->find('list');

        // move preferred countries to top of list
        foreach ($this->preferredCountries as $preferredCountry) {
            $key = array_search($preferredCountry, $landen);
            if (false !== $key) {
                unset($landen[ $key]);
                $landen = [(string) $key => $preferredCountry] + $landen;
            }
        }

        Cache::write($this->cachekey, $landen);

        return $landen;
    }
}

<?php

class AdminController extends AppController
{
    public $name = 'Admin';
    public $uses = [];
    public $components = [];

    public function index()
    {
    }

    public function uit_dienst()
    {
        $this->loadModel('Medewerker');

        $medewerkers = $this->Medewerker->uit_dienst();

        $this->set('medewerkers', $medewerkers);
    }

    public function edit_models()
    {
    }

    public function phpinfo()
    {
        $this->autoRender = false;
        phpinfo();
    }
}

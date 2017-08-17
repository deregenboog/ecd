<?php

class UnregisterAllShell extends Shell
{
    public $uses = ['Registratie'];

    public function main()
    {
        $current_hour = (int) date('H');

        if ($current_hour == 14) {
            // nachtopvang
            $this->log('AUTOMATIC UNREGISTER NIGHT', 'auto_checkout');
            $this->Registratie->automaticCheckOut(['Locatie.nachtopvang' => 1]);
        } else {
            $this->log('AUTOMATIC UNREGISTER DAY', 'auto_checkout');
            $this->Registratie->automaticCheckOut(['Locatie.nachtopvang' => 0]);
        }

        $this->log("COMPLETED\n", 'auto_checkout');
    }
}

<?php

/* IzDeelnemersIzProject Test cases generated on: 2014-08-11 16:08:18 : 1407767058*/
App::import('Model', 'IzDeelnemersIzProject');

class IzDeelnemersIzProjectTestCase extends CakeTestCase
{
    public $fixtures = ['app.iz_deelnemers_iz_project', 'app.iz_deelnemer', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intake_verslaving', 'app.awbz_intake', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.infobaliedoelgroep', 'app.inkomen', 'app.inkomens_intake', 'app.inkomens_awbz_intake', 'app.instantie', 'app.instanties_intake', 'app.instanties_awbz_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.awbz_intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.awbz_intakes_primaireproblematieksgebruikswijze', 'app.awbz_intake_verslaving', 'app.traject', 'app.back_on_track', 'app.bot_koppeling', 'app.groepsactiviteiten_intake', 'app.awbz_hoofdaannemer', 'app.hoofdaannemer', 'app.awbz_indicatie', 'app.notitie', 'app.opmerking', 'app.categorie', 'app.verslag', 'app.contactsoort', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.bot_verslag', 'app.hi5_intake', 'app.bedrijfitem', 'app.bedrijfsector', 'app.hi5_intakes_verslavingsgebruikswijzen', 'app.hi5_intakes_primaireproblematieksgebruikswijzen', 'app.hi5_intakes_verslavingen', 'app.hi5_intakes_inkomen', 'app.hi5_intakes_instanty', 'app.hi5_answer', 'app.hi5_question', 'app.hi5_answer_type', 'app.hi5_intakes_answer', 'app.hi5_evaluatie', 'app.hi5_evaluatie_question', 'app.hi5_evaluatie_paragraph', 'app.hi5_evaluaties_hi5_evaluatie_question', 'app.contactjournal', 'app.verslaginfo', 'app.attachment', 'app.groepsactiviteiten_groepen_klant', 'app.groepsactiviteiten_groep', 'app.groepsactiviteit', 'app.groepsactiviteiten_vrijwilliger', 'app.vrijwilliger', 'app.groepsactiviteiten_groepen_vrijwilliger', 'app.groepsactiviteiten_reden', 'app.groepsactiviteiten_verslag', 'app.groepsactiviteiten_klant', 'app.iz_project'];

    public function startTest()
    {
        $this->IzDeelnemersIzProject = &ClassRegistry::init('IzDeelnemersIzProject');
    }

    public function endTest()
    {
        unset($this->IzDeelnemersIzProject);
        ClassRegistry::flush();
    }
}

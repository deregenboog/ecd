<?php

/* GroepsactiviteitenKlant Test cases generated on: 2014-05-04 13:05:54 : 1399203714*/
App::import('Model', 'GroepsactiviteitenKlant');

class GroepsactiviteitenKlantTestCase extends CakeTestCase
{
    public $fixtures = ['app.groepsactiviteiten_klant', 'app.groepsactiviteit', 'app.groepsactiviteiten_groep', 'app.groepsactiviteiten_groepen_vrijwilliger', 'app.vrijwilliger', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.klant', 'app.medewerker', 'app.ldap_user', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intake_verslaving', 'app.awbz_intake', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.infobaliedoelgroep', 'app.inkomen', 'app.inkomens_intake', 'app.inkomens_awbz_intake', 'app.instantie', 'app.instanties_intake', 'app.instanties_awbz_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.awbz_intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.awbz_intakes_primaireproblematieksgebruikswijze', 'app.awbz_intake_verslaving', 'app.traject', 'app.back_on_track', 'app.bot_koppeling', 'app.awbz_hoofdaannemer', 'app.hoofdaannemer', 'app.awbz_indicatie', 'app.notitie', 'app.opmerking', 'app.categorie', 'app.verslag', 'app.contactsoort', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.bot_verslag', 'app.hi5_intake', 'app.bedrijfitem', 'app.bedrijfsector', 'app.hi5_intakes_verslavingsgebruikswijzen', 'app.hi5_intakes_primaireproblematieksgebruikswijzen', 'app.hi5_intakes_verslavingen', 'app.hi5_intakes_inkomen', 'app.hi5_intakes_instanty', 'app.hi5_answer', 'app.hi5_question', 'app.hi5_answer_type', 'app.hi5_intakes_answer', 'app.hi5_evaluatie', 'app.hi5_evaluatie_question', 'app.hi5_evaluatie_paragraph', 'app.hi5_evaluaties_hi5_evaluatie_question', 'app.contactjournal', 'app.verslaginfo', 'app.attachment', 'app.groepsactiviteiten_vrijwilliger', 'app.groepsactiviteiten_reden', 'app.groepsactiviteiten_vrijwilligers'];

    public function startTest()
    {
        $this->GroepsactiviteitenKlant = &ClassRegistry::init('GroepsactiviteitenKlant');
    }

    public function endTest()
    {
        unset($this->GroepsactiviteitenKlant);
        ClassRegistry::flush();
    }
}

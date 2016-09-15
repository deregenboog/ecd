<?php
/* BotKoppeling Test cases generated on: 2014-03-13 17:03:41 : 1394728001*/
App::import('Model', 'BotKoppeling');

class BotKoppelingTestCase extends CakeTestCase {
	var $fixtures = array('app.bot_koppeling', 'app.medewerker', 'app.ldap_user', 'app.back_on_track', 'app.klant', 'app.geslacht', 'app.land', 'app.nationaliteit', 'app.intake', 'app.verblijfstatus', 'app.legitimatie', 'app.verslaving', 'app.intake_verslaving', 'app.awbz_intake', 'app.verslavingsfrequentie', 'app.verslavingsperiode', 'app.woonsituatie', 'app.locatie', 'app.registratie', 'app.schorsing', 'app.reden', 'app.schorsingen_reden', 'app.infobaliedoelgroep', 'app.inkomen', 'app.inkomens_intake', 'app.inkomens_awbz_intake', 'app.instantie', 'app.instanties_intake', 'app.instanties_awbz_intake', 'app.verslavingsgebruikswijze', 'app.intakes_verslavingsgebruikswijze', 'app.awbz_intakes_verslavingsgebruikswijze', 'app.primaireproblematieksgebruikswijze', 'app.intakes_primaireproblematieksgebruikswijze', 'app.awbz_intakes_primaireproblematieksgebruikswijze', 'app.awbz_intake_verslaving', 'app.traject', 'app.awbz_hoofdaannemer', 'app.hoofdaannemer', 'app.awbz_indicatie', 'app.notitie', 'app.opmerking', 'app.categorie', 'app.verslag', 'app.contactsoort', 'app.inventarisaties_verslagen', 'app.inventarisatie', 'app.doorverwijzer', 'app.bot_verslag', 'app.hi5_intake', 'app.bedrijfitem', 'app.bedrijfsector', 'app.hi5_intakes_verslavingsgebruikswijzen', 'app.hi5_intakes_primaireproblematieksgebruikswijzen', 'app.hi5_intakes_verslavingen', 'app.hi5_intakes_inkomen', 'app.hi5_intakes_instanty', 'app.hi5_answer', 'app.hi5_question', 'app.hi5_answer_type', 'app.hi5_intakes_answer', 'app.hi5_evaluatie', 'app.hi5_evaluatie_question', 'app.hi5_evaluatie_paragraph', 'app.hi5_evaluaties_hi5_evaluatie_question', 'app.contactjournal', 'app.verslaginfo', 'app.attachment');

	function startTest() {
		$this->BotKoppeling =& ClassRegistry::init('BotKoppeling');
	}

	function endTest() {
		unset($this->BotKoppeling);
		ClassRegistry::flush();
	}

}
?>
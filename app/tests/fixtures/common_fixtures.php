<?php


class CommonFixture {

	/* Pointer to the TestCase object who constructs this class */
	var $testCaseObj = null;
	
	/* Array which defines which fixtures in the commonFixtures array
	 * are going to be included for the TestCase.
	 *  
	 */	
	var $fixturesTypeIncluded = array();
	
	/* Group of fixtures which can be included in the TestCase.
	 * To include these groups is necessary to pass as a second  
	 * parameter in this class __construct() function, a keys group list:
	 * basic,hotel,negotiation,organization or common
	 */
	
	var $commonFixtures = array (
		// Fixtures which should be included in almost all tests.
		'empty' => array(
            ),
        'full_klant' => array(
             'app.awbz_indicatie', 'app.awbz_intake',
             'app.inkomens_awbz_intake', 'app.instanties_awbz_intake',
             'app.awbz_intakes_verslavingsgebruikswijze',
             'app.awbz_intakes_primaireproblematieksgebruikswijze',
             'app.awbz_intake_verslaving', 'app.inventarisaties_verslag',
             'app.traject', 'app.hi5_intake', 'app.bedrijfitem',
             'app.bedrijfsector', 'app.hi5_answer',
             'app.hi5_question',  'app.hi5_answer_type',
             'app.hi5_intakes_verslavingsgebruikswijzen',
             'app.hi5_intakes_primaireproblematieksgebruikswijzen',
             'app.hi5_intakes_verslavingen', 'app.hi5_intakes_inkomen',
             'app.hi5_intakes_instanty', 'app.hi5_intakes_answer',
             'app.hi5_evaluatie', 'app.hi5_evaluatie_question',
             'app.hi5_evaluatie_paragraph',
             'app.hi5_evaluaties_hi5_evaluatie_question',
             'app.contactjournal', 'app.klant', 'app.geslacht', 'app.land',
              'app.medewerker', 'app.intake',
             'app.verblijfstatus', 'app.legitimatie', 'app.verslaving',
             'app.verslavingsfrequentie', 'app.verslavingsperiode',
             'app.woonsituatie', 'app.locatie', 'app.registratie',
             'app.schorsing', 'app.reden', 'app.reden', 'app.schorsingen_reden',
             'app.inkomen', 'app.inkomens_intake', 'app.intake_verslaving',
             'app.instantie', 'app.instanties_intake', 'app.verslavingsgebruikswijze',
             'app.intakes_verslavingsgebruikswijze',
             'app.intakes_primaireproblematieksgebruikswijze', 'app.verslag',
             'app.inventarisatie', 'app.doorverwijzer', 'app.notitie',
             'app.awbz_hoofdaannemer', 'app.hoofdaannemer', 'app.opmerking',
             'app.categorie', 'app.verslaginfo'
       ),



	);
	
	function __construct(&$testCaseObj, $fixturesType = array()){
        // Auto load all empty fixtures

        $path = APP.'/tests/fixtures/empty_*.php';

        $empty_files = glob($path);
        foreach($empty_files as $empty_f) {
            $name = basename($empty_f);
            $name = 'app.'.str_replace('_fixture.php', '', $name);
            $this->commonFixtures['empty'][]  = $name;
        }
	
		$this->testCaseObj =& $testCaseObj;
		$this->fixturesTypeIncluded = $fixturesType;
		//$this->diff_fixtures();
		$this->setCommonFixtures();

    }
	
	/* This function debugs the difference between the fixtures loaded for a testcase (defined in the 
	 * $fixtures class variable in the related testcase) and the fixtures in class array $commonFixtures.
	 * Only checks the difference for the $commonFixtures keys specified in $fixturesType 
	 * class __construct() function. 
	 * 
	 *  Note: Is not used , but is handy to automate difference checks from testcase fixtures and $commonFixtures
	 *  fixture groups.
	 */
	
	function diff_fixtures(){
		$fix = array();
		$f = false;
		foreach($this->testCaseObj->fixtures as $test_fixture){
			$f = false;
			foreach($this->commonFixtures as $fix_key => $fixtures){
				if(in_array($test_fixture,$fixtures)){
					$f = true;
				}
			}
			if(!$f){
				$fix[] = $test_fixture;
			}
		}
		debug(implode('\',\'',$fix));
	}
	
    function replaceFixtures(){
        $replacedFixtures = $this->testCaseObj->replaceFixtures;
        $fixtures =& $this->testCaseObj->fixtures;
        foreach($replacedFixtures as $replaced_fixture => $new_fixture){
            $key = array_search($replaced_fixture, $fixtures);
            if ($key !== false) {
                unset ($fixtures[$key]);
                $fixtures[] = $new_fixture;
            }
        }
    }
	
	function setCommonFixtures(){
		$this->mergeFixtures();
		if(!empty($this->testCaseObj->replaceFixtures)){
			$this->replaceFixtures($this->testCaseObj->replaceFixtures);
		}
	}
	
	function mergeFixtures(){
		$testCaseFixtures = array();
		if(!empty($this->fixturesTypeIncluded)){
			$includedFixtures = $this->fixturesTypeIncluded;
		}else{
			$includedFixtures = array();
		}

        // The fixtures explicited in the test have priority:
		foreach($this->testCaseObj->fixtures as $fixtureKey => &$fixture){
			if(!in_array($fixture, $testCaseFixtures)){
				$testCaseFixtures[] = $fixture;
			}
		}
		foreach($includedFixtures as &$fixtureType){
			$fixtures = $this->getCommonFixtures($fixtureType);

            if ($fixtureType == 'empty') {
                foreach($fixtures as &$fixture){
                    // Empty fixtures are only added if the real ones
                    // are not used already.
                    $normal_fixture = str_replace('app.empty_','app.',$fixture);
                    if(!in_array($normal_fixture, $testCaseFixtures) &&
                        !in_array($fixture, $testCaseFixtures)){
                        $testCaseFixtures[] = $fixture;
                    }
                }
            } else {
                foreach($fixtures as &$fixture){
                    $empty_fixture = str_replace('app.','app.empty_',$fixture);
                    if(!in_array($fixture, $testCaseFixtures)){
                        $testCaseFixtures[] = $fixture;
                        // A fixture automatically replaces the corresponsing
                        // empty_ one.
                        $key = array_search($empty_fixture, $testCaseFixtures);
                        if ($key !== false) {
                            unset ($testCaseFixtures[$key]);
                        }
                    }
                }
            }
		}
		$this->testCaseObj->fixtures = $testCaseFixtures;
	}
	
	function getCommonFixtures($fixtureType){
		$fixtures = array();
		if(isset($this->commonFixtures[$fixtureType])){
			$fixtures = $this->commonFixtures[$fixtureType];
		}
		return $fixtures;
	}


}


?>

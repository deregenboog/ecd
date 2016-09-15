<?php

class PopulatorController extends AppController
{
	public $name = 'Populator';
	public $uses = array('Klant', 'Intake');

	public function populate($number = 30)
	{
		debug("get rid of intake's validation if you want to use this! (models/intake.php)");

		for ($i = 0; $i < $number; $i++) {
			$this->Klant->create();
			$data = array(
				'voornaam' => $this->genRandom('words', 1),
				'achternaam' => $this->genRandom('words', 1),
				'roepnaam' => $this->genRandom('words', 1),
				'geboortedatum' => $this->genRandom('date'),
				'geslacht_id' => rand(1, 5),
				'laatste_TBC_controle' => $this->genRandom('date'),
			);
			//debug($data['name'] . ' ' . $data['created']);
			for ($j=0; $j < rand(0, 10); $j++) {
				$data['Intake'][$j] = array(
					'medewerker_id' => 1,
					'datum_intake' => $this->genRandom('date'),
					'verblijfstatus_id' => rand(1, 4),
					'postadres' => $this->genRandom('words', 2),
					'woonplaats' => $this->genRandom('words', 2),
					'legitimatie_id' => rand(0, 422323),
					'legitimatie_nummer' => rand(0, 422323),
					'legitimatie_geldig_tot' => $this->genRandom('date'),
					'verslavingsfrequentie_id' => rand(1, 7),
					'verslavingsperiode_id' => rand(1, 7),
					'woonsituatie_id' => rand(1, 8),
					'verwachting_dienstaanbod' => $this->genRandom('paragraph', 20),
					'toekomstplannen' => $this->genRandom('paragraph', 20),
					'opmerking_andere_instanties' => $this->genRandom('paragraph', 20),
					'medische_achtergrond' => $this->genRandom('paragraph', 20),
					'postcode' => rand(1000, 9999).$this->genRandom('letters', 2),
					'locatie1_id' => rand(1, 18),
					'locatie2_id' => rand(1, 18),
					'indruk' => $this->genRandom('paragraph', 20),
					'doelgroep' => 1,
				);
			}
			debug($data);
			var_dump($this->Klant->saveAll($data));
		}//for
	}//populate

	public function genRandom($type = 'letters', $length = 10)
	{
		switch ($type) {
			case 'letters':
				$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ';
				break;
			case 'lettersAndNumbers':
				$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWYZ1234567890';
				break;
			case 'paragraph':
				$characters = 'abcdef ghijklmno pqrstu vwxyz ABCDE FGHIJKL MNOPQRST UVWYZ 12345 678 90';
				break;
			case 'date':
				return rand(1920, 2000).'-'.rand(1, 12).'-'.rand(1, 29);
			case 'words':
				$words = array('foo', 'annihilation', 'bar', 'wicked', 'grabbing', 'teddy', 'bears', 'red', 'completely', 'Jeżozwierz', 'annoying', 'forever', 'better', 'than', 'mother', 'blast', 'smelly', 'rope', 'maintained', 'ugly');
				$string = '';
				for ($p = 0; $p < $length; $p++) {
					$string .= $words[rand(0, count($words)-1)];
					($p == $length) ? $string .= '' : $string .= ' ';
				}
				return $string;
		}
		$string = '';

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters)-1)];
		}

		return $string;
	}
}//class

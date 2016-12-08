<?php

/* @var $medewerker MedewerkerHelper */
/* @var $projecten ProjectHelper */

echo implode($delimiter, [
	'Nummer',
	'Roepnaam',
	'Voornaam',
	'Tussenvoegsel',
	'Achternaam',
	'Medewerker dossier',
	'Medewerker intake',
	'Medewerker(s) hulpaanbod',
	'Project(en)',
	'Stadsdeel',
]) . PHP_EOL;

foreach ($personen as $persoon) {
	echo implode($delimiter, [
		$persoon['IzVrijwilliger']['id'],
		$persoon['Vrijwilliger']['roepnaam'],
		$persoon['Vrijwilliger']['voornaam'],
		$persoon['Vrijwilliger']['tussenvoegsel'],
		$persoon['Vrijwilliger']['achternaam'],
		$medewerker->csList($persoon['Vrijwilliger'], $medewerkers),
		$medewerker->csList($persoon['IzIntake'], $medewerkers),
		$medewerker->csList($persoon['IzHulpaanbod'], $medewerkers),
		$project->csList($persoon['IzHulpaanbod'], $projecten),
		$persoon['Vrijwilliger']['werkgebied'],
	]) . PHP_EOL;
}

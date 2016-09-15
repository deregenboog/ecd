<?php

foreach ($verslagen as &$verslag) {
	
	echo '<div style="padding: 15px;"><h3>Verslag van ';
	echo $this->Date->show($verslag['Verslag']['datum']);
	echo '</h3>';
	
	foreach ($verslag['InventarisatiesVerslagen'] as $record) {
		
		foreach ($record as $invId => &$inv) {
			
			echo $inv['Inventarisatie']['titel'];
			
			if ($invId < count($record) - 1) {
				echo ' >>&nbsp;';
			}
		}
		
		echo '<br/>';
		
	}
	
	echo '</p>';

	echo '<p>Locatie: ';
	
	echo !empty($verslag['Locatie']['naam']) ? $verslag['Locatie']['naam'] : __('Overige');
	
	echo '</p>';
	
	if (!empty($verslag['Medewerker']['name'])) {
		
		echo '<p>Medewerker: ';
		echo $verslag['Medewerker']['name'].'</p>';
		
	}
	
	foreach (array('advocaat', 'contact', 'opmerking') as $field) {
		
		if (!empty($verslag['Verslag'][$field])) {
			
			if ($field == 'aanpassing_verslag') {
				
				if ($verslag['Contactsoort']['id'] == 3) {
					echo '<h4>Duur verslag (minuten)</h4>';
				}
				
			} else {
				echo '<h4>'.ucfirst($field).'</h4>';
			}
			
			if ($field == 'opmerking') {
				echo '<p>'.nl2br($verslag['Verslag'][$field]).'</p>';
			} else {
				echo '<p>'.$verslag['Verslag'][$field].'</p>';
			}
		}
	}

	echo '<p> Contactsoort: '.($verslag['Contactsoort']['text'] ? $verslag['Contactsoort']['text'] : __('Overige', true)).'</p>';
	
	if (!empty($verslag['Verslag']['aanpassing_verslag']) && $verslag['Contactsoort']['id'] == 3) {
		echo '<p>Duur verslag (minuten): '.$verslag['Verslag']['aanpassing_verslag'].'</p>';
	}

	$wrench = $html->image('wrench.png');
	$url = array(
		'action' => 'edit',
		$verslag['Verslag']['id'],
	);
	
	$opts = array('escape' => false, 'title' => __('edit', true));
	echo $html->link($wrench, $url, $opts);

	echo '</div>';
}

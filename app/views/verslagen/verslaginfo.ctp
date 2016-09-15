<?php
$f = $this->Form;
?>

<div class="actions">

	<?= $this->element('klantbasic', array('data' => $klant));?>
	
	<div class="links">
	
<?php

	echo $this->Html->link('Nieuw verslag invoeren', array(
		'controller' => 'maatschappelijk_werk',
		'action' => 'add',
		$klant['Klant']['id'],
	));
	
	echo '<br/>';
	
	echo $this->Html->link('Rapportage', array(
		'controller' => 'maatschappelijk_werk',
		'action' => 'rapportage',
	));
	
?>
	</div>
</div>

<div class="verslagen view">
	<fieldset>
		<legend>Gegevens maatschappelijk werk</legend>
<?php
	echo $f->create('Verslaginfo',
		array('url' => array('controller'=>'maatschappelijk_werk', 'action' => 'verslaginfo', $klant['Klant']['id']) )
	);
	
	echo $f->hidden('klant_id', array('value' => $klant['Klant']['id']));
	
	echo $f->hidden('id');

	echo '<fieldset><legend>'.__('Casemanager RBG', true).'</legend>';
	
	echo $f->input('casemanager_id', array(
		'label' => __('Naam', true),
		'options' => $medewerkers,
		'empty' => true,
	));
	
	echo $f->input('casemanager_email', array(
		'label' => __('Emailadres', true),
	));
	
	echo $f->input('casemanager_telefoonnummer', array(
		'label' => __('Telefoonnummer', true),
	));
	
	echo '</fieldset>';

	echo '<fieldset><legend>'.__('Trajectbegeleider', true).'</legend>';
	
	echo $f->input('trajectbegeleider_id', array(
		'label' => __('Naam', true),
		'options' => $medewerkers,
		'empty' => true,
	));
	
	echo $f->input('trajectbegeleider_email', array(
		'label' => __('Emailadres', true),
	));
	
	echo $f->input('trajectbegeleider_telefoonnummer', array(
		'label' => __('Telefoonnummer', true),
	));
	
	echo '</fieldset>';

	echo '<fieldset><legend>'.__('Trajecthouder extern', true).'</legend>';
	
	echo $f->input('trajecthouder_extern_organisatie', array(
		'label' => __('Organisatie', true),
	));
	
	echo $f->input('trajecthouder_extern_naam', array(
		'label' => __('Naam', true),
	));
	
	echo $f->input('trajecthouder_extern_email', array(
		'label' => __('Emailadres', true),
	));
	
	echo $f->input('trajecthouder_extern_telefoonnummer', array(
		'label' => __('Telefoonnummer', true),
	));
	
	echo '</fieldset>';
	
	echo $f->input('overige_contactpersonen_extern', array(
		'label' => __('Overige contactpersonen extern', true),
		'type' => 'textarea',
	));

	echo '<fieldset><legend>'.__('Uitkerende instantie', true).'</legend>';
	
	echo $f->input('instantie', array(
		'label' => __('Instantie', true),
		'options' => array(__('DWI', true), __('UWV', true), __('Anders', true)),
		'empty' => true,
	));
	
	echo $f->input('registratienummer', array(
		'label' => __('Registratienummer', true),
	));
	
	echo '</fieldset>';
	
	echo '<fieldset><legend>'.__('Budgettering', true).'</legend>';
	
	echo $f->input('budgettering', array(
		'label' => __('Budgettering', true),
	));
	
	echo $f->input('contactpersoon', array(
		'label' => __('Contactpersoon', true),
	));
	
	echo '</fieldset>';
	
	echo '<fieldset><legend>'.__('Klantmanager', true).'</legend>';
	
	echo $f->input('klantmanager_naam', array(
		'label' => __('Naam', true),
	));
	
	echo $f->input('klantmanager_email', array(
		'label' => __('Emailadres', true),
	));
	
	echo $f->input('klantmanager_telefoonnummer', array(
		'label' => __('Telefoonnummer', true),
	));
	
	echo '</fieldset>';
	
	echo $f->input('sociaal_netwerk', array(
		'label' => __('Sociaal netwerk', true),
		'type' => 'textarea',
	));

	echo $f->input('bankrekeningnummer', array(
		'label' => __('Bankrekeningnummer', true),
	));
	
	echo $f->input('polisnummer_ziektekostenverzekering', array(
		'label' => __('Polisnummer ziektekostenverzekering', true),
	));

	echo '<fieldset><legend>'.__('Woningnet', true).'</legend>';
	
	echo $f->input('inschrijfnummer', array(
		'label' => __('Inschrijfnummer', true),
	));
	
	echo $f->input('wachtwoord', array(
		'label' => __('Wachtwoord', true),
	));
	
	echo '</fieldset>';
	
	echo $f->input('telefoonnummer', array(
		'label' => __('Telefoonnummer', true),
	));

	echo $f->input('contact', array(
		'label' => __('Contact', true),
	));

	echo $f->input('adres', array(
		'label' => __('Adres', true),
		'type' => 'textarea',
	));
	
	echo $f->input('overigen', array(
		'label' => __('Overigen', true),
		'type' => 'textarea',
	));
?>
	</fieldset>
 <?php 
	echo $f->end(__('Submit', true));
 ?>
</div>

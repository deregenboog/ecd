<script type="text/javascript">
	var amocCountries = <?= json_encode($amocCountries) ?>;
</script>

<div class="klanten">

<?php echo $this->Form->create('Klant', array('url' => array('step' => 4)));?>
	<fieldset class="twoDivs">
		<legend><?php __('Persoonsgegevens nieuwe klant'); ?></legend>
		<div class="leftDiv">
	<?php
		echo $this->Form->hidden('referer');
		echo $this->Form->input('voornaam');
		echo $this->Form->input('tussenvoegsel');
		echo $this->Form->input('achternaam');
		echo $this->Form->input('roepnaam');
		echo $this->Form->input('geslacht_id');
		?>
		</div><div class="rightDiv">
		<?php
		echo $date->input('Klant.geboortedatum', null, array(
			'label' => 'Geboortedatum',
			'rangeLow' => (date('Y') - 100).date('-m-d'),
			'rangeHigh' => date('Y-m-d'),
		));
		echo $this->Form->input('land_id', array('default'=> $default_land_id, 'label' => 'Geboorteland'));
		echo $this->Form->input('doorverwijzen_naar_amoc', array(
			'label' => __('Ik wil deze persoon wegens taalproblemen doorverwijzen naar AMOC', true),
		));
		echo '<div class="amocLandWarning">';
		echo __('Personen uit dit land worden doorgestuurd naar AMOC.', true).' '.__('Op het volgende scherm is een verwijsbrief uit te printen.', true);
		echo '</div>';
		echo $this->Form->input('nationaliteit_id', array('default'=> $default_nationaliteit_id));
		echo $this->Form->input('BSN');
		echo $date->input('Klant.laatste_TBC_controle', 'empty', array(
			'label' => 'Laatste TBC controle',
			'rangeLow' => (date('Y') - 20).date('-m-d'),
			'rangeHigh' => date('Y-m-d'),
		));
		echo $this->Form->input('medewerker_id', array('empty' => '', 'value' => $logged_in_user));

	?>
	</div>
	</fieldset>
<?php echo $this->Form->end(__('Opslaan', true));?>
</div>

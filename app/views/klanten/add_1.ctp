<script type="text/javascript">
	var amocCountries = <?= json_encode($amocCountries) ?>;
</script>

<div class="klanten">

<?php echo $this->Form->create('Klant', array('url'=>array('step' => 1, 'generic'=>$generic)));?>
	<fieldset class="twoDivs">
		<legend><?php __('Persoonsgegevens nieuwe klant, stap 1'); ?></legend>
		<div class="leftDiv">
	<?php
			echo $this->Form->hidden('referer');
			echo $this->Form->input('voornaam');
			echo $this->Form->input('tussenvoegsel');
			echo $this->Form->input('achternaam');
			echo $date->input('Klant.geboortedatum', 'empty', array(
				'label' => 'Geboortedatum',
				'rangeLow' => (date('Y') - 100).date('-m-d'),
				'rangeHigh' => date('Y-m-d'),
		));
	?>
	</div>
	</fieldset>
	
<?php echo $this->Form->end(__('Volgende', true));?>

</div>


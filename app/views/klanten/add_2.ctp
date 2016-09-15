<div class="klanten">

<h2>Dubbele klanten</h2>
<p>

<?php
__('Er zijn mogelijk duplicaten in de database. Selecteer een bestaande klant om deze te bewerken, of selecteer "Nieuwe klant invoeren".');
?>


</p>
<ul>

<?php
foreach ($duplicates as $d) {
	$k = $d['Klant'];
	$id = $k['id'];
	$name = $k['name1st_part'].' '.$k['name2nd_part'];
	$date = $k['geboortedatum'];
	$land = $d['Geboorteland']['land'];
	$link = $name.', '.$land.', '.$date;

	echo '<li>'.$this->Html->link($link, array('action' => 'view', $id)).
	'</li>';
}

$k = $this->data['Klant'];
$d = $k['geboortedatum'];

?>

</ul>
</div>
<div class="klanten">

<?php 

	echo $this->Form->create('Klant', array('url'=>array('step' => 3, 'generic'=>$generic)));?>
		<fieldset class="twoDivs">
				<legend><?php __('Persoonsgegevens nieuwe klant'); ?></legend>
				<div class="leftDiv">
		<?php
			echo $this->Form->hidden('referer');
			echo $this->Form->input('voornaam');
			echo $this->Form->input('tussenvoegsel');
			echo $this->Form->input('achternaam');
		echo $this->date->input("{$persoon_model}.geboortedatum", null, array(
										'label' => 'Geboortedatum',
										'rangeLow' => (date('Y') - 100).date('-m-d'),
										'rangeHigh' => date('Y-m-d'),
								));
		?>
		</div>
		</fieldset>
		
<?php echo $this->Form->end(__('Volgende', true));?>

</div>

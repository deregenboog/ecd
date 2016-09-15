<?php 
$model = $persoon_model;
$controller = strtolower(Inflector::pluralize($persoon_model));
if (empty($step)) {
	$step=null;
}
$werkgebied = "";
$postcodegebied = "";
if (!empty($this->data[$persoon_model]['werkgebied'])) {
	$werkgebied = $this->data[$persoon_model]['werkgebied'];
}
if (!empty($this->data[$persoon_model]['postcodegebied'])) {
	$postcodegebied = $this->data[$persoon_model]['postcodegebied'];
}
?>

<?php
	if (! empty($this->data[$model]['id'])) {
		$referer = array('controller' => $controller, 'action' => 'view', $this->data[$model]['id']);
	}
	if (isset($this->data[$model]['referer'])) {
		$referer = $this->data[$model]['referer'];
	}
	if (!empty($referer)) {
		echo $this->Html->link('Terug', $referer);
	}

?>
<div class="klanten">
<?php

	echo $this->Form->create($model, array('url' => array('step' => 4, 'generic' => true)));
?>
	<fieldset class="twoDivs">
		<legend><?php __('Persoonsgegevens bewerken'); ?></legend>
		<div class="leftDiv">
	<?php
		echo $this->Form->hidden('referer');
		echo $this->Form->hidden('generic', array('value' => 1));
		echo $this->Form->input('id');
		echo $this->Form->input('voornaam');
		echo $this->Form->input('tussenvoegsel');
		echo $this->Form->input('achternaam');
		echo $this->Form->input('roepnaam');
		echo $this->Form->input('geslacht_id');
		?>
		</div><div class="rightDiv">
		<?php

		echo $date->input("{$model}.geboortedatum", null, array(
					'label' => 'Geboortedatum',
					'rangeLow' => (date('Y') - 100).date('-m-d'),
					'rangeHigh' => date('Y-m-d'),
				));
		echo $this->Form->input('land_id', array('label' => 'Geboorteland'));

		echo $this->Form->input('nationaliteit_id');
		echo $this->Form->input('BSN');
		echo $this->Form->input('medewerker_id', array('empty' => ''));

	?>
	</div>
	</fieldset>
	
	<fieldset class="twoDivs">
		<legend><?php __('Contact gegevens bewerken'); ?></legend>
		<div class="leftDiv">
	<?php
		echo $this->Form->input('adres');
		echo $this->Form->input('postcode', array('class' => 'postcode'));
		echo $this->Form->input('werkgebied', array('type' => 'hidden', 'class' => 'werkgebied'));
		echo '<label>&nbsp;Werkgebied:</label><div id="werkgebied_display">'.$werkgebied."</div>";
		echo $this->Form->input('postcodegebied', array('type' => 'hidden', 'class' => 'postcodegebied'));
		echo '<label>&nbsp;Postcodegebied:</label><div id="postcodegebied_display">'.$postcodegebied."</div>";

		echo $this->Form->input('plaats');
		echo $this->Form->input('email');

		?>
		</div><div class="rightDiv">
		<?php
		echo $this->Form->input('mobiel');
		echo $this->Form->input('telefoon');
		echo $this->Form->input('opmerking');
		echo $this->Form->input('geen_post');
		echo $this->Form->input('geen_email');

	?>
	</div>
	</fieldset>
	
<?= $this->Form->submit(__('Opslaan', true), array('div' => 'submit'));?>
	 <div class="submit">
	<?php
		if (!empty($this->data[$model]['id']) && $user_is_administrator) {
			echo $this->Html->link(__('Delete', true),
					array(
						'action' => 'disable',
						$this->data[$model]['id'],
						),
					array(
						'class' => 'delete-button',
						),
					__('Are you sure you want to delete the client?', true)
					);
		}
	?>
	 </div>
	<?= $this->Form->end(); ?>
</div>

<?php
$stadsdeelUrl = json_encode($this->Html->url(
		array('controller' => 'vrijwilligers', 'action' => 'get_stadsdeel')
	));

$this->Js->buffer("
	$(document).ready(function() {
		$('.postcode').change(function() {

			var val = $(this).val();

			if (val.length >= 4) {
				$.post(
					" .$stadsdeelUrl.",
					{postcode: val},
					function(data) {
						if (data.stadsdeel) {
							$('#".$persoon_model."Werkgebied').val(data.stadsdeel);
							$('#werkgebied_display').text(data.stadsdeel);
						}
						if (data.postcodegebied) {
							$('#".$persoon_model."Postcodegebied').val(data.postcodegebied);
							$('#postcodegebied_display').text(data.postcodegebied);
						}
					},
					'json'
				);
			}
		});
	});
");
?>

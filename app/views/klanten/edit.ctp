<script type="text/javascript">
	var amocCountries = <?= json_encode($amocCountries) ?>;
</script>

<?php echo $this->Html->link('Terug naar klantoverzicht', array('controller' => 'klanten', 'action' => 'view', $this->data['Klant']['id'])); ?>

<div class="klanten">

<?php echo $this->Form->create('Klant');?>

	<fieldset class="twoDivs">
		<legend><?php __('Klant persoonsgegevens bewerken'); ?></legend>
		<div class="leftDiv">
		
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('referer');
		echo $this->Form->input('voornaam');
		echo $this->Form->input('tussenvoegsel');
		echo $this->Form->input('achternaam');
		echo $this->Form->input('roepnaam');
		echo $this->Form->input('geslacht_id');
		echo $this->Form->input('overleden', array(
			'type' => 'checkbox',
			'label' => 'Overleden',
		));
		?>
		</div><div class="rightDiv">
		
		<?php

		echo $date->input('Klant.geboortedatum', null, array(
					'label' => 'Geboortedatum',
					'rangeLow' => (date('Y') - 100).date('-m-d'),
					'rangeHigh' => date('Y-m-d'),
				));
		echo $this->Form->input('land_id', array('label' => 'Geboorteland'));
		echo $this->Form->input('doorverwijzen_naar_amoc', array(
			'label' => __('Ik wil deze persoon wegens taalproblemen doorverwijzen naar AMOC', true),
		));
		echo '<div class="amocLandWarning">';
		echo __('Personen uit dit land worden doorgestuurd naar AMOC.', true);
		echo $this->Html->link(__('Verwijsbrief printen', true), array(
			'action' => 'printLetter',
			$this->data['Klant']['id'],
		), array(
			'target' => '_blank',
		));
		echo '</div>';

		echo $this->Form->input('nationaliteit_id');
		echo $this->Form->input('BSN');
		echo $date->input('Klant.laatste_TBC_controle', null, array(
			'label' => 'Laatste TBC controle',
			'rangeLow' => (date('Y') - 20).date('-m-d'),
			'rangeHigh' => date('Y-m-d'),
		));
		echo $this->Form->input('medewerker_id', array('empty' => ''));

	?>
	</div>
	</fieldset>
<?= $this->Form->submit(__('Opslaan', true), array('div' => 'submit'));?>
	 <div class="submit">
	<?php
		if ($user_is_administrator) {
			echo $this->Html->link(__('Delete', true),
					array(
						'action' => 'disable',
						$this->data['Klant']['id'],
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

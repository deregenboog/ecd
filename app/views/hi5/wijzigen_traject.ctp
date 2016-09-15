<div class="hi5 form">
	<?php 

	echo $this->Form->create('Traject',
			array('url' => '/hi5/wijzigen_traject/'.$klant['Klant']['id'] )
	);
	?>

	<fieldset>
		<legend><?php __('Gegevens traject - en werkbegeleiding'); ?></legend>
		<?php
			echo $form->hidden('id', array('value' => $klant['Traject']['id']));
			echo $form->hidden('klant_id', array('value' => $klant['Klant']['id']));
			echo $this->Form->input('trajectbegeleider_id', array(
				'label' => 'Trajectbegeleider',
			));
			echo $this->Form->input('werkbegeleider_id', array(
				'label' => 'Werkbegeleider',
			));

			echo $this->Form->input('klant_telefoonnummer', array('label' => 'Telefoonnummer client'));
		?>
		<fieldset>
			<legend><?php __('Klantmanager DWI')?></legend>
			<?php
				//debug($klant);
				echo $this->Form->input('administratienummer', array('label' => 'Administratienummer'));
				echo $this->Form->input('klantmanager', array('label' => 'Klantmanager'));
				echo $this->Form->input('manager_telefoonnummer', array('label' => 'Telefoonnummer'));
				echo $this->Form->input('manager_email', array('label' => 'e-Mail'));
			?>
		</fieldset>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>

<div class="actions">
	<?= $this->element('klantbasic', array('data' => $klant)); ?>
	<?= $this->element('diensten', array( 'diensten' => $diensten, ))?>
</div>

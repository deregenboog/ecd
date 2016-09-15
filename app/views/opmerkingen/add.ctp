<div class="form">
	<fieldset>
		<legend><?php __('Opmerking toevoegen')?></legend>
		<?php 
			echo $this->Form->create('Opmerking',
				array('url' => array('controller'=>'opmerkingen', 'action' => 'add', $klant['Klant']['id'])));
			echo $this->Form->hidden('klant_id', array('value' => $klant['Klant']['id']));
			echo $this->Form->input('categorie_id', array('empty' => ''));
			echo $this->Form->input('beschrijving', array('type' => 'textarea'));
			echo $this->Form->end(__('Submit', true));
		?>
	</fieldset>
</div>
<div class="actions">
	<?php echo $this->element('klantbasic', array('data' => $klant)); ?>
	<?php echo $this->element('diensten', array( 'diensten' => $diensten, )); ?>
</div>

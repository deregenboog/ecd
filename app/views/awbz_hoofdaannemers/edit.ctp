<div class="actions">
	<?= $this->element('klantbasic', array('data' => $klant)); ?>
	<p>
		<?=
			$this->Html->link('Annuleren',
				array(
					'controller' => 'awbz',
					'action' => 'view',
					$klant['Klant']['id'],
				)
			);
		?>
	</p>
</div>

<div class="awbzHoofdaannemers form">

<?php echo $this->Form->create('AwbzHoofdaannemer');?>
	<fieldset>
		<legend><?php __('Edit Awbz Hoofdaannemer'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('klant_id');
		echo $this->Date->datepicker('begindatum', array('required' => true));
		echo $this->Date->datepicker('einddatum');
		echo $this->Form->input('hoofdaannemer_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>

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

<div class="awbzIndicaties form">

<?php echo $this->Form->create('AwbzIndicatie');?>
	<fieldset>
		<legend><?php __('Edit Awbz Indicatie'); ?></legend>
	<?php
		$hours = array();

		for ($i = 0; $i <= 40; $i++) {
			$hours[$i] = $i.' uur';
		}

		echo $this->Form->input('id');
		echo $this->Form->hidden('klant_id');
		echo $this->Date->datepicker('begindatum', array('required' => true));
		echo $this->Date->datepicker('einddatum', array('required' => true));
		echo $this->Form->input('begeleiding_per_week', array(
			'options' => $hours,
			'label' => 'Aantal uur begeleiding per week',
		));
		echo $this->Form->input('activering_per_week', array(
			'options' => $hours,
			'label' => 'Aantal uur activering per week',
		));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>

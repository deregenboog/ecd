<?php echo $this->element('pfo_subnavigation'); ?>
<div class="pfoAardRelaties form">
<?php echo $this->Form->create('PfoAardRelatie');?>
	<fieldset>
		<legend><?php __('Voeg aard relatie toe'); ?></legend>
	<?php
		echo $this->Form->input('naam');
		echo $date->input('PfoAardRelatie.startdatum', null, array(
				'label' => 'Geldig van',
				'rangeLow' => (date('Y') - 20).date('-m-d'),
				'rangeHigh' => (date('Y') + 20).date('-m-d'),
		));
		echo $date->input('PfoAardRelatie.einddatum', null, array(
				'label' => 'Geldig tot',
				'rangeLow' => (date('Y') - 20).date('-m-d'),
				'rangeHigh' => (date('Y') + 20).date('-m-d'),
		));

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Aard Relaties lijst', true), array('action' => 'index'));?></li>
	</ul>
</div>

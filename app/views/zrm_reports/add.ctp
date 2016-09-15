<div class="zrmReports form">
<?php 
	echo $this->Form->create('ZrmReport');

	echo $this->element('zrm', array(
			'model' => 'Klant',
			'zrm_data' => $zrm_data,
	));

	echo $this->Form->end(__('Submit', true));

?>
</div>
<div class="actions">

</div>

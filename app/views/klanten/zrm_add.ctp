<div class="zrmReports form">
<?php 
	echo $this->Form->create('Klant', array('url' => array($id)));
	echo $this->Form->hidden('referer');

	echo $this->element('zrm', array(
			'model' => 'Klant',
			'zrm_data' => $zrm_data,
	));

	echo $this->Form->end(__('Submit', true));

?>
</div>
<div class="actions">

</div>

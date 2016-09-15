<?php

$error = array(
	'error'		 => __('An error occured while transferring the file.', true),
	'resource'	 => __('The file is invalid.', true),
	'access'	 => __('The file cannot be processed.', true),
	'location'	 => __('The file cannot be transferred from or to location.', true),
	'permission' => __('Executable files cannot be uploaded.', true),
	'size'		 => __('The file is too large.', true),
	'pixels'	 => __('The file is too large.', true),
	'extension'  => __('The file has the wrong extension.', true),
	'mimeType'	 => __('The file has the wrong MIME type.', true),
);
?>

<fieldset>
	<legend>Documentbeheer upload</legend>
<?php
		echo $form->create('IzDeelnemerDocument',
			array(
				'url' => array(
					'controller' => 'iz_deelnemers',
					'action' => 'upload',
					$id,
				),
				'enctype' => 'multipart/form-data',
			)
		);
		
		echo $this->Form->input('IzDeelnemerDocument.group', array(
			'type' => 'hidden',
		));

		echo $this->Form->input('IzDeelnemerDocument.title', array(
			'label' => __('Titel', true),
		));
		
		echo $date->input('IzDeelnemerDocument.created', date('Y-m-d'), array(
			'label' => 'Datum',
			'class' => 'date',
			'rangeLow' => '2009-12-31',
			'rangeHigh' => date('Y-m-d'), )
		);
		
		echo $this->Form->input('IzDeelnemerDocument.file', array(
			'type' => 'file',
		));
		
		echo $form->end(array('label' => 'Ga'));

?>
</fieldset>

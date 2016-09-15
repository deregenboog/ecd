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
		echo $form->create($persoon_model,
			array(
				'url' => array(
					'controller' => 'Groepsactiviteiten',
					'action' => 'upload',
					$persoon_model,
					$id,
				),
				'enctype' => 'multipart/form-data',
			)
		);

		echo $this->Form->input('id', array('value' => $id));
		
		echo $this->Form->input('GroepsactiviteitenDocument.group', array(
			'type' => 'hidden',
		));

		echo $this->Form->input('GroepsactiviteitenDocument.title', array(
			'label' => __('Titel', true),
		));
		
		echo $date->input('GroepsactiviteitenDocument.created', date('Y-m-d'), array(
			'label' => 'Datum',
			'class' => 'date',
			'rangeLow' => (date('Y') - 19).date('-m-d'),
			'rangeHigh' => date('Y-m-d'), )
		);
		
		echo $this->Form->input('GroepsactiviteitenDocument.file', array(
			'type' => 'file',
		));
		
		echo $form->end(array('label' => 'Ga'));

?>
</fieldset>

<?php

	if ($registratie['Registratie'][$fieldname] != 0) {
		$checked = true;
	} else {
		$checked = false;
	}

	$field_id = $fieldname.'_'.$registratie['Registratie']['id'];

	echo $this->Form->input($field_id, array(
		'label' => false,
		'type' => 'checkbox',
		'hiddenField' => false,
		'checked' => $checked,
		'name' => $field_id,
		'style' =>'cursor: pointer',
	));

	$this->Js->get('#'.$field_id)->event('change', $this->Js->request(
		array(
			'controller' => 'registraties',
			'action' => 'jsonSimpleCheckboxToggle',
			$fieldname,
			$registratie['Registratie']['id'],
		),
		array(
			'async' => false,
			'before' => '$("#loading").css("display","block")',
			'complete' => '$("#loading").css("display","none");',
			'success' => 'set_checkbox("'.$field_id.'",data);',
			'type' => 'json',
		)
	));

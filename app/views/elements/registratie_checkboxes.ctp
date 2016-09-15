<?php

$tt1 = mt();

$js_request_options = array(
	'dataExpression' => true,
	'evalScripts' => true,
	'method' => 'post',
	'async' => false,
	'before' => '$("#loading").css("display","block")',
	'complete' => '$("#loading").css("display","none")',
	'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)),	
);

if ($fieldname != 'douche' && $fieldname != 'mw' && $fieldname != 'gbrv') {

	$js_request_options['update'] = '#'.$fieldname.'__'.$registratie['Registratie']['id'];
	$formId = $fieldname.'_'.$registratie['Registratie']['id'];
	$f = '<form id="'.$formId.'" method="post" action="/regenboog/registraties" accept-charset="utf-8">';
	
	if ($registratie['Registratie'][$fieldname]) {
		$checked = 'checked="checked"';
	} else {
		$checked = '';
	}

	$in = '<input type="hidden" name="data[Registratie]['.
		$fieldname.']" id="Registratie'.ucfirst($fieldname).
		$registratie['Registratie']['id'].
		'_" value="0" /><input type="checkbox" name="data[Registratie]['
		.$fieldname.']" '.$checked.' class="compact" id="Registratie'
		.ucfirst($fieldname).$registratie['Registratie']['id'].
		'" value="1" />';

	echo $f.$in.'</form>';
	
	$this->Js->get('#'.$fieldname.'_'.$registratie['Registratie']['id'])->event('change',
		$this->Js->request('/registraties/ajaxUpdateRegistratie/'.$registratie['Registratie']['id'],
			array(
				'dataExpression' => true,
				'evalScripts' => true,
				'method' => 'post',
				'async' => false,
				'before' => '$("#loading").css("display","block")',
				'complete' => '$("#loading").css("display","none")',
				'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)), 
			)
		)
	);
}

App::Import('Sanitize');

if ($fieldname == 'douche') {

	$js_request_options['complete'] = 'applyLastSorting();$("#loading").css("display","none");';

	if ($registratie['Registratie']['douche'] == 0) {
		$showerAction = 'add';
	} else {
		$showerAction = 'del';
	}
	$js_request_options['update'] = '#registratielijst';
	$ajaxCheckboxAction = '/registraties/ajaxUpdateShowerList/'.$showerAction.'/'.$locatie_id.'/'.$registratie['Registratie']['id'];

	if ($registratie['Registratie']['douche'] > 0) {
		
		echo "<div class='ajax_text'>".$registratie['Registratie']['douche']."</div>";
			$this->Js->get('#'.$fieldname.'__'.$registratie['Registratie']['id'])->event('click',
			$this->Js->request($ajaxCheckboxAction,
				$js_request_options
			)
		);
			
			
	} else {
		
		$input_options = array('type'=>'checkbox', 'div' => false,
			'name' =>'data[Registratie]['.$fieldname.']',
			'label'=>false, 'class' => 'compact', 'id' => 'RegistratieDouche'.$registratie['Registratie']['id'], 
		);
		
		$f = '<form id="'.$fieldname.'_'.$registratie['Registratie']['id'].'" method="post" action="/regenboog/registraties" accept-charset="utf-8">';
		
		if ($registratie['Registratie']['douche'] == -1) {
			$input_options['checked'] = 'checked="checked"';
		} else {
			$input_options['checked'] = '';
		}
		
		$in = '<input type="hidden" name="data[Registratie]['.$fieldname.']" id="RegistratieDouche'.$registratie['Registratie']['id'].'_" value="0" />';
		$in .= '<input type="checkbox" name="data[Registratie]['.$fieldname.']" class="compact" id="RegistratieDouche'.$registratie['Registratie']['id'].'" '.$input_options['checked'].' value="1" />';
		
		echo $f.$in;
		echo '</form>';
		
		$this->Js->get('#'.$fieldname.'_'.$registratie['Registratie']['id'])->event('change',
				$this->Js->request($ajaxCheckboxAction,
				$js_request_options
			)
		);
	}
}

if ($fieldname == 'mw' || $fieldname == 'gbrv') {

	if ($registratie['Registratie'][$fieldname] == 0) {
		$action = 'add';
	} else {
		$action = 'del';
	}
	
	$js_request_options['complete'] .= ';applyLastSorting()'; //adding sorting script to the callback code
	$js_request_options['update'] = '#registratielijst';
	$ajaxCheckboxAction = '/registraties/ajaxUpdateQueueList/'.$action.'/'.$fieldname.'/'.$locatie_id.'/'.$registratie['Registratie']['id'];
	
	if ($registratie['Registratie'][$fieldname] > 0) {
		
		echo "<div class='ajax_text'>".$registratie['Registratie'][$fieldname]."</div>";
				$this->Js->get('#'.$fieldname.'__'.$registratie['Registratie']['id'])->event('click',
				$this->Js->request($ajaxCheckboxAction,
				$js_request_options
			)
		);
				
	} else {
		
		$input_options = array('type'=>'checkbox', 'div' => false,
			'name' =>'data[Registratie]['.$fieldname.']',
			'label'=>false, 'class' => 'compact', 'id' => 'RegistratieMw'.$registratie['Registratie']['id'], 
		);
		
		$f = '<form id="'.$fieldname.'_'.$registratie['Registratie']['id'].'" method="post" action="/regenboog/registraties" accept-charset="utf-8">';
		
		if ($registratie['Registratie'][$fieldname] == -1) {
			$input_options['checked'] = 'checked="checked"';
		} else {
			$input_options['checked'] = '';
		}
		
		$fieldId = 'Registratie'.ucfirst($fieldname).$registratie['Registratie']['id'];
		$in = '<input type="hidden" name="data[Registratie]['.$fieldname.']" id="'.$fieldId.'_" value="0" />';
		$in .= '<input type="checkbox" name="data[Registratie]['.$fieldname.']" class="compact" id="'.$fieldId.'" value="1" '.$input_options['checked'].'/>';
		
		echo $f.$in;
		echo '</form>';
		
		$this->Js->get('#'.$fieldname.'_'.$registratie['Registratie']['id'])->event('change',
				$this->Js->request($ajaxCheckboxAction,
				$js_request_options
			)
		);
	}
}

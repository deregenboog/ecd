<?php
	echo $this->Html->image('up.png', array(
		'id' => 'imgScrollup',
		'title' => __('scroll naar boven', true),
	));
	if (isset($locatie_name)) {
		echo "<h2>".__('Registratie voor ', true).$locatie_name."</h2>";
		echo "(";
		echo $html->link('locatie wijzigen', array('action' => 'index'));
		echo ")";
	} else {
		echo "<h2>".__('Klantenlist', true)."</h2>";
	}

	echo $form->create('Klant', array('controller' => 'klanten', 'action'=>'index', 'id'=>'filters'));
	$dd = array('type' => 'text', 'label' => false, 'autocomplete' => 'off');
	echo '<table class="filter"><tr>';
	echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
	echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
	echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
	echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
	echo '<td colspan="3"></td>';
	echo '<td></td></tr></table>';
	echo $form->end();

	if (!isset($add_to_list)) {
		$action ='/klanten/index';
	} else {
		$action ='/registraties/index/'.$locatie_id;
	}
	$ajax_url = $this->Html->url($action, true);
	$this->Js->get('#filters');
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
	echo $js->writeBuffer();
?>
<div id="filter-loading" style="display: none">Loading...</div>
<div id="contentForIndex">
	<?php echo $this->element('registratie_klantenlijst'); ?>
</div>

<?php

	echo $this->Form->create('History', array(
		'action' => 'ajaxShowHistory',
		$locatie_id,
	));

	$options = array(__('today only', true), __('yesterday', true));

	for ($i = 2; $i <= 8; $i++) {
		if ($i == 7) {
			$options[$i] = __('last week', true);
		} elseif ($i == 8) {
			$options[14] = sprintf(__('%s weeks ago', true), 2);
		} else {
			$options[$i] = sprintf(__('%s days ago', true), $i);
		}
	}

	echo $this->Form->input('history_limit', array(
		'options' => &$options,
		'label' => __('Show all registrations since:', true),
	));

	$this->Js->get('#HistoryHistoryLimit')->event('change', $this->Js->request(
		array('action' => 'ajaxShowHistory', $locatie_id),
		array(
			'async' => true,
			'update' => '#registratielijst',
			'data' => $this->Js->serializeForm(array(
				'isForm' => false,
				'inline' => true,
			)),
			'dataExpression' => true,
			'method' => 'post',
			'complete' => 'applyLastSorting()',
		)
	));
	echo $this->Form->end();
?>
<div id="registratielijst">
	<?php echo $this->element('registratielijst'); ?>
</div>

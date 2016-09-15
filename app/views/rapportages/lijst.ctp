	<h2><?php __('Klantenrapportages');?></h2>
<?php
	echo $html->link('Nieuwe klant invoeren', array('action' => 'add'));
	echo $form->create('Klant', array('controller' => 'klanten', 'action'=>'index', 'id'=>'filters'));
	$dd = array('type' => 'text', 'label' => false);
	echo '<table class="filter"><tr>';
	echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
	echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
	echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
	echo '<td class="roepnaamCol">'.$form->input('roepnaam', $dd).'</td>';
	echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
	echo '<td colspan="3"></td>';
	echo '</tr></table>';

	echo $form->end();
$this->Js->get('#filters')->event('keyup',
	$this->Js->request('/klanten/index/rowUrl:rapportages.klant/showDisabled:0',
		array('update' => '#contentForIndex',
			'dataExpression' => true,
			'evalScripts' => true,
			'method' => 'post',
			'before' => '$("#clientList").css("color","#999")',
			'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true)),
		)
	)
);
echo $js->writeBuffer();
?>

<div id="contentForIndex">
	<?php echo $this->element('klantenlijst'); ?>
</div>

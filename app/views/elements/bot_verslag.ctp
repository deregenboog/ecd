<?php
	$tag = Str::uuid();
?>
<div id="verslag<?= $tag?>">

<?php
	$new= false;
	if (empty($data)) {
		$new=true;
		$data = array('BotVerslag' => array(
			'id' => null,
			'klant_id' => $klant['Klant']['id'],
			'contact_type' => 'Telefonisch',
			'verslag' => null,
		));
	}
	if (empty($data['BotVerslag']['medewerker_id'])) {
		$data['BotVerslag']['medewerker_id'] = $this->Session->read('Auth.Medewerker.id');
	}
?>
	<div id="view_verslag<?= $tag; ?>">
<?php 
	if (!$new) {
?>

	<h2>Verslag van <?= $date->show($data['BotVerslag']['created'], array('short'=>true)); ?></h2>
	<p>Soort contact: <?= $data['BotVerslag']['contact_type']?></p>
	<p>Medewerker: <?= $data['BotVerslag']['Medewerker']['name'] ?></p>
	<h4>Verslag:</h4>
	<p>
	<?= preg_replace('/\n/', '<br/>', h($data['BotVerslag']['verslag'])) ?>
	</p>
<?php 
		$wrench = $html->image('wrench.png');
		echo "<a id='toevoegen{$tag}' href='#' title='Bewerk Verslag'>$wrench</a>\n";
	} else {
		echo "<a id='toevoegen{$tag}' href='#' >Verslag toevoegen</a>\n";
	}
?>
	<div>&nbsp;</div>
	</div>
	<div style="display: none;" id='edit_verslag<?= $tag; ?>'>
	
<?php
	if (!$new) {
?>
	<legend>Verslag van <?= $data['BotVerslag']['created']; ?></legend>

<?php
	}

	echo $this->Form->create('BotVerslag', array(
		'url' => array('action' => 'verslag', $data['BotVerslag']['id']),
		'id' => 'form_verslag'.$tag,
	));

	$this->Form->data = $data;
	
	echo $this->Form->hidden('id');
	
	echo $this->Form->hidden('klant_id');
	
	echo $this->Form->hidden('medewerker_id');
	
	echo $this->Form->input('contact_type', array(
		'type' => 'select',
	));

	echo $this->Form->input('BotVerslag.verslag', array(
		'type' => 'textarea',
		'rows' => 15,
		'cols' => 110,
	));

	$label = "Aanpassen";
	
	if ($new) {
		$label = 'Toevoegen';
	}
	
	echo $this->Form->button($label, array(
		'id' => 'verslag_save'.$tag,
		'type' => 'button',
	));
	
	echo "&nbsp;";
	
	echo $this->Form->button('cancel', array(
		'id' => 'verslag_cancel'.$tag,
		'type' => 'button',
	));
	
	echo $this->Form->end();
	
	$url=$this->Html->url(
		array(
			'controller' => 'bot_verslagen',
			'action' => 'edit',
			$data['BotVerslag']['id'],
		)
	);
	
	$this->Js->buffer("Ecd.verslag('{$tag}','{$url}');");
?>
	</div>
</div>
<div>&nbsp;</div>

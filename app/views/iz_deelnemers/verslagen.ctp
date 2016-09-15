<fieldset>
<?php $url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'koppelingen', $id), true); ?>

<?php 

	if (! empty($iz_koppeling)) {
		echo $html->link('Koppelingen', $url);
	}
?>

<br/>
<br/>

<h2>Verslagen:</h2> 
<h2>

<?php
	echo $persoon[$persoon_model]['name'];
	if (!empty($other_persoon)) {
		echo "&nbsp;en ".$other_persoon['name'];
		echo " (".$date->show($iz_koppeling['koppeling_startdatum']).")";
	}
?>

</h2>
<br/>
<br/>

<h3 style="cursor: pointer;" class="edit-verslag" id="edit-verslag-0"><?php echo $this->Html->image('wrench.png') ?> Verslag toevoegen</h3>

<div style="padding: 15px;">
	<div id="verslag-editor-0" class="hidden">
	
<?php

	echo $this->Form->create(
		'IzVerslag',
		array(
			'url' => array('controller' => 'IzVerslagen', 'action' => 'add_edit', $id, 'iz_koppeling_id' => $iz_koppeling['id']),
			'id' => 'verslag-form-0',
		)
	);

	echo $this->Form->hidden('iz_koppeling_id', array('value' => $iz_koppeling['id']));
	
	echo $this->Form->textarea('opmerking', array('id' => 'VerslagOpmerking-0', 'class' => 'verslag-textarea'));

	echo $this->Form->submit('Verslag invoeren', array('id' => 'verslag-submit-0', 'div' => false));

	echo $this->Form->end();
?>
	</div>
</div>

<?php foreach ($verslagen as $verslag) {
			?>
<div style="padding: 15px;">
	<h3><?= strftime('%e %B %Y om %H:%M', strtotime($verslag['IzVerslag']['created'])); ?></h3>
	<p></p>
	<p>Medewerker: 
<?php 
	if (!empty($verslag['IzVerslag']['medewerker_id'])) {
		echo $viewmedewerkers[ $verslag['IzVerslag']['medewerker_id'] ];
	} 
?>

	</p>
	
	<h4>Opmerking</h4>
	
	<div class="hidden" id="verslag-editor-<?php echo $verslag['IzVerslag']['id']?>">
<?php
	echo $this->Form->create(
		'IzVerslag',
		array(
			'url' => array('controller' => 'IzVerslagen', 'action' => 'add_edit', $id, 'iz_koppeling_id' => $iz_koppeling['id']),
			'id' => 'verslag-form-'.$verslag['IzVerslag']['id'],
		)
	);

	echo $this->Form->hidden('id', array('value' => $verslag['IzVerslag']['id'], 'id' => 'verslag-id-'.$verslag['IzVerslag']['id']));

	echo $this->Form->hidden('iz_koppeling_id', array('value' => $verslag['IzVerslag']['iz_koppeling_id']));

	echo $this->Form->textarea('opmerking', array('id' => 'VerslagOpmerking-'.$verslag['IzVerslag']['id'], 'class' => 'verslag-textarea'));

	echo $this->Form->submit('Verslag invoeren', array('id' => 'verslag-submit-'.$verslag['IzVerslag']['id'], 'div' => false));

	echo $this->Form->end(); 
?>

	</div>

	<div id="verslag-content-<?php echo $verslag['IzVerslag']['id'] ?>">
		<p><?php echo nl2br($verslag['IzVerslag']['opmerking']) ?></p>
	</div>

	<a title="Bewerken" href="#" class="edit-verslag" id="edit-verslag-<?php echo $verslag['IzVerslag']['id']?>">

	<?php echo $this->Html->image('wrench_backup.png'); ?>
	</a>
	
</div>
<?php 
		} ?>

</fieldset>

<?php
$this->Js->buffer("
	$(document).ready(function() {
		$('.edit-verslag').click(function() {
			var id = $(this).attr('id');
			id = id.replace('edit-verslag-', '');

			var editor = $('#verslag-editor-' + id);
			editor.toggleClass('hidden');

			if (id) {
				var content = $('#verslag-content-' + id);
				content.toggleClass('hidden');

				if (!editor.hasClass('hidden')) {
					$('#VerslagOpmerking-' + id).val(content.text().trim());
				}
			}

		});

	
	});
");
?>
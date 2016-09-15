<br />
<fieldset>

<h2>Verslagen</h2>

<h3 style="cursor: pointer;" class="edit-verslag" id="edit-verslag-0"><?php echo $this->Html->image('wrench.png') ?> Verslag toevoegen</h3>

<div style="padding: 15px;">
	<div id="verslag-editor-0" class="hidden">
		<?php
		
			echo $this->Form->create(
				'GroepsactiviteitenVerslag',
				array(
					'url' => array('controller' => 'GroepsactiviteitenVerslagen', 'action' => 'add_edit', $persoon_model, $persoon[$persoon_model]['id']),
					'id' => 'verslag-form-0',
				)
			);

			echo $this->Form->textarea('opmerking', array('id' => 'VerslagOpmerking-0', 'class' => 'verslag-textarea'));

			echo $this->Form->submit('Verslag invoeren', array('id' => 'verslag-submit-0', 'div' => false));

			echo $this->Form->end();
		?>
	</div>
</div>

<?php foreach ($persoon['GroepsactiviteitenVerslag'] as $verslag) {
			?>
<div style="padding: 15px;">

	<h3><?= strftime('%e %B %Y om %H:%M', strtotime($verslag['created'])); ?></h3>
	
	<p></p>
	
	<p>Medewerker: <?php echo $viewmedewerkers[ $verslag['medewerker_id'] ]; ?></p>
	
	<h4>Opmerking</h4>
	
	<div class="hidden" id="verslag-editor-<?php echo $verslag['id']?>">
	
		<?php
			echo $this->Form->create(
				'GroepsactiviteitenVerslag',
				array(
					'url' => array('controller' => 'GroepsactiviteitenVerslagen', 'action' => 'add_edit', $persoon_model, $persoon[$persoon_model]['id']),
					'id' => 'verslag-form-'.$verslag['id'],
				)
			);

			echo $this->Form->hidden('id', array('value' => $verslag['id'], 'id' => 'verslag-id-'.$verslag['id']));

			echo $this->Form->textarea('opmerking', array('id' => 'VerslagOpmerking-'.$verslag['id'], 'class' => 'verslag-textarea'));

			echo $this->Form->submit('Verslag invoeren', array('id' => 'verslag-submit-'.$verslag['id'], 'div' => false));

			echo $this->Form->end(); ?>
			
	</div>
	
	<div id="verslag-content-<?php echo $verslag['id'] ?>">
		<p><?php echo $verslag['opmerking'] ?></p>
	</div>
	
	<a title="Bewerken" href="#" class="edit-verslag" id="edit-verslag-<?php echo $verslag['id']?>">
		<?php echo $this->Html->image('wrench_backup.png'); ?>
	</a>
	
</div>
<?php } ?>

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

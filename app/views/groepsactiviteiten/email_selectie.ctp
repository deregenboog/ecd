<?= $this->element('groepsactiviteiten_subnavigation'); ?>
<?= $this->Form->create('Groepsactiviteit', array(
		'enctype' => 'multipart/form-data',
)) ;?>

<fieldset class="action data">
		<legend>E-mail</legend>
		
		Ontvangers:
		<ul id="ontvangers" stype='list-style-type: none;'>
			<?php if (count($personen) > 20) {
	?>
		<?= count($personen) ?> Personen
		<?php 
} else {
	foreach ($personen as $persoon) {
		?>
			<li  style='display: inline;'><?= $persoon['email'] ?>,</li>
		<?php 
	}
} ?>
		</ul>
		<br class="clear">
		<br class="clear">
		<p>Afzender: <?php echo $this->Session->read('Auth.Medewerker.LdapUser.mail'); ?></p>

		<?= $this->Form->input('onderwerp', array('style' => 'width: 500px')) ;?>
		<br>
		<?= $this->Form->input('text', array('label' => 'Bericht', 'type' => 'textarea', 'style' => 'width: 500px')) ;?>
		
		<?= $this->Form->hidden('Document.0.group', array('value' => 'email'))?>
		<?= $this->Form->input('Document.0.file', array(
				'type' => 'file',
			));
		?>
		
		<?= $this->Form->hidden('Document.1.group', array('value' => 'email'))?>
		<?= $this->Form->input('Document.1.file', array(
				'type' => 'file',
			));
		?>
		
		<?= $this->Form->hidden('Document.2.group', array('value' => 'email'))?>
		<?= $this->Form->input('Document.2.file', array(
				'type' => 'file',
			));
		?>
		
		<?php 
			if (isset($this->validationErrors['Document']['2'])) {
				echo $this->data['Document'][2]['file']['name'].' '.$this->data['Document'][2]['file']['type'];
			}
		?>
		
		<?= $this->Form->submit('Verzenden') ;?>
</fieldset>
<?= $this->Form->end() ;?>


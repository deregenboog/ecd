<?php /* @var $form FormHelper */ ?>

<?= $this->element('hs_subnavigation') ?>

<h1>Klant toevoegen</h1>

<?= $form->create() ?>
	<table>
		<tr>
			<td>Voornaam</td>
			<td><?= $form->input('Klant.voornaam') ?></td>
		</tr>
		<tr>
			<td>Tussenvoegsel</td>
			<td><?= $form->input('Klant.tussenvoegsel') ?></td>
		</tr>
		<tr>
			<td>Achternaam</td>
			<td><?= $form->input('Klant.achternaam') ?></td>
		</tr>
	</table>
<?= $form->end('Volgende stap') ?>

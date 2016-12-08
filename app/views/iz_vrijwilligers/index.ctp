<?php /* @var $html HtmlHelper */?>
<?php /* @var $form FormHelper */?>
<?php /* @var $paginator PaginatorHelper */?>
<?php /* @var $medewerker MedewerkerHelper */?>
<?php /* @var $projecten ProjectHelper */?>
<?= $this->element('iz_subnavigation') ?>

<h2>Vrijwilligers</h2>
<div id="contentForIndex">
	<table id="clientList" class="index filtered">
		<thead>
			<?= $form->create('IzVrijwilliger') ?>
				<tr>
					<th colspan="2">
						<?= $paginator->counter(['format' => __('%count% resultaten', true)]) ?>
					</th>
					<th colspan="2">
						<?= $form->input('Query.name', [
							'type' => 'select',
							'label' => 'Selectie',
							'empty' => '',
							'options' => [
								'beginstand' => 'Beginstand',
								'gestart' => 'Gestart',
								'gestopt' => 'Gestopt',
								'eindstand' => 'Eindstand',
							],
						]) ?>
					</th>
					<th>
						<?= $form->input('Query.from', [
							'type' => 'date',
							'dateFormat' => 'DMY',
							'label' => 'Van',
							'empty' => '',
						]) ?>
					</th>
					<th>
						<?= $form->input('Query.until', [
							'type' => 'date',
							'dateFormat' => 'DMY',
							'label' => 'T/m',
							'empty' => '',
						]) ?>
					</th>
					<th></th>
					<th colspan="2">
						<?= $html->link('Huidige selectie downloaden', $this->params['named'] + ['format' => 'csv']) ?>
						<?= $form->submit('Filteren') ?>
					</th>
				</tr>
				<tr>
					<th>
						<?= $this->Paginator->sort('Nummer', 'Vrijwilliger.id') ?>
					</th>
					<th>
						<?= $this->Paginator->sort('Voornaam', 'Vrijwilliger.voornaam') ?>
					</th>
					<th>
						<?= $this->Paginator->sort('Achternaam', 'Vrijwilliger.achternaam') ?>
					</th>
					<th>
						<?= $this->Paginator->sort('Geboortedatum', 'Vrijwilliger.geboortedatum') ?>
					</th>
					<th>Medewerker dossier</th>
					<th>Medewerker intake</th>
					<th>Medewerker(s) hulpaanbod</th>
					<th>Project(en)</th>
					<th>Stadsdeel</th>
				</tr>
				<tr>
					<th></th>
					<th>
						<?= $form->input('Vrijwilliger.voornaam', array('label' => '')) ?>
					</th>
					<th>
						<?= $form->input('Vrijwilliger.achternaam', array('label' => '')) ?>
					</th>
					<th>
						<?= $form->input('Vrijwilliger.geboortedatum', array('label' => '', 'empty' => '')) ?>
					</th>
					<th>
						<?= $form->input('Vrijwilliger.medewerker_id', array('label' => '', 'empty' => '')) ?>
					</th>
					<th>
						<?= $form->input('IzIntake.medewerker_id', array('label' => '', 'empty' => '')) ?>
					</th>
					<th>
						<?= $form->input('IzHulpaanbod.medewerker_id', array('label' => '', 'empty' => '')) ?>
					</th>
					<th>
						<?= $form->input('IzHulpaanbod.project_id', array('label' => '', 'empty' => '')) ?>
					</th>
					<th>
						<?= $form->input('Stadsdeel.stadsdeel', array('label' => '', 'empty' => '', 'type' => 'select', 'options' => $werkgebieden)) ?>
					</th>
				</tr>
			<?= $form->end() ?>
		</thead>
<?php

foreach ($personen as $i => $persoon):
	$altrow = ($i++ % 2 == 0) ? 'altrow' : '';
// 	if (!isset($rowOnclickUrl)) {
// 		$url = $html->url(array('controller' => 'klanten', 'action' => 'view', $persoon['IzVrijwilliger']['id']));
// 	} else {
// 		$urlArray = $rowOnclickUrl;
// 		$urlArray[] = $persoon['IzVrijwilliger']['id'];
// 		$url = $this->Html->url($urlArray);
// 	}
?>
	<tr class="klantenlijst-row <?= $altrow ?>">
		<td>
			<?= $persoon['Vrijwilliger']['id'] ?>
		</td>
		<td>
			<?= $this->Format->name1st($persoon['Vrijwilliger']); ?>
		</td>
		<td>
			<?= $persoon['Vrijwilliger']['name2nd_part'] ?>
		</td>
		<td>
			<?= $date->show($persoon['Vrijwilliger']['geboortedatum'], array('short'=>true)) ?>
		</td>
		<td>
			<?= $medewerker->htmlList($persoon['Vrijwilliger'], $medewerkers) ?>
		</td>
		<td>
			<?= $medewerker->htmlList($persoon['IzIntake'], $medewerkers, 'GEEN INTAKE') ?>
		</td>
		<td>
			<?= $medewerker->htmlList($persoon['IzHulpaanbod'], $medewerkers) ?>
		</td>
		<td>
			<?= $project->htmlList($persoon['IzHulpaanbod'], $projecten) ?>
		</td>
		<td>
			<?= $persoon['Vrijwilliger']['werkgebied']; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<p>
	<?= $this->Paginator->counter(array(
		'format' => __('Page %page% of %pages% (total: %count% volunteers)', true),
	)) ?>
</p>

<div class="paging">
	<?= $this->Paginator->prev('<< '.__('previous', true)) ?>
	| <?= $this->Paginator->numbers() ?>
	| <?= $this->Paginator->next(__('next', true).' >>') ?>
</div>

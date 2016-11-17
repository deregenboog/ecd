<?php
	$default = array(
		'id' => true,
		'name1st_part' => true,
		'name2nd_part' => true,
		'name' => false,
		'geboortedatum' => true,
		'medewerker_id' => false,
		'iz_projecten' => true,
		'medewerker_ids' => true,
		'werkgebied' => true,
		'last_zrm' => true,
		'dummycol' => false,
	);
	if (empty($fields)) {
		$fields = array();
	}
	$fields = array_merge($default, $fields);

	$paginator->options(array(
	'update' => '#contentForIndex',
		'evalScripts' => true,
	));
	$filter_options = array();
?>

<table id="clientList" class="index filtered">
	<tr>
		<th class="klantnrCol">
			<?php echo $this->Paginator->sort('Nr', 'id', $filter_options); ?>
		</th>

		<th class="voornaamCol">
			<?php echo $this->Paginator->sort('Voornaam/ (Roepnaam)', 'name1st_part', $filter_options); ?>
		</th>

		<th class="achternaamCol">
			<?php echo $this->Paginator->sort('Achternaam', 'name2nd_part', $filter_options); ?>
		</th>

		<th class="gebortedatumCol">
			<?php echo $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options); ?>
		</th>
		<th class="izProjectsCol">
			Project
		</th>
		<th class="IzMedewerkerCol">
			Medewerker
		</th>
		<th class="werkgebiedCol">
			Werkgebied
		</th>
		<th class="lastZrmCol">
			Laatste ZRM
		</th>
	</tr>
	<?php foreach ($klanten as $i => $klant): ?>
		<tr class="klantenlijst-row <?= ($i++ % 2 == 0) ? 'altrow' : '' ?>">
			<td class="klantnrCol">
				<?=
					$this->Html->link(
						$klant['IzKlant']['id'],
						array(
							'controller' => 'iz_deelnemers',
							'action' => 'view',
							$klant['IzKlant']['id'],
						)
					)
				?>
			</td>
			<td class="voornaamCol">
				<?=
					$this->Html->link(
						$this->Format->name1st($klant['Klant']),
						array(
							'controller' => 'iz_deelnemers',
							'action' => 'view',
							$klant['IzKlant']['id'],
						)
					)
				?>
			</td>
			<td class="achternaamCol">
				<?=
					$this->Html->link(
						$klant['Klant']['name2nd_part'],
						array(
							'controller' => 'iz_deelnemers',
							'action' => 'view',
							$klant['IzKlant']['id'],
						)
					)
				?>
			</td>
			<td class="gebortedatumCol">
				<?= $date->show($klant['Klant']['geboortedatum'], array('short'=>true)); ?>&nbsp;
			</td>
			<td class="">
				<?= $klant['IzKlant']['projectlist'] ?>
			</td>
			<td class="">
				<?php
					if (! empty($klant['IzKlant']['medewerker_ids'])) {
						$s='';
						foreach ($klant['IzKlant']['medewerker_ids'] as $medewerker_id) {
							if (! empty($s)) {
								$s.=', <br/>';
							}
							$s .= $viewmedewerkers[$medewerker_id];
						}

						echo $s;
					}
				?>
			</td>
			<td class="">
				<?= $klant['Klant']['werkgebied']; ?>
			</td>
			<?php
				$zrm = strtotime($klant['Klant']['last_zrm']);
				$now = strtotime(date('Y-m-d'));

				$style = "";
				if ($zrm < $now - 5 * 30 * 24 * 60 * 60) {
					$style = "background-color: orange; color: white;";
				}

				if ($zrm < $now - 6 * 30 * 24 * 60 * 60) {
					$style = "background-color: red; color: white;";
				}

				if (empty($klant['Klant']['last_zrm'])) {
					$style = "";
				}
			?>
			<td class="" style="<?= $style; ?>">
				<?= $date->show($klant['Klant']['last_zrm'], array('short'=>true)); ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>

<p>
	<?php
		echo $this->Paginator->counter(array(
		'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
		));
	?>
</p>

<?php //$num_filter = $filter_options;?>

<?php
// 		if (isset($locatie_id)) {
// 			$num_filter['url'][0] = $filter_options['url'][0].$locatie_id;
// 		}
?>

<div class="paging">
	<?= $this->Paginator->prev('<< '.__('previous', true), $filter_options, null, array('class'=>'disabled')) ?>
	| <?php //echo $this->Paginator->numbers($num_filter);?>
	| <?= $this->Paginator->next(__('next', true).' >>', $filter_options, null, array('class' => 'disabled')) ?>
</div>

	   <tr>
			<th class="klantnrCol">
			<?php
				echo $this->Paginator->sort('Klantnr', 'id', $filter_options);
			?>
			</th>
			
			<th class="voornaamCol">
			<?php
				echo $this->Paginator->sort('Voornaam/(Roepnaam)', 'name1st_part', $filter_options);
			?>
			</th>
			
			<th class="achternaamCol">
			<?php
				echo $this->Paginator->sort('Achternaam', 'name2nd_part', $filter_options);
			?>
			</th>
			
			<th class="gebortedatumCol">
			<?php
				echo $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options);
			?>
			</th>
			
			<th class="geschlachtCol" style="vertical-align: bottom;">
			<?php
				echo $this->Paginator->sort('G', 'geslacht_id', $filter_options);
			?>
			</th>
			
			<th class="1eIntakeCol">1<sup>e</sup> Locatie</th>
			
			<th class="2eIntakesCol">2<sup>e</sup> Locatie</th>
			
			<th class="3eIntakesCol">3<sup>e</sup> Locatie</th>
			
			<th class="laatsteIntakeCol">Laatste intake</th>
			
			<th class="aantalIntakesCol">Aantal intakes</th>
		</tr>

<?php

$paginator->options(array(
		'update' => '#contentForIndex',
		'evalScripts' => true,
));

$i = 0;

foreach ($klanten as $klant) {
	
	if ($i++ % 2 == 1) {
		$altrow = ' altrow';
	} else {
		$altrow = null;
	}
	
	$hi5IntakeCount = count($klant['Hi5Intake']);
	$klant_id = $klant['Klant']['id'];

	$url = $this->Html->url(array(
			'controller' => 'Hi5',
			'action' => 'view',
			$klant_id,
	), true); 
?>
	<tr class="klantenlijst-row <?= $altrow; ?>"
		onMouseOver="this.style.cursor='pointer'"
		onClick="location.href='<?=$url; ?>';"
		id='klanten_<?=$klant['Klant']['id']?>'
	>
	
	<td class="klantnrCol"><?=$klant['Klant']['id']; ?>&nbsp;</td>
	<td class="voornaamCol"><?=$this->Format->name1st($klant['Klant']); ?>&nbsp;</td>
	<td class="achternaamCol"><?=$klant['Klant']['name2nd_part']; ?>&nbsp;</td>
	<td class="gebortedatumCol"><?=$klant['Klant']['geboortedatum']; ?>&nbsp;</td>
	<td class="geschlachtCol"><?=$klant['Geslacht']['afkorting']; ?>&nbsp;</td>
	<td class="1eIntakeCol">
<?php
	if ($hi5IntakeCount && isset($klant['Hi5Intake'][0]['Locatie1']['naam'])) {
		echo $klant['Hi5Intake'][0]['Locatie1']['naam'];
	} 
?>
	</td>
	<td class="2eIntakesCol">
<?php
	if ($hi5IntakeCount && isset($klant['Hi5Intake'][0]['Locatie2']['naam'])) {
		echo $klant['Hi5Intake'][0]['Locatie2']['naam'];
	} 
?>
	</td>
	
	<td class="3eIntakesCol">
<?php
	if ($hi5IntakeCount && isset($klant['Hi5Intake'][0]['Locatie3']['naam'])) {
		echo $klant['Hi5Intake'][0]['Locatie3']['naam'];
	} 
?>
	/td>
	<td class="laatsteIntakeCol"><?=$hi5IntakeCount?$klant['Hi5Intake'][0]['datum_intake']:''; ?></td>
	<td class="aantalIntakesCol"><?=$hi5IntakeCount; ?></td>
	</tr>
<?php } ?>

<tr id="table_footer">
	<td colspan=6>
	<p>

<?php
	echo $this->Paginator->counter(array(
			'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	));
	$num_filter = $filter_options;
	if (isset($locatie_id)) {
		$num_filter['url'][0] = $filter_options['url'][0].$locatie_id;
	}
?>

	</p>
	<div class="paging">

<?php
	echo $this->Paginator->prev('<< '.__('previous', true), $filter_options, null, array(
			'class' => 'disabled',
	));
	?> | <?php
	echo $this->Paginator->numbers($num_filter);
	?> | <?php
	echo $this->Paginator->next(__('next', true).' >>', $filter_options, null, array(
		'class' => 'disabled',
	));
	?>
	</div>
	<?=$js->writeBuffer();?>
	</td>
</tr>

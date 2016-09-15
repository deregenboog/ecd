<?php
	$paginator->options(array(
			   'update' => '#contentForIndex',
			'evalScripts' => true,
	));
?>
	
	<table id="clientList" class="index filtered">
	<tr>
			<th class="klantnrCol"><?= $this->Paginator->sort('Klantnr', 'id', $filter_options);?></th>
			<th class="voornaamCol"><?= $this->Paginator->sort('Voornaam/(Roepnaam)', 'name1st_part', $filter_options);?></th>
			<th class="achternaamCol"><?= $this->Paginator->sort('Achternaam', 'name2nd_part', $filter_options);?></th>
			<th class="gebortedatumCol"><?= $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options);?></th>
			<th class="geschlachtCol"><?= $this->Paginator->sort('Geslacht', 'geslacht_id', $filter_options);?></th>
			<th></th>
	</tr>
	<?php

	$i = 0;
	foreach ($klanten as $klant):
		if ($i++ % 2 == 0) {
			$altrow = ' altrow';
		} else {
			$altrow = null;
		}

		$klant_id = $klant['Klant']['id'];

		$url = $this->Html->url(
			array('controller' => 'awbz', 'action' => 'view', $klant_id),
			true
		);

		?>
		<tr class="klantenlijst-row<?php echo $altrow;?>" 
			onMouseOver="this.style.cursor='pointer'" 
			onClick="location.href='<?= $url; ?>';"
			id='klanten_<?= $klant['Klant']['id']?>'
		>

			<td class="klantnrCol"><?= $klant['Klant']['id']; ?>&nbsp;</td>
			<td class="voornaamCol"><?= $this->Format->name1st($klant['Klant']);?>&nbsp;</td>
			<td class="achternaamCol"><?= $klant['Klant']['name2nd_part']; ?>&nbsp;</td>
			<td class="gebortedatumCol"><?= $klant['Klant']['geboortedatum']; ?>&nbsp;</td>
			<td class="geschlachtCol"><?= $klant['Geslacht']['afkorting']; ?>&nbsp;</td>
			<td></td>
		</tr>
	<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	));
	?>	</p>
	<?php $num_filter = $filter_options;?>
	<?php
		if (isset($locatie_id)) {
			$num_filter['url'][0] = $filter_options['url'][0].$locatie_id;
		}
	?>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), $filter_options, null, array('class'=>'disabled'));?>
	 |	<?php echo $this->Paginator->numbers($num_filter);?>
	 |
		<?php echo $this->Paginator->next(__('next', true).' >>', $filter_options, null, array('class' => 'disabled'));?>
	</div>
<?= $js->writeBuffer();?>

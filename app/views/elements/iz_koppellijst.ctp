<?php

function combineer_naam($t, $a)
{
	$n=$t;
	if (!empty($n)) {
		$n.=" ";
	}
	$n.=$a;
	return $n;
}
?>
<?php

	$paginator->options(array(
			'update' => '#contentForIndex',
			'evalScripts' => true,
	));

?>

<div class="izOntstaanContacten ">
	<table cellpadding="0" cellspacing="0"	class="index filtered">
	<tr>
			<th class='IzKlKvoornaam'><?php echo $this->Paginator->sort('Klant voornaam', 'Klant.voornaam');?></th>
			<th class='IzKlKachternaam'><?php echo $this->Paginator->sort('Klant achternaam', 'Klant.achternaam');?></th>
			<th class='IzKlVvoornaam'><?php echo $this->Paginator->sort('Vrijwilliger voornaam', 'Vrijwilliger.voornaam');?></th>
			<th class='IzKlVachternnaam'><?php echo $this->Paginator->sort('Vrijwilliger achternaam', 'Vrijwilliger.achternaam');?></th>
			<th class='IzKlmedewerker'>Medewerker</th>
			<th class='IzKlstartdatum'><?php echo $this->Paginator->sort('Startdatum', 'Vrijwilliger.koppeling_startdatum');?></th>
			<th class='IzKlwerkgebied'><?php echo $this->Paginator->sort('Werkgebied', 'Klant.werkgebied');?></th>
			<th class='IzKlproject'><?php echo $this->Paginator->sort('Project', 'Vrijwilliger.project_naam');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($personen as $persoon):
		//debug($persoon);

		$kurl=array(
			'action' => 'koppelingen',
			$persoon['IzDeelnemer']['id'],
		);
		$vurl=array(
			'action' => 'koppelingen',
			$persoon['Vrijwilliger']['iz_deelnemer_id'],
		);

		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		$mw = $viewmedewerkers[$persoon['Vrijwilliger']['medewerker_id']];
		if ($mw != $viewmedewerkers[$persoon['Vrijwilliger']['klant_medewerker_id']]) {
			$mw.= "<br/>".$viewmedewerkers[$persoon['Vrijwilliger']['klant_medewerker_id']];
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $html->link($persoon['Klant']['voornaam'], $kurl); ?>&nbsp;</td>
		<td><?php echo $html->link(combineer_naam($persoon['Klant']['tussenvoegsel'], $persoon['Klant']['achternaam']), $kurl); ?>&nbsp;</td>
		<td><?php echo $html->link($persoon['Vrijwilliger']['voornaam'], $vurl); ?>&nbsp;</td>
		<td><?php echo $html->link(combineer_naam($persoon['Vrijwilliger']['tussenvoegsel'], $persoon['Vrijwilliger']['achternaam']), $vurl); ?>&nbsp;</td>
		<td><?php echo $mw; ?>&nbsp;</td>
		<td><?php echo $date->show($persoon['Vrijwilliger']['koppeling_startdatum']); ?>&nbsp;</td>
		<td><?php echo $persoon['Klant']['werkgebied']; ?>&nbsp;</td>
		<td><?php echo $persoon['Vrijwilliger']['project_naam']; ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => 'Pagina %page% van %pages%, met %current% van de %count% koppelingen',
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 |	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>

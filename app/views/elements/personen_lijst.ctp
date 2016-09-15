<?php

$default = array(
	'id' => true,
	'name1st_part' => true,
	'name2nd_part' => true,
	'name' => false,
	'geboortedatum' => true,
	'medewerker_id' => true,
	'bot' => false,
	'iz_projecten' => false,
	'werkgebied' => false,
	'iz_coordinator' => false,
	'medewerker_ids' => false,
	'iz_datum_aanmelding' => false,
	'last_zrm' => false,
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

?>
	
<table id="clientList" class="index filtered">
<tr>
<?php 
	if ($fields['id']) {
?>
	<th class="klantnrCol">
	<?php echo $this->Paginator->sort('Nr', 'id', $filter_options); ?>
	</th>

<?php } ?>

<?php 
	if ($fields['name1st_part']) {
?>
	<th class="voornaamCol">
	<?php echo $this->Paginator->sort('Voornaam/ (Roepnaam)', 'name1st_part', $filter_options); ?>
	</th>

<?php } ?>

<?php 
	if ($fields['name2nd_part']) {
?>
	<th class="achternaamCol">
	<?php echo $this->Paginator->sort('Achternaam', 'name2nd_part', $filter_options); ?>
	</th>
<?php } ?>

<?php 
	if ($fields['geboortedatum']) {
?>
	<th class="gebortedatumCol">
	<?php echo $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options); ?>
	</th>
<?php } ?>

<?php 
	if ($fields['medewerker_id']) {
?>
	<th class="medewerkerCol">Medewerker</th>
<?php } ?>

<?php 
	if ($fields['bot']) {
?>
	<th class="botCol">BOT'</th>
<?php } ?>

<?php 
	if ($fields['iz_projecten']) {
?>
	<th class="izProjectsCol">Project</th>
<?php } ?>

<?php 
	if ($fields['iz_coordinator']) {
?>
	<th class="IzCoordinatorCol">Coordinator</th>
<?php } ?>

<?php 
	if ($fields['medewerker_ids']) {
?>
	<th class="IzMedewerkerCol">Medewerker</th>
<?php } ?>

<?php 
	if ($fields['werkgebied']) {
?>
	<th class="werkgebiedCol">Werkgebied</th>
<?php } ?>


<?php 
	if ($fields['iz_datum_aanmelding']) {
?>
	<th class="IzDatumaanmeldingCol">
	<?= $this->Paginator->sort('Datum aanmelding', 'IzDeelnemer.datum_aanmelding', $filter_options); ?>
	</th>
<?php } ?>

<?php 
	if ($fields['last_zrm']) {
?>
	<th class="lastZrmCol">
	Laatste ZRM
	</th>
<?php } ?>

<?php if ($fields['dummycol']) { ?>

	<th class="show_allCol">
	   &nbsp; 
	</th>
<?php } ?>
</tr>
<?php

$i = 0;
foreach ($personen as $persoon):
	if ($i++ % 2 == 0) {
		$altrow = ' altrow';
	} else {
		$altrow = null;
	}
?>

<?php if (!isset($add_to_list)):?> 

<?php
	if (!isset($rowOnclickUrl)) {
		$url = $html->url(array('controller' => 'klanten', 'action' => 'view', $persoon[$persoon_model]['id']));
	} else {
		$urlArray = $rowOnclickUrl;
		$urlArray[] = $persoon[$persoon_model]['id'];
		$url = $this->Html->url($urlArray);
	}
?>
	<tr class="klantenlijst-row<?php echo $altrow;?>" 
			onMouseOver="this.style.cursor='pointer'" 
			onClick="location.href='<?php echo $url; ?>';"
			id='klanten_<?php echo $persoon[$persoon_model]['id']?>'>		

<?php else:?>
	<tr class="klantenlijst-row<?php echo $altrow;?>" onMouseOver="this.style.cursor='pointer'" id='klanten_<?php echo $klant['Klant']['id']?>'>
<?php endif;?>

<?php 
	if ($fields['id']) {
?>
	<td class="klantnrCol"><?php echo $persoon[$persoon_model]['id']; ?>&nbsp;</td>
<?php } ?>

<?php 
	if ($fields['name1st_part']) {
?>
	<td class="voornaamCol"><?= $this->Format->name1st($persoon[$persoon_model]); ?>&nbsp;</td>
<?php } ?>

<?php 
	if ($fields['name2nd_part']) {
?>
	<td class="achternaamCol">

<?php
	if (isset($persoon[$persoon_model]['name2nd_part'])) {
			echo $persoon[$persoon_model]['name2nd_part'];
	} 
?>
	&nbsp;
	</td>
<?php } ?>

<?php 
	if ($fields['geboortedatum']) {
?>
	<td class="gebortedatumCol">
	<?= $date->show($persoon[$persoon_model]['geboortedatum'], array('short'=>true)); ?>&nbsp;
	</td>

<?php } ?>

<?php 
	if ($fields['medewerker_id']) {
?>
	<td class="medewerkerCol">
<?php 
	if (isset($viewmedewerkers[$persoon[$persoon_model]['medewerker_id']])) {
		echo $viewmedewerkers[$persoon[$persoon_model]['medewerker_id']];
	} 
?>
	&nbsp;
	</td>
<?php } ?>

<?php 
	if ($fields['bot']) {
?>
	<td class="">
<?php
	if (isset($klant['BackOnTrack']['id'])) {
		echo $date->show($klant['BackOnTrack']['startdatum'], array('short'=>true));
		echo " ";
		echo $date->show($klant['BackOnTrack']['einddatum'], array('short'=>true));
	} else {
		echo '-';
	} 
?>
	</td>
<?php } ?>

<?php 
	if ($fields['iz_projecten']) {
?>
	<td class="">
<?php
	echo $persoon[$persoon_model]['projectlist']; 
?>
	&nbsp;
	</td>

<?php } ?>

<?php 
	if ($fields['iz_coordinator']) {
?>
	<td class="">
<?php
	if (isset($persoon['IzDeelnemer']['IzIntake']['medewerker_id'])) {
		echo $viewmedewerkers[$persoon['IzDeelnemer']['IzIntake']['medewerker_id']];
	}
?>
	</td>
			
<?php } ?>

<?php 
	if ($fields['medewerker_ids']) {
?>
	<td class="">
<?php
	if (! empty($persoon[$persoon_model]['medewerker_ids'])) {
		$s='';
		foreach ($persoon[$persoon_model]['medewerker_ids'] as $medewerker_id) {
			if (! empty($s)) {
				$s.=', <br/>';
			}
			$s .= $viewmedewerkers[$medewerker_id];
		}

		echo $s;
	} 
?>
	</td>
			
<?php } ?>

<?php 
	if ($fields['werkgebied']) {
?>
	<td class="">

	<?= $persoon[$persoon_model]['werkgebied']; ?>
	&nbsp;
	</td>
<?php } ?>
	
<?php 
	if ($fields['iz_datum_aanmelding']) {
?>
	<td class="">
	<?= $date->show($persoon['IzDeelnemer']['datum_aanmelding'], array('short'=>true)); ?>
	&nbsp;
	</td>
<?php } ?>
<?php 
	if ($fields['last_zrm']) {
		$zrm = strtotime($persoon['Klant']['last_zrm']);
		$now = strtotime(date('Y-m-d'));

		$style = "";
		if ($zrm < $now - 5 * 30 * 24 * 60 * 60) {
			$style = "background-color: orange; color: white;";
		}

		if ($zrm < $now - 6 * 30 * 24 * 60 * 60) {
			$style = "background-color: red; color: white;";
		}

		if (empty($persoon['Klant']['last_zrm'])) {
			$style = "";
		} 
?>
	<td class="" style="<?= $style; ?>">
	<?= $date->show($persoon['Klant']['last_zrm'], array('short'=>true)); ?>
	</td>
<?php } ?>

<?php
		if ($fields['dummycol']) {
?>
	<td class="show_allCol" >&nbsp;
	</td>
<?php

	}
?>
	</tr>
<?php endforeach; ?>
	</table>

<?php
	if (isset($add_to_list)) {

		foreach ($klanten as $klant) {
			$this->Js->get('#klanten_'.$klant['Klant']['id'])->event('click',
				$this->Js->request('/registraties/ajaxAddRegistratie/'.$klant['Klant']['id'].'/'.$locatie_id,
					array('update' => '#registratielijst',
						'dataExpression' => true,
						'evalScripts' => true,
						'method' => 'post',
						'before' => '$("#loading").css("display","block")',
						'complete' => '$("#loading").css("display","none");',
						)
					)
				);
		}
	}
?>
	<p>
<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	));
?>	
	</p>

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
<?php
	if ($this->name === 'Registraties' && $this->action === 'index') {
		$this->Js->get('.klantenlijst-row')->event('click',
			$this->Js->request('/registraties/index/'.$locatie_id,
				array(
					'update' => '#contentForIndex',
					'before' => '$("#filters :input[type=\'text\']").val("");',
				)

			)
		);
	}

	echo $js->writeBuffer();

<?php
	$paginator->options(array(
			   'update' => '#contentForIndex',
			'evalScripts' => true,
	));
?>

	<table id="clientList" class="index filtered">
	<tr>
			<th class="klantnrCol"><?php echo $this->Paginator->sort('Klantnr', 'id', $filter_options);?></th>
			<th class="voornaamCol"><?php echo $this->Paginator->sort('Voornaam/(Roepnaam)', 'name1st_part', $filter_options);?></th>
			<th class="achternaamCol"><?php echo $this->Paginator->sort('Achternaam', 'achternaam', $filter_options);?></th>
			<th class="gebortedatumCol"><?php echo $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options);?></th>
			<th class="geschlachtCol"><?php echo $this->Paginator->sort('G', 'geslacht_id', $filter_options);?></th>
			<th class="laatsteIntakeCol"><?php echo '1<sup>e</sup> Locatie';?></th>
			<th class="aantalIntakesCol"><?php echo '2<sup>e</sup> Locatie';?></th>
			<th class="aantalIntakesCol"><?php echo '3<sup>e</sup> Locatie';?></th>
			<th class="newIntakeCol"><?= __('New intake needed', true);?></th>
			<th class="schorsingenCol">Schorsingen</th>
	</tr>
	<?php

	$i = 0;
	foreach ($klanten as $klant):
		if ($i++ % 2 == 0) {
			$altrow = ' altrow';
		} else {
			$altrow = null;
		}

	?>
	<?php if (!isset($add_to_list)):?>
		<?php
			if (!isset($rowOnclickUrl)) {
				$url = $html->url(array('controller' => 'klanten', 'action' => 'view', $klant['Klant']['id']));
			} else {
				$urlArray = $rowOnclickUrl;
				$urlArray[] = $klant['Klant']['id'];
				$url = $this->Html->url($urlArray);
			}
		?>
		<tr class="klantenlijst-row<?php echo $altrow;?>"
					onmouseover="this.style.cursor='pointer'"
					onclick="location.href='<?php echo $url; ?>';"
					id='klanten_<?php echo $klant['Klant']['id']?>'>
	<?php else:?>

		<tr class="klantenlijst-row<?php echo $altrow;?>"
			onmouseover="this.style.cursor='pointer'"
			id='klanten_<?php echo $klant['Klant']['id']?>'
		>
	<?php endif;?>

			<td class="klantnrCol"><?php echo $klant['Klant']['id']; ?>&nbsp;</td>
			<td class="voornaamCol"><?php echo $this->Format->name1st($klant['Klant']); ?>&nbsp;</td>
			<td class="achternaamCol"><?php echo $klant['Klant']['name2nd_part']; ?>&nbsp;</td>
			<td class="gebortedatumCol"><?php echo $date->show($klant['Klant']['geboortedatum'], array('short'=>true));  ?>&nbsp;</td>
			<td class="geschlachtCol"><?php echo $klant['Geslacht']['afkorting']; ?>&nbsp;</td>
			<td class="laatsteIntakeCol"><?php
					if (isset($klant['LasteIntake']['Locatie1']['naam'])) {
						echo $klant['LasteIntake']['Locatie1']['naam'];
					} else {
						echo '-';
					}
				?>
			</td>
			<td class="aantalIntakesCol"><?php
				if (isset($klant['LasteIntake']['Locatie2']['naam'])) {
					echo $klant['LasteIntake']['Locatie2']['naam'];
				} else {
						echo '-';
					}
				?>
			</td>
			<td class="aantalIntakesCol"><?php
				if (isset($klant['LasteIntake']['Locatie3']['naam'])) {
					echo $klant['LasteIntake']['Locatie3']['naam'];
				} else {
						echo '-';
					}
				?>
			</td>
			<td class="newIntakeCol">
				<?php if ($klant['Klant']['new_intake_needed'] < 0) {
					?>
				<span class="warning">
					<?= __('Ja', true).', '.
						$this->Date->humanDays(- $klant['Klant']['new_intake_needed']).
						' '.
						__('verlopen', true)
					?>
				</span>
				<?php 
				} else {
					?>
					<?= __('Nee', true).', '.
						$this->Date->humanDays($klant['Klant']['new_intake_needed']).
						' '.
						__('geldig', true)
					?>
				<?php 
				} ?>
			</td>
			<td>
				<?php
					if (
						$klant['Klant']['schorsingen'] == 'Hier geschorst'
					) {
						$strong = '<span class="warning">';
						$strong_end = '</span>';
					} else {
						$strong = $strong_end = '';
					}
					echo $strong;
					$msg = $klant['Klant']['schorsingen'];
					if (!empty($klant['Klant']['schorsing_locatie_id'])) {
						$msg = _("Geschorst bij")." ".$locaties[$klant['Klant']['schorsing_locatie_id']]." "._("van")." ";
						$msg .= $date->show($klant['Klant']['schorsing_datum_van'], array('short'=>true));
						$msg .= " "._("tot")." ".$date->show($klant['Klant']['schorsing_datum_tot'], array('short'=>true));
					}
					echo $msg;
					echo $strong_end;
				?>
			</td>
		</tr>
	<?php
		endforeach;
	?>
	</table>
	<?php
	if (isset($add_to_list)) {

		$confirm_msg =
			__('This client has been checked out less than an hour ago. '.
			   'Are you sure you want to register him/her again?', true);

		foreach ($klanten as &$klant) {

			$klant_id = & $klant['Klant']['id'];

			$request = $this->Js->request(
				'/registraties/ajaxAddRegistratie/'.
					$klant_id.'/'.$locatie_id,
				array(
					'update' => '#registratielijst',
					'dataExpression' => true,
					'evalScripts' => true,
					'method' => 'post',
					'before' => '$("#loading").css("display","block")',
					'complete' => '$("#loading").css("display","none")',
				)
			);
			$remote_func_url = $this->Html->url(array(
				'controller' => 'registraties',
				'action' => 'jsonCanRegister',
			), true);

			$onclick_script = '
				var can_register =
					ajax_check_can_register("'.
					$klant_id.'","'.$locatie_id.'","'.$remote_func_url.'");
				if(can_register.allow && can_register.confirm){
					var confirm_action = confirm(can_register.message);
				} else if(can_register.allow){
					var confirm_action = true;
				}else{
					alert(can_register.message);
					var confirm_action = false;
				}
			$("#loading").css("display","none");

				if(confirm_action){'.$request.'}
			';

			$this->Js->get('#klanten_'.$klant_id)->event(
				'click', $onclick_script);

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
?>


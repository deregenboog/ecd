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
			<th class="achternaamCol"><?php echo $this->Paginator->sort('Achternaam', 'name2nd_part', $filter_options);?></th>
			<th class="gebortedatumCol"><?php echo $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options);?></th>
			<th class="geschlachtCol"><?php echo $this->Paginator->sort('G', 'geslacht_id', $filter_options);?></th>
			<th class="1eIntakeCol"><?php echo 'Gebruikersruimte';?></th>
			<th class="2eIntakesCol"><?php echo 'Intake Locatie';?></th>
			<th class="3eIntakesCol"><?php echo '';?></th>
			<th class="laatsteIntakeCol"><?php 
				if (empty($maatschappelijkwerk)) {
					echo 'Laatste intake';
				} else {
					echo 'Laatste verslag';
				}
			?></th>
			<?php if (empty($maatschappelijkwerk)) {
				?>
			<th class="aantalIntakesCol"><?php echo 'Aantal intakes'; ?></th>
			<?php 
			} ?>
			<?php if (isset($bot) && $bot) {
				?>
			<th class=""><?php echo 'BOT'; ?></th>
			<?php 
			} ?>
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
					onMouseOver="this.style.cursor='pointer'" 
					onClick="location.href='<?php echo $url; ?>';"
					id='klanten_<?php echo $klant['Klant']['id']?>'>		
	<?php else:?>
		<tr class="klantenlijst-row<?php echo $altrow;?>" onMouseOver="this.style.cursor='pointer'" id='klanten_<?php echo $klant['Klant']['id']?>'>
	<?php endif;?>

			<td class="klantnrCol"><?php echo $klant['Klant']['id']; ?>&nbsp;</td>
			<td class="voornaamCol"><?= $this->Format->name1st($klant['Klant']);?>&nbsp;</td>
			<td class="achternaamCol"><?php echo $klant['Klant']['name2nd_part']; ?>&nbsp;</td>
			<td class="gebortedatumCol"><?php echo $klant['Klant']['geboortedatum']; ?>&nbsp;</td>
			<td class="geschlachtCol"><?php echo $klant['Geslacht']['afkorting']; ?>&nbsp;</td>
			<td class="1eIntakeCol"><?php
					if (isset($klant['LasteIntake']['Locatie1']['naam'])) {
						echo $klant['LasteIntake']['Locatie1']['naam'];
					} else {
						echo '-';
					}
				?>
			</td>
			<td class="2eIntakesCol"><?php
				if (isset($klant['LasteIntake']['Locatie2']['naam'])) {
					echo $klant['LasteIntake']['Locatie2']['naam'];
				} else {
						echo '-';
					}
				?>
			</td>
			<td class="3eIntakesCol"><?php
				if (isset($klant['LasteIntake']['Locatie3']['naam'])) {
					echo $klant['LasteIntake']['Locatie3']['naam'];
				} else {
						echo '-';
					}
				?>
			</td>
<?php
if (empty($maatschappelijkwerk)) {
					?>
			<td class="laatsteIntakeCol"><?php
					if (isset($klant['LasteIntake']['datum_intake'])) {
						echo $date->show($klant['LasteIntake']['datum_intake'], array('short'=>true));
					} else {
						echo '-';
					} ?>
			</td>
<?php

				} else {
					?>
			<td class="laatsteIntakeCol"><?php
					if (isset($klant['Klant']['laatste_verslag_datum'])) {
						echo $date->show($klant['Klant']['laatste_verslag_datum'], array('short'=>true));
					} else {
						echo '-';
					} ?>
			</td>

<?php

				}

?>
			
			<?php if (empty($maatschappelijkwerk)) {
	?>
			<td class=""><?php echo count($klant['Intake']); ?></td>
			<?php 
} ?>
			<?php if (isset($bot) && $bot) {
	?>
						<td class=""><?php
					if (isset($klant['BackOnTrack']['id'])) {
						echo $date->show($klant['BackOnTrack']['startdatum'], array('short'=>true));
						echo " ";
						echo $date->show($klant['BackOnTrack']['einddatum'], array('short'=>true));
					} else {
						echo '-';
					} ?>
			</td>
			<?php 
} ?>
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

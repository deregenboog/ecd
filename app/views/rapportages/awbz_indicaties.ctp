<?php
$confirm_msg = __('Aan te vragen indicatie verwijderen?', true);
?>
<div class="form">
	<fieldset>
		<legend>AWBZ-indicaties</legend>
		<fieldset>
			<h2>Aan te vragen indicaties (deze verlopen binnen 2,5 maand)</h2>

			<table>
				<tr>
					<th>#</th>
					<th>Naam (roepnaam)</th>
					<th>Startdate indicatie</th>
					<th>Einddate indicatie</th>
					<th>Actie</th>
				</tr>
				<?php 
				foreach ($indicaties as &$indicatie) {
					//if requested again then this goes to the other table
					if ($indicatie['AwbzIndicatie']['aangevraagd_id']) {
						continue;
					} ?>
					<tr>
						<td><?= $indicatie['AwbzIndicatie']['klant_id'] ?></td>
						<td>
						<?php
						echo $indicatie['Klant']['voornaam'].' ';
					if (!empty($indicatie['Klant']['roepnaam'])) {
						echo '('.$indicatie['Klant']['roepnaam'].')';
					}
					echo $indicatie[0]['name2nd_part']; ?>
					</td>
					<td><?= $date->show($indicatie['AwbzIndicatie']['begindatum']); ?></td>
					<td><?= $date->show($indicatie['AwbzIndicatie']['einddatum']); ?></td>
					<td>
						<nobr>
						<?php
							if ($indicatie['AwbzIndicatie']['aangevraagd_id']) {
								echo 'Awbz indicatie aangevraagd';
							} else {
								$ind_but_id = 'indicatie_aangevraagd_'.
										$indicatie['AwbzIndicatie']['id'];
								echo $this->Form->button(
										'Awbz indicatie<br/> aanvragen',
										array(
											'style' => 'cursor: pointer',
											'id' => $ind_but_id,
										)
								);
								$this->Js->get("#$ind_but_id")->event('click',
										$this->Js->request(
												array(
													'controller' => 'awbz_indicaties',
													'action' => 'jsonIndicationRequest',
													$indicatie['AwbzIndicatie']['id'],
												),
												array(
													'async' => false,
													'type' => 'json',
													'success' => 'update_indication_status(data,
													"' .$ind_but_id.'"
												);',
												)
										)
								);
								$delete_ind_but_id = 'indicatie_aangevraagd_niet_'.
										$indicatie['AwbzIndicatie']['id'];
								echo $this->html->image('trash.png',
									array(
										'alt' => 'Verwijderen',
										'id' => $delete_ind_but_id,
										'url' => '#',
									)
								);
								$this->Js->get("#$delete_ind_but_id")->event('click',
										'if(confirm("'.$confirm_msg.'")){'.
										$this->Js->request(
												array(
													'controller' => 'awbz_indicaties',
													'action' => 'jsonDeleteIndicationRequest',
													$indicatie['AwbzIndicatie']['id'],
												),
												array(
													'async' => false,
													'type' => 'json',
													'success' => 'delete_indication(data,
													"' .$delete_ind_but_id.'"
												);',
												)
										).'}'
								);
							} ?>
						</nobr>
					</td>
				</tr>
				<?php 
				} ?>
			</table>

			<h2>Aangevraagde indicaties</h2>
			<table id="tableAppliedIndications">
				<tr>
					<th>#</th>
					<th>Naam (roepnaam)</th>
					<th>Datum van aanvraag</th>
					<th>Aangevraagd door</th>
					<th>Actie</th>
				</tr>
				<?php
				foreach ($indicaties as &$indicatie) {
					//if NOT requested again then this goes to the other table
					if (! $indicatie['AwbzIndicatie']['aangevraagd_id']) {
						continue;
					} ?>
					<tr>
						<td><?= $indicatie['AwbzIndicatie']['klant_id'] ?></td>
						<td>
						<?php
						echo $indicatie['Klant']['voornaam'].' ';
					if (!empty($indicatie['Klant']['roepnaam'])) {
						echo '('.$indicatie['Klant']['roepnaam'].')';
					}
					echo $indicatie[0]['name2nd_part']; ?>
					</td>
					<td><?= $date->show($indicatie['AwbzIndicatie']['aangevraagd_datum']) ?></td>
					<td><?= $indicatie[0]['aangevraagd_naam']; ?></td>
					<td>
						<?= $html->link('Indicatie invoeren', array(
							'controller' => 'awbz',
							'action' => 'view',
							$indicatie['AwbzIndicatie']['klant_id'],
						)); ?>
					</td>
				</tr>
				<?php 
				} ?>
				</table>
			</fieldset>
		</fieldset>
	</div>

	<div class="actions">
		<fieldset>
			<legend>Rapportageopties</legend>
		<?php
			echo $this->Form->create('options', array(
				'type' => 'post',
				'url' => array(
					'controller' => 'Rapportages',
					'action' => $this->action,
				),
			));
			$radio_options = array(
				'legend' => __('Sex', true),
				'type' => 'radio',
				'options' => array(
					1 => __('Men', true),
					2 => __('Women', true),
					0 => __('Men and women', true),
				),
			);

			if (empty($this->data)) {
				$radio_options['default'] = 0;
			}

			echo $this->Form->input('geslacht_id', $radio_options);
		?>

<?php echo $form->end(array('label' => 'Ga')); ?>
	</fieldset>
</div>

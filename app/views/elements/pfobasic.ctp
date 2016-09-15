<fieldset>
	<legend>Basisgegevens</legend>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>Roepnaam</td>
			<td><?php echo $data['PfoClient']['roepnaam']; ?></td>
		</tr>
		<tr>
			<td>Tussenvoegsel</td>
			<td><?php echo $data['PfoClient']['tussenvoegsel']; ?></td>
		</tr>
		<tr>
			<td>Achternaam</td>
			<td><?php echo $data['PfoClient']['achternaam']; ?></td>
		</tr>
		<tr>
			<td>Geslacht</td>
			<td><?php echo $data['Geslacht']['volledig']; ?></td>
		</tr>
		<tr>
			<td>Geboortedatum</td>
			<td><?php echo $date->show($data['PfoClient']['geboortedatum'], array('short'=>true)); ?></td>
		</tr>
	</table>
	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => 'pfo_clienten', 'action' => 'edit', $data['PfoClient']['id']);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
		?>
	</div>
</fieldset>

<fieldset>
	<legend>Contactgegevens</legend>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>Adres</td>
			<td><?php echo h($data['PfoClient']['adres']); ?></td>
		</tr>
		<tr>
			<td>Postcode</td>
			<td><?php echo h($data['PfoClient']['postcode']); ?></td>
		</tr>
		<tr>
			<td>Woonplaats</td>
			<td><?php echo h($data['PfoClient']['woonplaats']); ?></td>
		</tr>
		<tr>
			<td>Telefoon vast</td>
			<td><?php echo h($data['PfoClient']['telefoon']); ?></td>
		</tr>
		<tr>
			<td>Telefoon mobiel</td>
			<td><?php echo h($data['PfoClient']['telefoon_mobiel']); ?></td>
		</tr>
		<tr>
			<td>E-mailadres</td>
			<td style="word-break:break-all;"><?php echo h($data['PfoClient']['email']); ?></td>
		</tr>
		<tr>
			<td>Notitie</td>
			<td><?php echo h($data['PfoClient']['notitie']); ?></td>
		</tr>
	</table>
	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => 'pfo_clienten', 'action' => 'edit', $data['PfoClient']['id']);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
		?>
	</div>
</fieldset>

<fieldset>
	<legend>Aanmeldgegevens</legend>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>Eerste contact</td>
			<td><?php echo $date->show($data['PfoClient']['created'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Medewerker</td>
			<td><?php echo $medewerkers[$data['PfoClient']['medewerker_id']]; ?>
		</tr>
			<tr>
			<td>Groep</td>
			<td>
			<?php
			if (isset($groepen[$data['PfoClient']['groep']])) {
				echo $groepen[$data['PfoClient']['groep']];
			} else {
				echo $data['PfoClient']['groep'];
			}
			?>
			</td>
		</tr>
		<tr>
			<td>Aard van de relatie</td>
			<td><?php 
				if (isset($aard_relatie[$data['PfoClient']['aard_relatie']])) {
					echo $aard_relatie[$data['PfoClient']['aard_relatie']];
				} else {
					echo $data['PfoClient']['aard_relatie'];
				}
			?>
			</td>
		</tr>
		<tr>
			<td>Dubbele diagnose?</td>
			<td>
			<?php 

				switch ($data['PfoClient']['dubbele_diagnose']) {
					case 0:
						echo 'Nee';
						break;
					case 1:
						echo 'Ja';
						break;
					case 2:
						echo 'Vermoedelijk';
						break;
				}

			?>
			</td>
		</tr>
		<tr>
			<td>Eerder hulpverlening ontvangen?</td>
			<td><?php if ($data['PfoClient']['eerdere_hulpverlening']) {
				echo 'ja';
			} else {
				echo 'nee';
			}?></td>
		</tr>
		<tr>
			<td>Via</td>
			<td><?php echo h($data['PfoClient']['via']); ?></td>
		</tr>
		<tr>
			<td>Andere betrokken hulpverleners : </td><td></td>
		</tr>
		<tr>
			<td><?php echo h($data['PfoClient']['hulpverleners']); ?></td>
		</tr>
		<tr>
			<td>Andere belangrijke contacten : </td><td></td>
		</tr>
		<tr>
			<td colspan="2"><?php echo h($data['PfoClient']['contacten']); ?></td>
		</tr>
		<tr>
			<td>Begeleidingsformulier overhandigd</td>
			<td><?php echo $date->show($data['PfoClient']['begeleidings_formulier'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Brief huisarts verstuurd</td>
			<td><?php echo $date->show($data['PfoClient']['brief_huisarts'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Evaluatieformulier overhandigd</td>
			<td><?php echo $date->show($data['PfoClient']['evaluatie_formulier'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Datum afgesloten</td>
			<td><?php echo $date->show($data['PfoClient']['datum_afgesloten'], array('short'=>true)); ?></td>
		</tr>
		</table>
	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => 'pfo_clienten', 'action' => 'edit', $data['PfoClient']['id']);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
		?>
	</div>
</fieldset>

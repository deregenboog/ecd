<fieldset>
	<legend>Basisgegevens</legend>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>Voornaam</td>
			<td><?php echo $data['Klant']['voornaam']; ?></td>
		</tr>
		<tr>
			<td>Tussenvoegsel</td>
			<td><?php echo $data['Klant']['tussenvoegsel']; ?></td>
		</tr>
		<tr>
			<td>Achternaam</td>
			<td><?php echo $data['Klant']['achternaam']; ?></td>
		</tr>
		<tr>
			<td>Roepnaam</td>
			<td><?php echo $data['Klant']['roepnaam']; ?></td>
		</tr>
		<tr>
			<td>Geslacht</td>
			<td><?php echo $data['Geslacht']['volledig']; ?></td>
		</tr>
		<tr>
			<td>Geboortedatum</td>
			<td><?php echo $date->show($data['Klant']['geboortedatum'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Geboorteland</td>
			<td><?php echo $data['Geboorteland']['land']; ?></td>
		</tr>
		<tr>
			<td>Nationaliteit</td>
			<td><?php echo $data['Nationaliteit']['naam']; ?></td>
		</tr>
		<tr>
			<td><?php __('bsn')?></td>
			<td><?php echo $data['Klant']['BSN']; ?></td>
		</tr>
		<tr>
			<td>Laatste TBC controle</td>
			<td><?php echo $date->show($data['Klant']['laatste_TBC_controle'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Medewerker</td>
			<td><?php echo $data['Medewerker']['voornaam'].' '.
				$data['Medewerker']['tussenvoegsel'].' '.
				$data['Medewerker']['achternaam']; ?></td>
		</tr>
	</table>
	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => 'klanten', 'action' => 'edit', $data['Klant']['id']);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
		?>
	</div>
</fieldset>

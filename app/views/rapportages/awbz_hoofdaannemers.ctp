
<fieldset>
	<legend>AWBZ-hoofdaannemers</legend>
	<table>
		<tr>
			<th>Hoofdaannemer</th>
			<th>Naam</th>
			<th>Geboortedatum</th>
			<th>BSN</th>
			<th>Begeleidingsuren genoten</th>
			<th>Activering genoten</th>
			<th>Activering factuur</th>
			<th>Begeleidingsuren factuur</th>
		</tr>
	<?php foreach ($indicaties as &$indicatie) {
	?>
		<tr>
			<td><?= $indicatie['Hoofdaannemer']['naam'] ?></td>
			<td>
			<?php
			echo $indicatie['Klant']['voornaam'].' ';
	if (!empty($indicatie['Klant']['roepnaam'])) {
		echo '('.$indicatie['Klant']['roepnaam'].')';
	}
	echo $indicatie['Klant']['name2nd_part']; ?>
		</td>
		<td><?= $indicatie['Klant']['geboortedatum']?></td>
		<td><?= $indicatie['Klant']['BSN']?></td>
		<td><?= $indicatie['Klant']['begeleidingsuren_genoten']; ?></td>
		<td></td>
		<td><?= $indicatie['Klant']['activering_genoten']; ?></td>
		<td></td>
	</tr>
	<?php 
} ?>
	</table>
</fieldset>

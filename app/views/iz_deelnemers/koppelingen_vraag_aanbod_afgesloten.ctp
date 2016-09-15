<?php 

$label = "undefined";

switch ($persoon_model) {
	case 'Klant':
		$label = "Afgesloten vraag";
		break;
	case 'Vrijwilliger':
		$label = "Afgesloten aanbod";
}

$wrench = $html->image('lock_open.png');
$opts = array('class' => 'unlock_koppeling', 'escape' => false, 'title' => __('edit', true));

?>
<h2 style="color: gray;"><?= $label; ?></h2>

<table>
	<thead>
	<tr><th>Project</th><th>Startdatum</th><th>Einddatum</th><th>Verslag</th><th>Reden afsluiting</th></tr><tr>
	</tr></thead>
	<tbody>
<?php foreach ($koppelingen as $key => $koppeling) {
	
	$url_openen = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'koppeling_openen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']), true);

	$url_verslag = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'verslagen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']), true);
	
	if ($koppeling['IzKoppeling']['section'] != 1) {
		continue;
	} 
?>
	<tr>
	<td>
<?php
	if (isset($projects[$koppeling['IzKoppeling']['project_id']])) {
		echo $projects[$koppeling['IzKoppeling']['project_id']];
	} else {
		echo $koppeling['IzKoppeling']['project_id'];
	} 
?>
	</td>
	<td><?= $date->show($koppeling['IzKoppeling']['startdatum'], array('short'=>true)); ?></td>
	<td><?= $date->show($koppeling['IzKoppeling']['einddatum'], array('short'=>true)); ?></td>
	<td><?= $html->link('Verslag', $url_verslag); ?></td>
	<td>
	<?= $iz_vraagaanboden[$koppeling['IzKoppeling']['iz_vraagaanbod_id']]; ?>
	<?= $html->link($wrench, $url_openen, $opts); ?>
	</td>
	</tr>
<?php 
} ?>
</tbody>
</table>

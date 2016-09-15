<?php 
$label = "undefined";

switch ($persoon_model) {
	case 'Klant':
		$label = "Afgesloten koppelingen";
		break;
	case 'Vrijwilliger':
		$label = "Afgesloten koppelingen";
}

$wrench = $html->image('lock_open.png');

?>

<h2 style="color: gray;"><?= $label; ?></h2>

<table>
	<thead>
	<tr><th>Project</th><th><?= $label_other; ?></th><th>Startdatum</th><th>Einddatum</th><th>Verslag</th><th>Succesvol</th><th>Reden afsluiting</th></tr><tr>
	</tr></thead>
	<tbody>
	
<?php foreach ($koppelingen as $key => $koppeling) {
	
	$opts = array('class' => 'unlock_koppeling', 'escape' => false, 'title' => __('edit', true));
	
	$url_openen = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'koppeling_openen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']), true);
	$url_verslag = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'verslagen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']), true);
	
	if ($koppeling['IzKoppeling']['section'] != 3) {
		continue;
	}
	
	$url = array( 'action' => 'koppelingen',  $koppeling['IzKoppeling']['iz_deelnemer_id_of_other']); ?>
	
	<tr>
	<td>
	
	<?php
	
		if (isset($projects[$koppeling['IzKoppeling']['project_id']])) {
			echo $projects[$koppeling['IzKoppeling']['project_id']];
		} else {
			echo 'onbekend';
		} 
		
	?>
	</td>
	<td><?= $html->link($koppeling['IzKoppeling']['name_of_koppeling'], $url); ?></td>
	<td><?= $date->show($koppeling['IzKoppeling']['koppeling_startdatum'], array('short'=>true)); ?></td>
	<td><?= $date->show($koppeling['IzKoppeling']['koppeling_einddatum'], array('short'=>true)); ?></td>
	<td><?= $html->link('Verslag', $url_verslag); ?></td>
	<td><?= empty($koppeling['IzKoppeling']['koppeling_succesvol']) ? 'nee' : 'ja'; ?></td>
	<td>
	<?= $iz_eindekoppelingen[$koppeling['IzKoppeling']['iz_eindekoppeling_id']]; ?>
	 
	<?= $html->link($wrench, $url_openen, $opts); ?>
	</td>
	</tr>
<?php 
} ?>
</tbody>
</table>

<?php

$this->Js->buffer(<<<EOS

$('.alerturl').each().change(function(event){
	console.log('in here');
});

EOS
);

?>


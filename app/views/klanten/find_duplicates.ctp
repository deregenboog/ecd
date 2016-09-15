<?php
$klantLinkTpl = $this->Html->link('%1$s', array(
	'controller' => 'klanten',
	'action' => 'view',
	'%2$s',
), array( 'target' => 'klant' )) ;
$mergeLinkTpl = $this->Html->link('Samenvoegen', array(
	'controller' => 'klanten',
	'action' => 'merge',
	'ids' => '%1$s',
), array( 'target' => 'merge' )) ;

echo $this->Html->link('Terug naar opties', array(
	'controller' => 'klanten',
		'action' => 'findDuplicates',
			));

?>
<h2>Lijst van mogelijk dubbele invoer (<?php echo __($mode) ?>)</h2>

	<table id="clientList" class="index filtered">
	<tr>
			<th>Nr.</th>
			<th>Afgestemd</th>
			<th>Aantal</th>
			<th>Namen</th>
			<th>Samenvoegen</th>
	</tr>
	<?php

	$i = 0;
	foreach ($duplicates as $dup):
		$klanten = array();
		$klantIds = array();
		foreach ($dup['klanten'] as $klant) {
			$klanten[] = sprintf($klantLinkTpl, $klant[1], $klant[0]);
			$klantIds[] = $klant[0];
		}
		$mergeLink = sprintf($mergeLinkTpl, implode(',', $klantIds));
	?>
		<tr>
			<td><?= ++$i ?>.</td>
			<td><?= $dup['match'] ?>&nbsp;</td>
			<td><?= count($klanten) ?>&nbsp;</td>
			<td><?= implode(', ', $klanten) ?>&nbsp;</td>
			<td><?= $mergeLink ?>&nbsp;</td>
		</tr>
	<?php endforeach; ?>
	</table>
	

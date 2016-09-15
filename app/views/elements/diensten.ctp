<?php
if (!empty($persoon_model) && $persoon_model != 'Klant') {
	return;
}
?>
<fieldset >
	<legend>Diensten</legend>
	<table cellpadding="0" cellspacing="0">
<?php 
	if (!empty($diensten)) {
		foreach ($diensten as $dienst) {
			$value = $dienst['value'];
			if ($dienst['type']=='date') {
				$value="";
				if (empty($dienst['to'])) {
					$value.="sinds ";
				}
				$value.=$date->show($dienst['from'], array('short'=>true))." ";
				if (! empty($dienst['to'])) {
					$value.="tot ";
					$value.=$date->show($dienst['to'], array('short'=>true))." ";
				}
			}
			$link = $dienst['name'];
			if (! empty($dienst['url'])) {
				$link = $this->Html->link($dienst['name'], $dienst['url']);
			} ?>
		<tr>
			<td><?= $link ?></td>
			<td><?= $value ?></td>
		</tr>
			 <?php

		}
	} else {
		__('Geen diensten');
	}
?>
</table>

</fieldset>

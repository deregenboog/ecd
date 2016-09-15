<fieldset>
	<legend>Gegevens Back On Track</legend>
		<div>
		<?php 
			$url = array('controller' => 'back_on_track', 'action' => 'view', 'klant_id' => $data['Klant']['id']);
			echo $html->link('Verslagen', $url);
		?>
		<br/><br/>
	</div>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>Datum aanmelding</td>
			<td><?php echo $date->show($data['BackOnTrack']['startdatum'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Datum intake</td>
			<td><?php echo $date->show($data['BackOnTrack']['intakedatum'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Einddatum</td>
			<td><?php echo $date->show($data['BackOnTrack']['einddatum'], array('short'=>true)); ?></td>
		</tr>
	</table>
	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => 'back_on_track', 'action' => 'edit', 'klant_id' => $data['Klant']['id']);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
		?>
	</div>
	<?php if (!empty($data['BackOnTrack']['id'])) {
			?>
	<br/>
		<table cellpadding="0" cellspacing="0">
		<tr>
			<th>Coach</th>
			<th>Start</th>
			<th>Einde</th>
		</tr>
		<?php
		foreach ($data['BackOnTrack']['BotKoppeling'] as $bot) {
			?>
		<tr>
			<td><?= $viewmedewerkers[$bot['medewerker_id']] ?></td>
			<td><?=  $date->show($bot['startdatum'], array('short'=>true)) ?></td>
			<td><?=  $date->show($bot['einddatum'], array('short'=>true)) ?></td>
		</tr>
		<?php

		} ?>
	</table>
	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => 'bot_koppelingen', 'action' => 'add', 'back_on_track_id' => $data['BackOnTrack']['id'], 'klant_id' => $data['Klant']['id']);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts); ?>
	</div>
	<?php 
		} ?>
</fieldset>

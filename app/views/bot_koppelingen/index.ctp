<div class="actions">

<?php 
	echo $this->element('klantbasic', array('data' => $klant));
	echo  $this->element('bot_documents', array('data' => $klant, 'group' => Attachment::GROUP_BTO));
	echo $this->element('back_on_track', array('data' => $klant));
?>
</div>

<div class="backOnTracks view">

<div class="backOnTrack form">

	<fieldset>
		<legend><?php __('Koppelingen'); ?></legend>
		<table cellpadding="0" cellspacing="0">
		<thead>
				<tr><th>Coach</th>
				<th>Start</th>
				<th>Einde</th>
				<th></th>
				<th/>
		</tr></thead>
		<tbody>
		
<?php
	if (empty($bot_koppelingen)) {
		echo "<tr><td>&nbsp;</td><td/><td/><td/></tr>";
	}
	foreach ($bot_koppelingen as $k) {
?>
				<tr>
						<td><?= $viewmedewerkers[$k['BotKoppeling']['medewerker_id']]?></td>
						<td><?=  $date->show($k['BotKoppeling']['startdatum'], array('short'=>true)) ?></td>
						<td><?=  $date->show($k['BotKoppeling']['einddatum'], array('short'=>true))  ?></td>
						<td>
						<div class="editWrench">
<?php 
	$wrench = $html->image('wrench.png');
	$url = array(
		'controller' => 'bot_koppelingen',
		'action' => 'edit',
		$k['BotKoppeling']['id'],
	);
	$opts = array('escape' => false, 'title' => __('edit', true));
	echo $html->link($wrench, $url, $opts); 
?>
						</div>
						</td>
						<td>
<?php 
	$wrench = $html->image('x.png');
	$url = array(
		'controller' => 'bot_koppelingen',
		'action' => 'delete',
		$k['BotKoppeling']['id'],
	);
	$opts = array('escape' => false, 'title' => __('edit', true));
	echo $html->link($wrench, $url, $opts); 
?>
						</td>
				</tr>
<?php } ?>
			
		</tbody>
</table>

<?php 
	$action = 'add';
	
	if ($this->action == 'edit') {
		$action = 'edit';
	}
	
	$url = array('action' => $action);
	
	echo $this->Form->create('BotKoppeling', array('url' => $url));

    $msg = __('Koppeling toevoegen', true);
	
	if ($action == 'edit') {
        $msg = __('Wijzig bestaande koppeling', true);
	}
?>
			<legend><?= $msg; ?></legend>
			<div class="leftDiv">
<?php
	echo $this->Form->hidden('id');
	
	echo $this->Form->hidden('klant_id');
	
	echo $this->Form->hidden('back_on_track_id');
	
	echo $this->Form->input('medewerker_id', array('label' => __('Coach')));
	
	echo $date->input('BotKoppeling.startdatum', null, array(
		'label' => 'Start datum',
	));
	
	echo $date->input('BotKoppeling.einddatum', null, array(
		'label' => 'Eind datum',
	));
?>
	</div>
	
<?php 
	echo $this->Form->end(__('Submit', true));
	
	$url = array('controller' => 'BackOnTrack', 'action' => 'view', $klant['Klant']['id']);
	
	echo $this->Html->link(_('Cancel'), $url);
	
?>
	</fieldset>
</div>

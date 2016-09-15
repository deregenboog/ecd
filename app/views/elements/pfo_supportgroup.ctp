<table>
	<tbody>
		<tr>
			<td style='width:40%'>
				<?php echo 'Hoofdclient'; ?>
			</td>
			<td>

<?php

	echo $this->Form->checkbox('controll.hoofdclient_toggle', array(
				'label' => 'Hoofdclient',
				'id' => 'hoofdclient_toggle',
				'checked' => $hoofdclient,
			));

?>

			</td>
		</tr>

<?php

	if ($hoofdclient) {
		$hoofdclient_vissible = 'visible';
		$supportgroup_vissible = 'none';
	} else {
		$hoofdclient_vissible = 'none';
		$supportgroup_vissible = 'visible';
	}

	$supportgroup = array();
	if (isset($pfoClient['PfoClientenSupportgroup'])) {
		foreach ($pfoClient['PfoClientenSupportgroup'] as $sg) {
			$supportgroup[$sg['pfo_supportgroup_client_id']] = $clienten[$sg['pfo_supportgroup_client_id']];
		}
	}

	$clienten = array('' => '' ) + $clienten;
	$vrije_clienten = array('' => '' ) + $vrije_clienten;
	$hoofd_clienten = array('' => '' ) + $hoofd_clienten;
	foreach ($clienten as $key => $value) {
		if (isset($pfoClient['PfoClient']) && $key == $pfoClient['PfoClient']['id']) {
			unset($clienten[$key]);
		}
	}

	foreach ($vrije_clienten as $key => $value) {
		if (isset($pfoClient['PfoClient']) && $key == $pfoClient['PfoClient']['id']) {
			unset($vrije_clienten[$key]);
		}
	}

	foreach ($hoofd_clienten as $key => $value) {
		if (isset($pfoClient['PfoClient']) && $key == $pfoClient['PfoClient']['id']) {
			unset($hoofd_clienten[$key]);
		}
	}

?>

<tr class="section_supportclient" style='display: <?= $supportgroup_vissible; ?>'>
	<td>
<?php
		echo $this->Form->label('SupportClient.pfo_client_id', 'Gekoppeld aan');
?>
	</td>
	<td>
<?php 

	$value = null;
	if (isset($pfoClient['SupportClient']['pfo_client_id'])) {
		$value = $pfoClient['SupportClient']['pfo_client_id'];
	}

	echo $this->Form->input(
		'SupportClient.pfo_client_id', array(
		'label' => false,
		'type' => 'select',
		'options' => $hoofd_clienten,
		'value' => $value,
	));

?>
			</td>
		</tr>
<tr class='section_supportclient' id="supportgroup" style='display: <?= $supportgroup_vissible?>'>
	<td style='width:40%'> 
<?php
	if (isset($pfoClient['hoofd_client_id']) and isset($clienten[$pfoClient['hoofd_client_id']])) {
		echo "Andere leden gekoppeld aan ".$clienten[$pfoClient['hoofd_client_id']].": ";
	}
?>
		</td>
		<td>
<?php

	if (isset($pfoClient['hoofd_client_id']) and isset($clienten[$pfoClient['hoofd_client_id']])) {
		$msg = "";
		if (isset($pfoClient['CompleteGroup'])) {
			foreach ($pfoClient['CompleteGroup'] as $sg_id) {

				if ($pfoClient['hoofd_client_id'] == $sg_id) {
					continue;
				}
				if ($pfoClient['PfoClient']['id'] == $sg_id) {
					continue;
				}
				if ($msg != "") {
					$msg .= ", ";
				}
				$msg .= $clienten[$sg_id];
			}
		}
		echo $msg;
	}
?>

	</td>
</tr>


 

<tr class='section_hoofdclient' style='display: <?= $hoofdclient_vissible?>'>
	<td>Gekoppelde clienten:</td>
	<td id="selected_list" ></td>
</tr>
<tr class='section_hoofdclient' style='display: <?= $hoofdclient_vissible?>'>
	<td>
<?php

echo $this->Form->label('select_clienten', 'Koppel client');
?>
</td>
	<td>
<?php

echo $this->Form->input('select_clienten', array(
	'label' => false,
	'id' => 'select_clienten',
	'name' => 'data[clienten]',
	'options' => $vrije_clienten,
));

$peOptions = array(
	'selected' => $supportgroup,
	'html' => 'addsupport',
	'dropdown' => 'select_clienten',
	'remove' => $html->image('delete.png'),
);

$this->Js->buffer(
		'Ecd.supportgroup_list.options = '.json_encode($peOptions)
);
$this->Js->buffer('Ecd.supportgroup_list();');

?>
</td>
</tr>

   </tbody>
</table>

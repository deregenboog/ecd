<fieldset class="fix-size">
	<legend>Gegevens traject-/<br/>werkbegeleiding</legend>
	<div class="vscroll">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td>Trajectbegeleider</td>
				<td><?php
				if (isset($data['Traject']['Trajectbegeleider']['name'])) {
					echo $data['Traject']['Trajectbegeleider']['name'];
				}
				?></td>
			</tr>
			<tr>
				<td>Werkbegeleider</td>
				<td><?php
				if (isset($data['Traject']['Werkbegeleider']['name'])) {
					echo $data['Traject']['Werkbegeleider']['name'];
				} ?></td>
			</tr>
			<tr>
				<td>Telnr. Client</td>
				<td><?php echo $data['Traject']['klant_telefoonnummer']; ?></td>
			</tr>
			<tr>
				<td>Administratie DWI</td>
				<td><?php echo $data['Traject']['administratienummer']; ?></td>
			</tr>
			<tr>
				<td>Klantmanager DWI</td>
				<td><?php echo $data['Traject']['klantmanager']; ?></td>
			</tr>
			<tr>
				<td>Telnr. Klantmanager</td>
				<td><?php echo $data['Traject']['manager_telefoonnummer']; ?></td>
			</tr>
			<tr>
				<td>e-Mail Klantmanager</td>
				<td><?php echo $data['Traject']['manager_email']; ?></td>
			</tr>
		</table>
	</div>
	<div class="editWrench">
			<?php 
				$wrench = $html->image('wrench.png');
				$url = array('controller' => 'hi5',
						'action' => 'wijzigen_traject',
						$data['Klant']['id'],
				);
				$opts = array(
						'escape' => false,
						'title' => __('edit', true),
				);
				echo $html->link($wrench, $url, $opts);
			?>
	</div>
</fieldset>

<br/>
<fieldset class="data" id="activiteiten" style="display: block;">

		<h2 style="display: inline-block;">Activiteiten</h2>
		
		<table>
		<tr>
		<td></td>
		<td>
			<?= $this->Form->create('Groepsactiviteit', array('url' => array($persoon_model, $id))); ?>
			<?= $this->Form->input('groepsactiviteit_id', array(
				'label' => 'Toevoegen aan activiteit',
				'type' => 'select',
				'style' => 'width: 400px;',
				'options' => $newgroepsactiviteiten, ));
			?>
			<?= $this->Form->submit(); ?>
		</td>
		</tr>
		</table>
		
		<table>
			<?php
				$url = $this->Html->url(array(
					'controller' => 'Groepsactiviteiten',
					'action' => 'activiteiten',
					$persoon_model,
					$id,
					'export' => 1,
				));
			?>
			<caption><a href="<?=$url ?>" title="Download als CSV-bestand">Download</a></caption>
			<thead>
				<tr>
					<td>Datum</td><td>Groep</td><td>Activiteit</td><td>Status</td>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($activiteiten as $activiteit) {
				?>
				<tr><td><?= $date->show($activiteit['Groepsactiviteit']['datum']) ?></td>
				<td><?= $groepsactiviteitengroepen_list_view[$activiteit['Groepsactiviteit']['groepsactiviteiten_groep_id']] ?></td>
				<td><?= $activiteit['Groepsactiviteit']['naam'] ?></td>
				<td><?= $activiteit[$persoon_groepsactiviteiten]['afmeld_status'] ?></td></tr>
			<?php 
			} ?>
			</tbody>
		</table>
		
</fieldset>

<?php
$list = "";
foreach ($activiteiten as $activiteit) {
	if (!empty($list)) {
		$list.=",";
	}
	$list.=$activiteit['Groepsactiviteit']['id'];
}

$this->Js->buffer(<<<EOS
	a=[{$list}];
	var l = a.length;
	for (var i = 0; i < l; i++) {
		$('#GroepsactiviteitGroepsactiviteitId').find("option[value='"+a[i].toString()+"']").attr('disabled','disabled');
	}
EOS
);

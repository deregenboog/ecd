
<div id="contentForIndex">
	<?php
	if ($showForm) {
		echo $this->Form->create('Rapportage',
			array('url' => array('controller'=>$this->name, 'action' => 'rapportage') )
		); ?>
		<fieldset><legend><?php __('AWBZ Report Information')?></legend>
		<?php
			$today = date('Y-m-d');
		echo $form->month('month', date('m'));
		echo $form->year('year', date('Y') -2, date('Y'), date('Y')); ?>
		</fieldset>
		<?php
		echo $this->Form->end(__('Submit', true));
	} else {
		?><h2><?php __('AWBZ Facturering')?></h2>
<table
	id="clientList"
	class="indsdex filtefasdred"
>
	<thead>
		<tr>
			<th><?php __("Hoofdaannemers"); ?></th>
			<th><?php __("Naam"); ?></th>
			<th><?php __("Geboortedatum"); ?></th>
			<th><?php __("BSN"); ?></th>
			<th><?php __("Begeleidingsuren genoten"); ?></th>
			<th><?php __("Begeleidingsuren factuur"); ?></th>
			<th><?php __("Activering genoten"); ?></th>
			<th><?php __("Activering factuur"); ?></th>
		</tr>
	</thead>
	<tbody>

	<?php foreach ($reportData as $reportRow) {
			?>
		<tr>
			<td><?= $reportRow['hoofdaannemers']['naam'] ?></td>
			
			<td><?= $reportRow['klanten']['voornaam'] ?> <?= $reportRow['klanten']['tussenvoegsel'] ?> <?= $reportRow['klanten']['achternaam'] ?></td>
			
			<td><?= $date->show($reportRow['klanten']['geboortedatum']) ?></td>
			
			<td><?= ! empty($reportRow['klanten']['BSN']) ? $reportRow['klanten']['BSN'] : '-' ?></td>

			<td><?= $reportRow[0]['verslag_also_regisered'] + $reportRow[0]['verslag_other_location'] ?></td>

			<td><?= min($reportRow[0]['verslag_also_regisered'] + $reportRow[0]['verslag_other_location'], $reportRow[0]['begeleiding']) ?></td>

			<td>0</td>
			
			<td>0</td>
		</tr>
	<?php

		} ?>
	</tbody>
</table>
	<?php 
	}
	?>
</div>


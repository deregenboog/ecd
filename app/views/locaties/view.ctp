<div class="locaties view">
<h2><?php  __('Locatie');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $locatie['Locatie']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Naam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $locatie['Locatie']['naam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Datum Van'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $locatie['Locatie']['datum_van']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Datum Tot'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $locatie['Locatie']['datum_tot']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $locatie['Locatie']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $locatie['Locatie']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Locatie', true), array('action' => 'edit', $locatie['Locatie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Locatie', true), array('action' => 'delete', $locatie['Locatie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $locatie['Locatie']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Locaties', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Locatie', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Registraties', true), array('controller' => 'registraties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Registratie', true), array('controller' => 'registraties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Schorsingen', true), array('controller' => 'schorsingen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Schorsing', true), array('controller' => 'schorsingen', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Intakes', true), array('controller' => 'intakes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Intake1', true), array('controller' => 'intakes', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Registraties');?></h3>
	<?php if (!empty($locatie['Registratie'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Locatie Id'); ?></th>
		<th><?php __('Klant Id'); ?></th>
		<th><?php __('Binnen'); ?></th>
		<th><?php __('Buiten'); ?></th>
		<th><?php __('Douche'); ?></th>
		<th><?php __('Kleding'); ?></th>
		<th><?php __('Maaltijd'); ?></th>
		<th><?php __('Activering'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($locatie['Registratie'] as $registratie):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $registratie['id'];?></td>
			<td><?php echo $registratie['locatie_id'];?></td>
			<td><?php echo $registratie['klant_id'];?></td>
			<td><?php echo $registratie['binnen'];?></td>
			<td><?php echo $registratie['buiten'];?></td>
			<td><?php echo $registratie['douche'];?></td>
			<td><?php echo $registratie['kleding'];?></td>
			<td><?php echo $registratie['maaltijd'];?></td>
			<td><?php echo $registratie['activering'];?></td>
			<td><?php echo $registratie['created'];?></td>
			<td><?php echo $registratie['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'registraties', 'action' => 'view', $registratie['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'registraties', 'action' => 'edit', $registratie['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'registraties', 'action' => 'delete', $registratie['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $registratie['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Registratie', true), array('controller' => 'registraties', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Schorsingen');?></h3>
	<?php if (!empty($locatie['Schorsing'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Datum Van'); ?></th>
		<th><?php __('Datum Tot'); ?></th>
		<th><?php __('Locatie Id'); ?></th>
		<th><?php __('Klant Id'); ?></th>
		<th><?php __('Remark'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($locatie['Schorsing'] as $schorsing):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $schorsing['id'];?></td>
			<td><?php echo $schorsing['datum_van'];?></td>
			<td><?php echo $schorsing['datum_tot'];?></td>
			<td><?php echo $schorsing['locatie_id'];?></td>
			<td><?php echo $schorsing['klant_id'];?></td>
			<td><?php echo $schorsing['remark'];?></td>
			<td><?php echo $schorsing['created'];?></td>
			<td><?php echo $schorsing['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'schorsingen', 'action' => 'view', $schorsing['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'schorsingen', 'action' => 'edit', $schorsing['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'schorsingen', 'action' => 'delete', $schorsing['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $schorsing['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Schorsing', true), array('controller' => 'schorsingen', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Intakes');?></h3>
	<?php if (!empty($locatie['Intake1'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Klant Id'); ?></th>
		<th><?php __('Medewerker Id'); ?></th>
		<th><?php __('Datum Invoer'); ?></th>
		<th><?php __('Datum Intake'); ?></th>
		<th><?php __('Verblijfstatus Id'); ?></th>
		<th><?php __('Postadres'); ?></th>
		<th><?php __('Postcode'); ?></th>
		<th><?php __('Woonplaats'); ?></th>
		<th><?php __('Verblijf In NL Sinds'); ?></th>
		<th><?php __('Verblijf In Amsterdam Sinds'); ?></th>
		<th><?php __('Legitimatie Id'); ?></th>
		<th><?php __('Legitimatie Nummer'); ?></th>
		<th><?php __('Legitimatie Geldig Tot'); ?></th>
		<th><?php __('Verslavingsfrequentie Id'); ?></th>
		<th><?php __('Verslavingsperiode Id'); ?></th>
		<th><?php __('Woonsituatie Id'); ?></th>
		<th><?php __('Verwachting Dienstaanbod'); ?></th>
		<th><?php __('Toekomstplannen'); ?></th>
		<th><?php __('Opmerking Andere Instanties'); ?></th>
		<th><?php __('Medische Achtergrond'); ?></th>
		<th><?php __('Laatste TBC Controle'); ?></th>
		<th><?php __('Locatie1 Id'); ?></th>
		<th><?php __('Locatie2 Id'); ?></th>
		<th><?php __('Indruk'); ?></th>
		<th><?php __('Doelgroep'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($locatie['Intake1'] as $intake1):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $intake1['id'];?></td>
			<td><?php echo $intake1['klant_id'];?></td>
			<td><?php echo $intake1['medewerker_id'];?></td>
			<td><?php echo $intake1['datum_invoer'];?></td>
			<td><?php echo $intake1['datum_intake'];?></td>
			<td><?php echo $intake1['verblijfstatus_id'];?></td>
			<td><?php echo $intake1['postadres'];?></td>
			<td><?php echo $intake1['postcode'];?></td>
			<td><?php echo $intake1['woonplaats'];?></td>
			<td><?php echo $intake1['verblijf_in_NL_sinds'];?></td>
			<td><?php echo $intake1['verblijf_in_amsterdam_sinds'];?></td>
			<td><?php echo $intake1['legitimatie_id'];?></td>
			<td><?php echo $intake1['legitimatie_nummer'];?></td>
			<td><?php echo $intake1['legitimatie_geldig_tot'];?></td>
			<td><?php echo $intake1['verslavingsfrequentie_id'];?></td>
			<td><?php echo $intake1['verslavingsperiode_id'];?></td>
			<td><?php echo $intake1['woonsituatie_id'];?></td>
			<td><?php echo $intake1['verwachting_dienstaanbod'];?></td>
			<td><?php echo $intake1['toekomstplannen'];?></td>
			<td><?php echo $intake1['opmerking_andere_instanties'];?></td>
			<td><?php echo $intake1['medische_achtergrond'];?></td>
			<td><?php echo $intake1['laatste_TBC_controle'];?></td>
			<td><?php echo $intake1['locatie1_id'];?></td>
			<td><?php echo $intake1['locatie2_id'];?></td>
			<td><?php echo $intake1['indruk'];?></td>
			<td><?php echo $intake1['doelgroep'];?></td>
			<td><?php echo $intake1['created'];?></td>
			<td><?php echo $intake1['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'intakes', 'action' => 'view', $intake1['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'intakes', 'action' => 'edit', $intake1['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'intakes', 'action' => 'delete', $intake1['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $intake1['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Intake1', true), array('controller' => 'intakes', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Intakes');?></h3>
	<?php if (!empty($locatie['Intake2'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Klant Id'); ?></th>
		<th><?php __('Medewerker Id'); ?></th>
		<th><?php __('Datum Invoer'); ?></th>
		<th><?php __('Datum Intake'); ?></th>
		<th><?php __('Verblijfstatus Id'); ?></th>
		<th><?php __('Postadres'); ?></th>
		<th><?php __('Postcode'); ?></th>
		<th><?php __('Woonplaats'); ?></th>
		<th><?php __('Verblijf In NL Sinds'); ?></th>
		<th><?php __('Verblijf In Amsterdam Sinds'); ?></th>
		<th><?php __('Legitimatie Id'); ?></th>
		<th><?php __('Legitimatie Nummer'); ?></th>
		<th><?php __('Legitimatie Geldig Tot'); ?></th>
		<th><?php __('Verslavingsfrequentie Id'); ?></th>
		<th><?php __('Verslavingsperiode Id'); ?></th>
		<th><?php __('Woonsituatie Id'); ?></th>
		<th><?php __('Verwachting Dienstaanbod'); ?></th>
		<th><?php __('Toekomstplannen'); ?></th>
		<th><?php __('Opmerking Andere Instanties'); ?></th>
		<th><?php __('Medische Achtergrond'); ?></th>
		<th><?php __('Laatste TBC Controle'); ?></th>
		<th><?php __('Locatie1 Id'); ?></th>
		<th><?php __('Locatie2 Id'); ?></th>
		<th><?php __('Indruk'); ?></th>
		<th><?php __('Doelgroep'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($locatie['Intake2'] as $intake2):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $intake2['id'];?></td>
			<td><?php echo $intake2['klant_id'];?></td>
			<td><?php echo $intake2['medewerker_id'];?></td>
			<td><?php echo $intake2['datum_invoer'];?></td>
			<td><?php echo $intake2['datum_intake'];?></td>
			<td><?php echo $intake2['verblijfstatus_id'];?></td>
			<td><?php echo $intake2['postadres'];?></td>
			<td><?php echo $intake2['postcode'];?></td>
			<td><?php echo $intake2['woonplaats'];?></td>
			<td><?php echo $intake2['verblijf_in_NL_sinds'];?></td>
			<td><?php echo $intake2['verblijf_in_amsterdam_sinds'];?></td>
			<td><?php echo $intake2['legitimatie_id'];?></td>
			<td><?php echo $intake2['legitimatie_nummer'];?></td>
			<td><?php echo $intake2['legitimatie_geldig_tot'];?></td>
			<td><?php echo $intake2['verslavingsfrequentie_id'];?></td>
			<td><?php echo $intake2['verslavingsperiode_id'];?></td>
			<td><?php echo $intake2['woonsituatie_id'];?></td>
			<td><?php echo $intake2['verwachting_dienstaanbod'];?></td>
			<td><?php echo $intake2['toekomstplannen'];?></td>
			<td><?php echo $intake2['opmerking_andere_instanties'];?></td>
			<td><?php echo $intake2['medische_achtergrond'];?></td>
			<td><?php echo $intake2['laatste_TBC_controle'];?></td>
			<td><?php echo $intake2['locatie1_id'];?></td>
			<td><?php echo $intake2['locatie2_id'];?></td>
			<td><?php echo $intake2['indruk'];?></td>
			<td><?php echo $intake2['doelgroep'];?></td>
			<td><?php echo $intake2['created'];?></td>
			<td><?php echo $intake2['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'intakes', 'action' => 'view', $intake2['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'intakes', 'action' => 'edit', $intake2['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'intakes', 'action' => 'delete', $intake2['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $intake2['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Intake2', true), array('controller' => 'intakes', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

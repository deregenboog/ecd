<div class="medewerkers view">
<h2><?php  __('Medewerker');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Ldapid'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['ldapid']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Voornaam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['voornaam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Tussenvoegsel'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['tussenvoegsel']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Achternaam'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['achternaam']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Eerste Bezoek'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['eerste_bezoek']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Laatste Bezoek'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['laatste_bezoek']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) {
	echo $class;
}?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) {
	echo $class;
}?>>
			<?php echo $medewerker['Medewerker']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Medewerker', true), array('action' => 'edit', $medewerker['Medewerker']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Medewerker', true), array('action' => 'delete', $medewerker['Medewerker']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $medewerker['Medewerker']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Medewerkers', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Medewerker', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Intakes', true), array('controller' => 'intakes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Intake', true), array('controller' => 'intakes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Notities', true), array('controller' => 'notities', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Notitie', true), array('controller' => 'notities', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Intakes');?></h3>
	<?php if (!empty($medewerker['Intake'])):?>
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
		foreach ($medewerker['Intake'] as $intake):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $intake['id'];?></td>
			<td><?php echo $intake['klant_id'];?></td>
			<td><?php echo $intake['medewerker_id'];?></td>
			<td><?php echo $intake['datum_invoer'];?></td>
			<td><?php echo $intake['datum_intake'];?></td>
			<td><?php echo $intake['verblijfstatus_id'];?></td>
			<td><?php echo $intake['postadres'];?></td>
			<td><?php echo $intake['postcode'];?></td>
			<td><?php echo $intake['woonplaats'];?></td>
			<td><?php echo $intake['verblijf_in_NL_sinds'];?></td>
			<td><?php echo $intake['verblijf_in_amsterdam_sinds'];?></td>
			<td><?php echo $intake['legitimatie_id'];?></td>
			<td><?php echo $intake['legitimatie_nummer'];?></td>
			<td><?php echo $intake['legitimatie_geldig_tot'];?></td>
			<td><?php echo $intake['verslavingsfrequentie_id'];?></td>
			<td><?php echo $intake['verslavingsperiode_id'];?></td>
			<td><?php echo $intake['woonsituatie_id'];?></td>
			<td><?php echo $intake['verwachting_dienstaanbod'];?></td>
			<td><?php echo $intake['toekomstplannen'];?></td>
			<td><?php echo $intake['opmerking_andere_instanties'];?></td>
			<td><?php echo $intake['medische_achtergrond'];?></td>
			<td><?php echo $intake['laatste_TBC_controle'];?></td>
			<td><?php echo $intake['locatie1_id'];?></td>
			<td><?php echo $intake['locatie2_id'];?></td>
			<td><?php echo $intake['indruk'];?></td>
			<td><?php echo $intake['doelgroep'];?></td>
			<td><?php echo $intake['created'];?></td>
			<td><?php echo $intake['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'intakes', 'action' => 'view', $intake['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'intakes', 'action' => 'edit', $intake['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'intakes', 'action' => 'delete', $intake['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $intake['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Intake', true), array('controller' => 'intakes', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Klanten');?></h3>
	<?php if (!empty($medewerker['Klant'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Klantnr'); ?></th>
		<th><?php __('MezzoID'); ?></th>
		<th><?php __('Voornaam'); ?></th>
		<th><?php __('Tussenvoegsel'); ?></th>
		<th><?php __('Achternaam'); ?></th>
		<th><?php __('Roepnaam'); ?></th>
		<th><?php __('Geslacht Id'); ?></th>
		<th><?php __('Geboortedatum'); ?></th>
		<th><?php __('Land Id'); ?></th>
		<th><?php __('Nationaliteit Id'); ?></th>
		<th><?php __('BSN'); ?></th>
		<th><?php __('Medewerker Id'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($medewerker['Klant'] as $klant):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $klant['id'];?></td>
			<td><?php echo $klant['klantnr'];?></td>
			<td><?php echo $klant['MezzoID'];?></td>
			<td><?php echo $klant['voornaam'];?></td>
			<td><?php echo $klant['tussenvoegsel'];?></td>
			<td><?php echo $klant['achternaam'];?></td>
			<td><?php echo $klant['roepnaam'];?></td>
			<td><?php echo $klant['geslacht_id'];?></td>
			<td><?php echo $klant['geboortedatum'];?></td>
			<td><?php echo $klant['land_id'];?></td>
			<td><?php echo $klant['nationaliteit_id'];?></td>
			<td><?php echo $klant['BSN'];?></td>
			<td><?php echo $klant['medewerker_id'];?></td>
			<td><?php echo $klant['created'];?></td>
			<td><?php echo $klant['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'klanten', 'action' => 'view', $klant['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'klanten', 'action' => 'edit', $klant['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'klanten', 'action' => 'delete', $klant['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $klant['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php __('Related Notities');?></h3>
	<?php if (!empty($medewerker['Notitie'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Klant Id'); ?></th>
		<th><?php __('Medewerker Id'); ?></th>
		<th><?php __('Datum'); ?></th>
		<th><?php __('Opmerking'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($medewerker['Notitie'] as $notitie):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $notitie['id'];?></td>
			<td><?php echo $notitie['klant_id'];?></td>
			<td><?php echo $notitie['medewerker_id'];?></td>
			<td><?php echo $notitie['datum'];?></td>
			<td><?php echo $notitie['opmerking'];?></td>
			<td><?php echo $notitie['created'];?></td>
			<td><?php echo $notitie['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'notities', 'action' => 'view', $notitie['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'notities', 'action' => 'edit', $notitie['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'notities', 'action' => 'delete', $notitie['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $notitie['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Notitie', true), array('controller' => 'notities', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

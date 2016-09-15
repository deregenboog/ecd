<div class="intakes index">
	<h2><?php __('Intakes');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('klant_id');?></th>
			<th><?php echo $this->Paginator->sort('medewerker_id');?></th>
			<th><?php echo $this->Paginator->sort('datum_intake');?></th>
			<th><?php echo $this->Paginator->sort('verblijfstatus_id');?></th>
			<th><?php echo $this->Paginator->sort('postadres');?></th>
			<th><?php echo $this->Paginator->sort('postcode');?></th>
			<th><?php echo $this->Paginator->sort('woonplaats');?></th>
			<th><?php echo $this->Paginator->sort('verblijf_in_NL_sinds');?></th>
			<th><?php echo $this->Paginator->sort('verblijf_in_amsterdam_sinds');?></th>
			<th><?php echo $this->Paginator->sort('legitimatie_id');?></th>
			<th><?php echo $this->Paginator->sort('legitimatie_nummer');?></th>
			<th><?php echo $this->Paginator->sort('legitimatie_geldig_tot');?></th>
			<th><?php echo $this->Paginator->sort('verslavingsfrequentie_id');?></th>
			<th><?php echo $this->Paginator->sort('verslavingsperiode_id');?></th>
			<th><?php echo $this->Paginator->sort('woonsituatie_id');?></th>
			<th><?php echo $this->Paginator->sort('verwachting_dienstaanbod');?></th>
			<th><?php echo $this->Paginator->sort('toekomstplannen');?></th>
			<th><?php echo $this->Paginator->sort('opmerking_andere_instanties');?></th>
			<th><?php echo $this->Paginator->sort('medische_achtergrond');?></th>
			<th><?php echo $this->Paginator->sort('locatie1_id');?></th>
			<th><?php echo $this->Paginator->sort('locatie2_id');?></th>
			<th><?php echo $this->Paginator->sort('indruk');?></th>
			<th><?php echo $this->Paginator->sort('doelgroep');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($intakes as $intake):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $intake['Intake']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($intake['Klant']['id'], array('controller' => 'klanten', 'action' => 'view', $intake['Klant']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($intake['Medewerker']['id'], array('controller' => 'medewerkers', 'action' => 'view', $intake['Medewerker']['id'])); ?>
		</td>
		<td><?php echo $intake['Intake']['datum_intake']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($intake['Verblijfstatus']['naam'], array('controller' => 'verblijfstatussen', 'action' => 'view', $intake['Verblijfstatus']['id'])); ?>
		</td>
		<td><?php echo $intake['Intake']['postadres']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['postcode']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['woonplaats']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['verblijf_in_NL_sinds']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['verblijf_in_amsterdam_sinds']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($intake['Legitimatie']['naam'], array('controller' => 'legitimaties', 'action' => 'view', $intake['Legitimatie']['id'])); ?>
		</td>
		<td><?php echo $intake['Intake']['legitimatie_nummer']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['legitimatie_geldig_tot']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($intake['Verslavingsfrequentie']['naam'], array('controller' => 'verslavingsfrequenties', 'action' => 'view', $intake['Verslavingsfrequentie']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($intake['Verslavingsperiode']['naam'], array('controller' => 'verslavingsperiodes', 'action' => 'view', $intake['Verslavingsperiode']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($intake['Woonsituatie']['naam'], array('controller' => 'woonsituaties', 'action' => 'view', $intake['Woonsituatie']['id'])); ?>
		</td>
		<td><?php echo $intake['Intake']['verwachting_dienstaanbod']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['toekomstplannen']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['opmerking_andere_instanties']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['medische_achtergrond']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($intake['Locatie1']['naam'], array('controller' => 'locaties', 'action' => 'view', $intake['Locatie1']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($intake['Locatie2']['naam'], array('controller' => 'locaties', 'action' => 'view', $intake['Locatie2']['id'])); ?>
		</td>
		<td><?php echo $intake['Intake']['indruk']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['doelgroep']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['created']; ?>&nbsp;</td>
		<td><?php echo $intake['Intake']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $intake['Intake']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $intake['Intake']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $intake['Intake']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $intake['Intake']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 |	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Intake', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Medewerkers', true), array('controller' => 'medewerkers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Medewerker', true), array('controller' => 'medewerkers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Verblijfstatussen', true), array('controller' => 'verblijfstatussen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Verblijfstatus', true), array('controller' => 'verblijfstatussen', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Legitimaties', true), array('controller' => 'legitimaties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Legitimatie', true), array('controller' => 'legitimaties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Verslavingsfrequenties', true), array('controller' => 'verslavingsfrequenties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Verslavingsfrequentie', true), array('controller' => 'verslavingsfrequenties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Verslavingsperiodes', true), array('controller' => 'verslavingsperiodes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Verslavingsperiode', true), array('controller' => 'verslavingsperiodes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Woonsituaties', true), array('controller' => 'woonsituaties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Woonsituatie', true), array('controller' => 'woonsituaties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Locaties', true), array('controller' => 'locaties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Locatie1', true), array('controller' => 'locaties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Inkomens', true), array('controller' => 'inkomens', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Inkomen', true), array('controller' => 'inkomens', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Instanties', true), array('controller' => 'instanties', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Instantie', true), array('controller' => 'instanties', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Verslavingsgebruikswijzen', true), array('controller' => 'verslavingsgebruikswijzen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Verslavingsgebruikswijze', true), array('controller' => 'verslavingsgebruikswijzen', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Verslavingen', true), array('controller' => 'verslavingen', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Verslaving', true), array('controller' => 'verslavingen', 'action' => 'add')); ?> </li>
	</ul>
</div>

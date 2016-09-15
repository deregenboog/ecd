<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>
<?php echo $this->element('../groepsactiviteiten/groeptable', array('action' => 'export')); ?>

<?php if (! empty($id)) {
?>

<h3 class="with_margin"><?php echo $groepsactiviteiten_list[$id] ?></h3>

<table>
	<?php
		$url = $this->Html->url(array(
			'controller' => 'GroepsactiviteitenGroepen',
			'action' => 'export',
			$id,
			'Klant',
		)); 
	?>
	
	<caption><a href="<?=$url ?>" title="Download als CSV-bestand">Download</a></caption>
	
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('Deelnemer', 'Klant.achternaam'); ?></th>
			<th><?php echo $this->Paginator->sort('Werkgebied', 'Klant.werkgebied'); ?></th>
			<th><?php echo $this->Paginator->sort('Lid sinds', 'startdatum'); ?></th>
		</tr>
	</thead>
	
	<tbody>
		<?php foreach ($deelnemers as $deelnemer): ?>
			<tr>
				<td>
				<?php
				
		$u=array(
			'controller' => 'Groepsactiviteiten',
			'action' => 'intakes',
			'Klant',
			$deelnemer['Klant']['id'],
		);
		
		$name= "";
		if ($deelnemer['Klant']['roepnaam']) {
			$name = '('.$deelnemer['Klant']['roepnaam'].')';
		}
		
		$name.= $deelnemer['Klant']['name'];
		echo $this->Html->link($name, $u); ?>
		
				</td>
				<td><?php echo $deelnemer['Klant']['werkgebied']; ?></td>
				<td><?php echo $deelnemer['GroepsactiviteitenGroepenKlant']['startdatum']; ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<div class="pagination">
<?php

	if (count($deelnemers)) {
		echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled')); ?>
	 |	<?php echo $this->Paginator->numbers(); ?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled')); ?> 
<?php 
		// echo $this->Paginator->counter();
	} 
?>

</div>

<table class="export_table">
	<?php
		$url = $this->Html->url(array(
			'controller' => 'GroepsactiviteitenGroepen',
			'action' => 'export',
			$id,
			'Vrijwilliger',
		)); ?>
	<caption><a href="<?=$url ?>" title="Download als CSV-bestand">Download</a></caption>
	<thead>
		<tr>
			<th>Vrijwilliger</th>
			<th>Werkgebied</th>
			<th>Lid sinds</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($vrijwilligers as $vrijwilliger): ?>
			<tr>
				<td>
				<?php
				
			$u=array(
				'controller' => 'Groepsactiviteiten',
				'action' => 'verslagen',
				'Vrijwilliger',
				$vrijwilliger['Vrijwilliger']['id'],
			);
			$name= "";
			if ($vrijwilliger['Vrijwilliger']['roepnaam']) {
				$name = '('.$vrijwilliger['Vrijwilliger']['roepnaam'].')';
			}
			$name.= $vrijwilliger['Vrijwilliger']['name'];
			echo $this->Html->link($name, $u); 
			
				?>
			
				</td>
				<td><?php echo $vrijwilliger['Vrijwilliger']['werkgebied']; ?></td>
				<td><?php echo $vrijwilliger['GroepsactiviteitenGroepenVrijwilliger']['startdatum']; ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?php 
} ?>

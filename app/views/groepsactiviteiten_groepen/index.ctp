<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>
<?php echo $this->element('groepsactiviteiten_beheer_subnavigation'); ?>
<?php 
		$wrench = $html->image('add.png');
		$url = array('action' => 'add');
		$opts = array('escape' => false, 'title' => __('add', true));
		echo $html->link($wrench, $url, $opts);
		echo $this->Html->link(__('Nieuwe groep', true), array('action' => 'add'));
?>
<div>&nbsp;</div>
<div class="groepsactiviteitenGroepen ">
	<h2><?php __('Groepen');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('naam');?></th>
			<th><?php echo $this->Paginator->sort('startdatum');?></th>
			<th><?php echo $this->Paginator->sort('einddatum');?></th>
			<th><?php echo $this->Paginator->sort('activiteiten_registreren');?></th>
			<th><?php echo $this->Paginator->sort('werkgebied');?></th>
			<th class="actions"></th>
	</tr>
	<?php
	$i = 0;
	foreach ($groepsactiviteitenGroepen as $groepsactiviteitenGroep):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?= $groepsactiviteitenGroep['GroepsactiviteitenGroep']['naam']; ?>&nbsp;</td>
		<td><?= $date->show($groepsactiviteitenGroep['GroepsactiviteitenGroep']['startdatum'], array('short'=>true)); ?>&nbsp;</td>
		<td><?= $date->show($groepsactiviteitenGroep['GroepsactiviteitenGroep']['einddatum'], array('short'=>true)); ?>&nbsp;</td>
		<td><?= empty($groepsactiviteitenGroep['GroepsactiviteitenGroep']['activiteiten_registreren']) ? 'nee' : 'ja'; ?>&nbsp;</td>
		<td><?= $groepsactiviteitenGroep['GroepsactiviteitenGroep']['werkgebied']; ?>&nbsp;</td>
		<td class="actions">
			<?php 
				$wrench = $html->image('wrench.png');
				$url = array('action' => 'edit', $groepsactiviteitenGroep['GroepsactiviteitenGroep']['id']);
				$opts = array('escape' => false, 'title' => __('edit', true));
				echo $html->link($wrench, $url, $opts);
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => 'Pagina %page% van %pages%, met %current% records van %count% totaal, beginnend op record %start%, eindigend op %end%',
	));
	?>	</p>

	<div class="paging">
		<?= $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 |	<?= $this->Paginator->numbers();?>
 |
		<?= $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>

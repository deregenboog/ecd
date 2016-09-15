<?php 

echo $this->element('groepsactiviteiten_subnavigation'); 
echo $this->element('../groepsactiviteiten/groeptable', array('action' => 'planning'));

if (! empty($id)) {
	
	$wrench = $html->image('add.png');
	$delete_icon = $html->image('delete.png');
	$url = array('action' => 'add');
	$opts = array('escape' => false, 'title' => __('add', true));
	
	echo $html->link($wrench, $url, $opts);
	echo $this->Html->link(__('Activiteit toevoegen', true), array('action' => 'add'));
	
?>

<div>&nbsp;</div>
<div class="groepsactiviteitenPlanning ">

	<h2><?php __('Activiteiten'); ?></h2>
	
	<table cellpadding="0" cellspacing="0">
	
	<tr>
		<th><?= $this->Paginator->sort('datum'); ?></th>
		<th><?= $this->Paginator->sort('Groep', 'GroepsactiviteitenGroep.naam'); ?></th>
		<th><?= $this->Paginator->sort('naam'); ?></th>
		<th><?= __('Deelnemers'); ?></th>
		<th><?= __('Vrijwilligers'); ?></th>
		<th class="actions"></th>
	</tr>
	
	<?php
	$i = 0;
	
	foreach ($groepsactiviteiten as $groepsactiviteit):
	
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		} 
		
	?>
	<tr<?php echo $class; ?>>
	
		<td>
		<?= $groepsactiviteit['Groepsactiviteit']['datum']; ?>
		</td>
		
		<td>
		<?= $groepsactiviteit['GroepsactiviteitenGroep']['naam']; ?>
		</td>
		
		<td>
		<?= $groepsactiviteit['Groepsactiviteit']['naam']; ?>
		</td>
		
		<td>
		<?= $groepsactiviteit['Groepsactiviteit']['klanten_count']; ?>
		</td>
		
		<td>
		<?= $groepsactiviteit['Groepsactiviteit']['vrijwilligers_count']; ?>
		</td>
		
		<td class="actions" style='text-align: right'>
		
		<?php 
		
			$wrench = $html->image('wrench.png');
			$url = array('action' => 'edit', $groepsactiviteit['Groepsactiviteit']['id']);
			$opts = array('escape' => false, 'title' => __('view', true));
			
			echo $html->link($wrench, $url, $opts);
			
			$wrench = $html->image('usergroep.png');
			$url = array('action' => 'activiteit_registreren', $groepsactiviteit['Groepsactiviteit']['id']);
			$opts = array('escape' => false, 'title' => 'bewerken');
			
			echo $html->link($wrench, $url, $opts);
			
			if (empty($groepsactiviteit['Groepsactiviteit']['klanten_count']) 
					&& empty($groepsactiviteit['Groepsactiviteit']['vrijwilligers_count'])) {
						
				$delete_icon = $html->image('delete.png');
				
				$url = array(
					'action' => 'delete', 
					$groepsactiviteit['Groepsactiviteit']['id']
						
				);
				
				$opts = array(
					'id' => $groepsactiviteit['Groepsactiviteit']['id'], 
					'class' => 'verwijderen', 
					'escape' => false, 
					'title' => 'verwijderen'
				);
				
				echo $html->link($delete_icon, $url, $opts);
				
			} 
		?>
		
		</td>
	</tr>
	
<?php endforeach; ?>

	</table>
	<p>
	
<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	)); 
?>	

	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled')); ?>
	 |	<?php echo $this->Paginator->numbers(); ?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled')); ?>
	</div>
</div>

<?php 
}
$this->Js->buffer(<<<EOS

$('.verwijderen').click(function(){
	return confirm("Wilt u deze activiteit verwijderen?");
})

EOS
);

?>

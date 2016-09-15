<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>

<?php 
$afmeld_status = Configure::read('Afmeldstatus');
?>

<h2>Activiteit</h2>
<table>
		<tbody>
		
			<tr>
				<td><label><?= __('Groep') ?></label></td>
				<td><?= $groepsactiviteiten_list[$groepsactiviteit['groepsactiviteiten_groep_id']] ?></td>
			</tr>
			
			<tr>
				<td><label><?= __('Activiteit') ?></label></td>
				<td><?= $groepsactiviteit['naam'] ?></td>
			</tr>
			
			<tr>
				<td><label><?= __('Datum') ?> </label></td>
				<td><?= $date->show($groepsactiviteit['datum']) ?></td>
			</tr>
			
			<tr>
				<td><label><?= __('Tijd') ?></label></td>
				<td><?= $date->show($groepsactiviteit['time']) ?></td>
			</tr>
			
		</tbody>
	</table>
	
<div class="klanten ">	  
<h2><?php __('Deelnemers');?></h2>

<?= $html->link("Alle deelnemers uit groep \"{$groepsactiviteiten_list[$groepsactiviteit['groepsactiviteiten_groep_id']]}\" toevoegen", array(
	'controller' => 'Groepsactiviteiten',
	'action' => 'activiteit_registreren_groep',
	'Klant',
	$id,
));

?>
<br/>
<br/>
<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $this->Paginator->sort('Naam', 'Klant.achternaam');?></th>
		<th><?= __('Status') ?></th>
		<th class="actions"></th>
	</tr>
	
	<?php
	
	$i = 0;
	foreach ($klanten as $klant):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		$naam = trim($klant['Klant']['name1st_part']);
		if (!empty($naam)) {
			$naam.="&nbsp;";
		}
		$naam .= $klant['Klant']['name2nd_part'];
	?>
	<tr<?php echo $class;?> id='klant_row<?= $klant['GroepsactiviteitenKlant']['id'] ?>'>
	
		<td style="width: 500px;"><?= $naam; ?></td>
		
		<td><?= $this->Form->input('activiteit_klant_status'.$klant['GroepsactiviteitenKlant']['id'], array(
				 'label' => '',
				 'type' => 'select',
				 'class' =>  'activiteit_klant_status',
				 'value' => $klant['GroepsactiviteitenKlant']['afmeld_status'],
				 'options' => $afmeld_status, ))
		?></td>
		
		<td class="actions">
			<?php 
			$wrench = $html->image('delete.png');
			echo "<a href='#' class='activiteit_klant_delete' id='activiteit_klant_delete{$klant['GroepsactiviteitenKlant']['id']}'>".$wrench."</a>";
			?>
		</td>
		
	</tr>
	
<?php endforeach; ?>
	</table>
	
	<p id='klant_counter'>
	
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	));
	?>	
	</p>
	
	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 |	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
	
</div>
<br/>

<div>&nbsp;</div>

<div class="vrijwilligers ">
	<h2><?php __('Vrijwilligers');?></h2>
	<?= $html->link("Alle vrijwilligers uit groep \"{$groepsactiviteiten_list[$groepsactiviteit['groepsactiviteiten_groep_id']]}\" toevoegen", array(
		'controller' => 'Groepsactiviteiten',
		'action' => 'activiteit_registreren_groep',
		'Vrijwilliger',
		$id,
	));
	?>
	
	<br/>
	<br/>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th style="width: 500px;">Naam</th>
			<th>Status</th>
			<th class="actions"></th>
	</tr>
	
	<?php
	
	$i = 0;
	
	foreach ($vrijwilligers as $vrijwilliger):
	
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		$naam = trim($vrijwilliger['Vrijwilliger']['name1st_part']);
		if (!empty($naam)) {
			$naam.="&nbsp;";
		}
		$naam .= $vrijwilliger['Vrijwilliger']['name2nd_part'];
		
	?>
	
	<tr<?= $class;?> id='vrijwilliger_row<?= $vrijwilliger['GroepsactiviteitenVrijwilliger']['id'];?>'>
	
		<td><?= $naam ?></td>
		
		<td>
		
		<?= $this->Form->input('activiteit_vrijwilliger_status'.$vrijwilliger['GroepsactiviteitenVrijwilliger']['id'], array(
				 'label' => '',
				 'type' => 'select',
				 'class' =>  'activiteit_vrijwilliger_status',
				'value' => $vrijwilliger['GroepsactiviteitenVrijwilliger']['afmeld_status'],
				 'options' => $afmeld_status, ))
		?>
		</td>
		
		<td class="actions">
			<?php 
			$wrench = $html->image('delete.png');
			echo "<a href='#' class='activiteit_vrijwilliger_delete' id='activiteit_vrijwilliger_delete{$vrijwilliger['GroepsactiviteitenVrijwilliger']['id']}'>".$wrench."</a>";
			?>
		</td>
		
	</tr>
	
<?php endforeach; ?>
	</table>

</div>

<?php 

$url=$this->Html->url(
		array(
				'controller' => 'groepsactiviteiten',
				'action' => 'activiteit_persoon_delete',
				$id,
		)
);
$url2=$this->Html->url(
		array(
				'controller' => 'groepsactiviteiten',
				'action' => 'activiteit_persoon_status',
				$id,
		)
);
$this->Js->buffer(<<<EOS

Ecd.activiteit_persoon_delete = function(url,url2) {

	function addhandlers(url,url2) {

		$('.activiteit_klant_delete').click(function(){ 
			var answer = confirm('Deze persoon wissen van de activiteit?');
			if (answer) {
				$("#loading").css("display","block");
				id = this.id.substring(23);
				var params = { persoon_model: "Klant", persoon_groepsactiviteiten_id: id };
				$.post( url, params, function(data) {
					$("#loading").css("display","none");
						if ( data.return == false ) {
							alert('Kan persoon niet wissen van groep');
						} else {
							$('#klant_row'+id).hide();
							$('#klant_counter').hide();
						}
				},'json');
			}
			
		});

		$('.activiteit_vrijwilliger_delete').click(function(){ 
			var answer = confirm('Deze persoon wissen van de activiteit?');
			if (answer) {
				$("#loading").css("display","block");
				id = this.id.substring(30);
				var params = { persoon_model: "Vrijwilliger", persoon_groepsactiviteiten_id: id };
				$.post( url, params, function(data) {
					$("#loading").css("display","none");
					if ( data.return == false ) {
						alert('Kan persoon niet wissen van groep');
					} else {
						$('#vrijwilliger_row'+id).hide();
					}
				 },'json');
			}
		});


		$('.activiteit_klant_status').change(function(){ 
				$("#loading").css("display","block");
				id = this.id.substring(23);
				tag='#activiteit_klant_status'+id;
				afmeld_status = $('#activiteit_klant_status'+id).val();
				var params = { persoon_model: "Klant", persoon_groepsactiviteiten_id: id, afmeld_status: afmeld_status };
				$.post( url2, params, function(data) {
					$("#loading").css("display","none");
						if ( data.return == false ) {
							alert('Kan status niet aanpassen');
						} 
				},'json');
			
		});

		$('.activiteit_vrijwilliger_status').change(function(){ 
				$("#loading").css("display","block");
				id = this.id.substring(30);

				afmeld_status = $('#activiteit_vrijwilliger_status'+id).val();
				var params = { persoon_model: "Vrijwilliger", persoon_groepsactiviteiten_id: id, afmeld_status: afmeld_status };
				$.post( url2, params, function(data) {
					$("#loading").css("display","none");
						if ( data.return == false ) {
							alert('Kan status niet aanpassen');
						} 
				},'json');
			
		});

	}
	addhandlers(url,url2);

}
		
Ecd.activiteit_persoon_delete('{$url}','{$url2}');		  

EOS
);

?>

<?php
if (isset($this->data['result']) && $this->data['result']) {
	echo "<script>location.reload();</script>";
	return;
}
?>


<br/>
<fieldset class="data" id="activiteiten" style="display: block;">

<h2>Groepen</h2>

<?php

$wrench = $html->image('lock_open.png');

$this->Js->buffer(<<<EOS

Ecd.communicatie_settings = function(id, url) {

	function addhandlers(id, url) {
		$('#edit_communcatie_settings'+id).click(function(){ 
			id = $(this).attr("id").substring(25); 
			$('#communicatie_settings'+id).toggle();
			$('#groep_afsluiten_form'+id).hide();
		});

		$('#submit_communcatie_settings'+id).click(function(event){ 
			event.preventDefault();
			var data = $('#communicatie_settings'+id).find('*:input').serialize();
			$("#loading").css("display","block");
			$.post(url, data, function (data) {
				$("#loading").css("display","none");
				tr="communcatie_settings_content"+id;
				$('#'+tr).html(data);
			});
		});
		
	}
	addhandlers(id, url);

}
		
Ecd.groep_afsluiten = function(id, url) {

	function addhandlers(id, url) {

		$('#delete_communcatie_settings'+id).click(function(){ 
			id = $(this).attr("id").substring(27); 
			$('#groep_afsluiten_form'+id).toggle();
			$('#communicatie_settings'+id).hide();
		});

		$('#submit_afsluiten'+id).click(function(event){ 
			event.preventDefault();
			var data = $('#groep_afsluiten_form'+id).find('*:input').serialize();
			$("#loading").css("display","block");
			$.post(url, data, function (data) {
				$("#loading").css("display","none");
				div="groep_afsluiten"+id;
				$('#'+div).html(data);
			});
		});
		
	}
	addhandlers(id, url);

}
		
EOS
);
?>

<table>
	<thead>
	
	<tr>
		<th>Groep</th>
		<th>Startdatum</th>
		<th>Contact</th>
		<th>
		Afsluiten
		</th>
	</tr>
	
	<tr>
	</tr>
	
	</thead>
	
	<tbody>
	<?php foreach ($active_groeps as $key => $groepsactiviteit) {
	?> 
			<tr>
			<td>
				<?= $groepsactiviteiten_list_view[$groepsactiviteit['groepsactiviteiten_groep_id']] ?>
			</td>
			<td>
				<?= $date->show($groepsactiviteit['startdatum']) ?>
			</td>
			<td>
			<div id="communcatie_settings_content<?= $groepsactiviteit['id'] ?>">
			<?= $this->element('../groepsactiviteiten/communication_settings', array(
				'key' => $key,
				'selectie' => $persoon_model,
				'groepsactiviteit' => $groepsactiviteit,
			)); ?>
			</div>
			</td>
			<td width="280px">
			<div id="groep_afsluiten<?= $groepsactiviteit['id'] ?>">
			<?= $this->element('../groepsactiviteiten/groep_afsluiten', array(
				'key' => $key,
				'selectie' => $persoon_model,
				'groepsactiviteit' => $groepsactiviteit,
				'persoon_id' => $groepsactiviteit[$persoon_id_field],
			)); ?>
			</div>
			</td>
			</tr>
			
		<?php 
} ?> 
   </tbody>
</table>


<table>
	<thead>
	<tr>
		<th colspan="3">Toevoegen aan groep</th>
	</tr>
	</thead>
	<?php 
if(count($groepsactiviteiten_list_new) > 0 ) {
	echo $this->Form->create('Groepsactiviteit', array('url' => array('action' => 'groepen', $persoon_model, $id)));
?>

<?= $this->Form->hidden($persoon_groepsactiviteiten_groepen.'.communicatie_email', array('value' => 1)); ?>
<?= $this->Form->hidden($persoon_groepsactiviteiten_groepen.'.communicatie_post', array('value' => 1)); ?>
<?= $this->Form->hidden($persoon_groepsactiviteiten_groepen.'.communicatie_telefoon', array('value' => 1)); ?>
	
	<tbody>
	
	<tr>
		<td>
		<?= $this->Form->input($persoon_groepsactiviteiten_groepen.'.groepsactiviteiten_groep_id', array('type' => 'select', 'options' => $groepsactiviteiten_list_new));?>
		</td>
		<td>
		<?= $date->input($persoon_groepsactiviteiten_groepen.'.startdatum', date('Y-m-d'), array(
			'label' => 'Startdatum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 19).date('-m-d'),
		));
		?>
		</td>
		<td>
		<?= $this->Form->end(__('Toevoegen', true));?>
		</td>
	</tr>	
<?php } ?>		
	</tbody>
</table>


<table>
	<thead>
	
	<tr>
		<th>Groep</th>
		<th>Startdatum</th>
		<th>Einddatum</th>
		<th>Reden</th>
		<th></th>
	</tr>
	
	<tr>
	</tr>
	
	</thead>
	<tbody>
	<?php foreach ($inactive_groeps as $groepsactiviteit) {
			$opts = array('class' => 'unlock_koppeling', 'escape' => false, 'title' => __('edit', true));
			$url_openen = $this->Html->url(array('controller' => 'groepsactiviteiten', 'action' => 'open_groep', $persoon_model, $id, $groepsactiviteit['id']), true); ?> 
			<tr>
			
			<td>
				<?= $groepsactiviteiten_list_view[$groepsactiviteit['groepsactiviteiten_groep_id']] ?>
			</td>
			
			<td>
				<?= $date->show($groepsactiviteit['startdatum']) ?>
			</td>
			
			<td>
				<?= $date->show($groepsactiviteit['einddatum']) ?>
			</td>
			
			<td width="280px">
				<?php 
					if (!empty($groepsactiviteiten_redenen[$groepsactiviteit['groepsactiviteiten_reden_id']])) {
						echo $groepsactiviteiten_redenen[$groepsactiviteit['groepsactiviteiten_reden_id']];
					} ?>
			</td>
			
			<td>
			<?= $html->link($wrench, $url_openen, $opts); ?>
			</td>
			
			</tr>
		<?php 
		} ?> 
   </tbody>
</table>

</fieldset>

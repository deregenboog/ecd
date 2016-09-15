<?php
if (isset($this->data['result']) && $this->data['result']) {
	echo "<script>location.reload();</script>";
	return;
}

$url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'koppelingen_afsluiten', $id), true);
$form_id = $this->data['IzKoppeling']['id'];

$koppeling_einddatum = date('Y-m-d');

if (! empty($this->data['IzKoppeling']['koppeling_einddatum'])) {
	
	if (is_array($this->data['IzKoppeling']['koppeling_einddatum'])) {
		
		if (!empty($this->data['IzKoppeling']['koppeling_einddatum']['year']) && !empty($this->data['IzKoppeling']['koppeling_einddatum']['month']) && !empty($this->data['IzKoppeling']['koppeling_einddatum']['day'])) {
			$koppeling_einddatum =$this->data['IzKoppeling']['koppeling_einddatum']['year']."-".$this->data['IzKoppeling']['koppeling_einddatum']['month']."-".$this->data['IzKoppeling']['koppeling_einddatum']['day'];
		}
		
	} else {
		$koppeling_einddatum =$this->data['IzKoppeling']['koppeling_einddatum'];
	}
}

?>


	<?= $this->Form->create(
		'IzKoppeling',
		array(
				'url' => $url,
				'id' => "editIzKoppelingForm{$key}",

		)
	);
	?>
	
<?php 
	echo $this->Form->hidden("id");
	echo $this->Form->hidden("koppeling_startdatum");
	echo $this->Form->hidden("key", array('value' => $key));
	echo $this->Form->hidden("IzDeelnemer.id", array('value' => $id))
?>
	<?= $date->input("IzKoppeling.koppeling_einddatum", $koppeling_einddatum,
		array(
			'label' => 'Eind datum',
			'rangeHigh' => (date('Y') + 10).date('-m-d'),
			'rangeLow' => (date('Y') - 19).date('-m-d'),
		));
	?>

   
	<?= $this->Form->input("iz_eindekoppeling_id", array(
			'type' => 'select',
			'label' => 'Reden afsluiting',
			'options' => $iz_eindekoppelingen_active,
	));
	?>
	<?= $this->Form->input('koppeling_succesvol') ; ?>
   
	<?= $this->Form->end();?>
	<?= $this->Form->button('Afsluiten', array('id' => 'afsluitenSubmit'.$key)); ?>
   
<?php 
	$this->Js->buffer(<<<EOS

Ecd.afsluiten = function(key, url) {
	function addhandlers(key, url) {

		$('#afsluitenSubmit'+key).click(function(event){ 
			var data = $('#editIzKoppelingForm'+key).find('*:input').serialize();
			$("#loading").css("display","block");
			$.post(url, data, function (data) {

					$("#loading").css("display","none");
					div="IzKoppelingAfsluitElement"+key;
					$('#'+div).html(data);
			});
		});
		
	}
	addhandlers(key, url);
}

Ecd.afsluiten('{$key}','{$url}');
EOS
);

?>

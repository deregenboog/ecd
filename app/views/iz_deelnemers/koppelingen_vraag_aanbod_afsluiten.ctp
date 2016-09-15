<?php

if (isset($this->data['result']) && $this->data['result']) {
	echo "<script>location.reload();</script>";
	return;
}

$url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'koppelingen_vraag_aanbod_afsluiten', $id), true);
$form_id = $this->data['IzKoppeling']['id'];

$einddatum = date('Y-m-d');

if (! empty($this->data['IzKoppeling']['einddatum'])) {
	
	if (is_array($this->data['IzKoppeling']['einddatum'])) {
		
		if (!empty($this->data['IzKoppeling']['einddatum']['year']) && !empty($this->data['IzKoppeling']['einddatum']['month']) && !empty($this->data['IzKoppeling']['einddatum']['day'])) {
			$einddatum =$this->data['IzKoppeling']['einddatum']['year']."-".$this->data['IzKoppeling']['einddatum']['month']."-".$this->data['IzKoppeling']['einddatum']['day'];
		}
		
	} else {
		$einddatum =$this->data['IzKoppeling']['einddatum'];
	}
}

?>


	<?= $this->Form->create(
		'IzKoppeling',
		array(
				'url' => $url,
				'id' => "editVaIzKoppelingForm{$key}",

		)
	);
	?>
	
	<?php 
		echo $this->Form->hidden("id");
		echo $this->Form->hidden("key", array('value' => $key));
		echo $this->Form->hidden("IzDeelnemer.id", array('value' => $id))
	?>
 
	<?= $this->Form->input("iz_vraagaanbod_id", array(
			'type' => 'select',
			'label' => 'Reden afsluiting',
			'options' => $iz_vraagaanboden,
	));
	?>
   
	<?= $this->Form->end();?>
	<?= $this->Form->button('Afsluiten', array('id' => 'VaafsluitenSubmit'.$key)); ?>
   
<?php 
	$this->Js->buffer(<<<EOS

Ecd.afsluiten = function(key, url) {
	function addhandlers(key, url) {

		$('#VaafsluitenSubmit'+key).click(function(event){ 
			var data = $('#editVaIzKoppelingForm'+key).find('*:input').serialize();
			$("#loading").css("display","block");
			$.post(url, data, function (data) {

					$("#loading").css("display","none");
					div="VaIzKoppelingAfsluitElement"+key;
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

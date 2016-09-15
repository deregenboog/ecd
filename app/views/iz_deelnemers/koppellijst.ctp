<?php echo $this->element('iz_subnavigation'); ?>

<?php 

	echo $form->create('IzDeelnemer', array('controller' => 'iz_deelnemers', 'action'=>'koppellijst'));
?>
<table>
	<tr>
			<th class='IzKlKvoornaam'>
			<?=
				$form->input('K.voornaam', array(
					'label' => "",
					'style' => 'width: 80px;',
				));
			?>
			</th>
			<th class='IzKlKachternaam'>
			<?=
				$form->input('K.achternaam', array(
					'label' => "",
					'style' => 'width: 80px;',
				));
			?>
			</th>
			<th class='IzKlVvoornaam'>
			<?=
				$form->input('V.voornaam', array(
					'label' => "",
					'style' => 'width: 80px;',
				));
			?>
			</th>
			</th>
			<th class='IzKlVachternnaam'>
			<?=
				$form->input('V.achternaam', array(
					'label' => "",
					'style' => 'width: 80px;',
				));
			?>
			</th>
			<th class='IzKlmedewerker'>
			<?= $form->input('medewerker_id', array('type' => 'select', 'style'=>'width: 100px', 'options' => array('' => '') + $viewmedewerkers, 'label' => false)); ?>
			</th>
			<th class='IzKlstartdatum'>Afgesloten
			<?= $form->input('afgesloten', array('type' => 'checkbox', 'style'=>'width: 100px', 'label' => "")); ?>
			</th>
			<th class='IzKlwerkgebied'>
			<?= $form->input('werkgebied', array('type' => 'select', 'style'=>'width: 80px', 'options' => array('' => '') + $werkgebieden, 'label' => false)); ?>
			</th>
			<th class='IzKlproject'>
			<?= $form->input('project', array('type' => 'select', 'style'=>'width: 80px', 'options' => array('' => '') + $projecten, 'label' => false)); ?>
			</th>
	</tr>
</table>
<?php 
	echo $form->end();
?>


<div id="contentForIndex">
	<?php
		echo $this->element('iz_koppellijst', array('iz' => true));
	?>
</div>

<?php

$this->Js->buffer(<<<EOS

$("#IzDeelnemerKoppellijstForm").submit(function(e) {
	var postData = $(this).serializeArray();
	var formURL = $(this).attr("action");
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{
			//data: return data from server
			$('#contentForIndex').html(data);
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			//if fails		
			console.log('error');
		}
	});
	e.preventDefault(); //STOP default action
	//e.unbind(); //unbind. to stop multiple form submit.
});

function throttle(f, delay){
	var timer = null;
	return function(){
		var context = this, args = arguments;
		clearTimeout(timer);
		timer = window.setTimeout(function(){
			f.apply(context, args);
		},
		delay || 750);
	};
}

$('#IzDeelnemerAfgesloten').change(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
});
$('#IzDeelnemerProject').change(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
});
$('#IzDeelnemerWerkgebied').change(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
});
$('#IzDeelnemerMedewerkerId').change(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
});
$('#KAchternaam').keyup(throttle(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
}));
$('#KVoornaam').keyup(throttle(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
}));
$('#VAchternaam').keyup(throttle(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
}));
$('#VVoornaam').keyup(throttle(function(){
	$('#IzDeelnemerKoppellijstForm').submit();
}));
EOS
);

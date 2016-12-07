<?php
	/* @var $this View */
	/* @var $form FormHelper */
?>
<?= $this->element('iz_subnavigation') ?>
<table>
	<tr>
		<?= $form->create() ?>
			<th colspan="2">
				<?= $form->input('Klant.naam', array('label' => 'Naam klant')) ?>
			</th>
			<th colspan="2">
				<?= $form->input('Vrijwilliger.naam', array('label' => 'Naam vrijwilliger')) ?>
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
		<?= $form->end() ?>
		</tr>
	<tr>
		<th><?= $this->Paginator->sort('Klant voornaam', 'Klant.voornaam') ?></th>
		<th><?= $this->Paginator->sort('Klant achternaam', 'Klant.achternaam') ?></th>
		<th><?= $this->Paginator->sort('Vrijwilliger voornaam', 'Vrijwilliger.voornaam') ?></th>
		<th><?= $this->Paginator->sort('Vrijwilliger achternaam', 'Vrijwilliger.achternaam') ?></th>
		<th>Medewerker</th>
		<th><?= $this->Paginator->sort('Startdatum', 'Vrijwilliger.koppeling_startdatum') ?></th>
		<th><?= $this->Paginator->sort('Werkgebied', 'Klant.werkgebied') ?></th>
		<th><?= $this->Paginator->sort('Project', 'Vrijwilliger.project_naam') ?></th>
	</tr>
</table>

<div id="contentForIndex">
	<?= $this->element('iz_koppellijst', array('iz' => true)) ?>
</div>

<?php

$this->Js->buffer(<<<EOS

$("#IzKoppelingIndexForm").submit(function(e) {
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
	$('#IzKoppelingIndexForm').submit();
});
$('#IzDeelnemerProject').change(function(){
	$('#IzKoppelingIndexForm').submit();
});
$('#IzDeelnemerWerkgebied').change(function(){
	$('#IzKoppelingIndexForm').submit();
});
$('#IzDeelnemerMedewerkerId').change(function(){
	$('#IzKoppelingIndexForm').submit();
});
$('#KAchternaam').keyup(throttle(function(){
	$('#IzKoppelingIndexForm').submit();
}));
$('#KVoornaam').keyup(throttle(function(){
	$('#IzKoppelingIndexForm').submit();
}));
$('#VAchternaam').keyup(throttle(function(){
	$('#IzKoppelingIndexForm').submit();
}));
$('#VVoornaam').keyup(throttle(function(){
	$('#IzKoppelingIndexForm').submit();
}));
EOS
);

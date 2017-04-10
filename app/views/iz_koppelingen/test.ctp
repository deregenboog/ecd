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
<?php

function combineer_naam($t, $a)
{
	$n=$t;
	if (!empty($n)) {
		$n.=" ";
	}
	$n.=$a;
	return $n;
}
?>
<?php

	$paginator->options(array(
			'update' => '#contentForIndex',
			'evalScripts' => true,
	));

?>

<div class="izOntstaanContacten ">
	<table cellpadding="0" cellspacing="0"	class="index filtered">
		<tr>
			<th class='IzKlKvoornaam'><?php echo $this->Paginator->sort('Klant voornaam', 'Klant.voornaam');?></th>
			<th class='IzKlKachternaam'><?php echo $this->Paginator->sort('Klant achternaam', 'Klant.achternaam');?></th>
			<th class='IzKlVvoornaam'><?php echo $this->Paginator->sort('Vrijwilliger voornaam', 'Vrijwilliger.voornaam');?></th>
			<th class='IzKlVachternnaam'><?php echo $this->Paginator->sort('Vrijwilliger achternaam', 'Vrijwilliger.achternaam');?></th>
			<th class='IzKlmedewerker'>Medewerker</th>
			<th class='IzKlstartdatum'><?php echo $this->Paginator->sort('Startdatum', 'Vrijwilliger.koppeling_startdatum');?></th>
			<th class='IzKlwerkgebied'><?php echo $this->Paginator->sort('Werkgebied', 'Klant.werkgebied');?></th>
			<th class='IzKlproject'><?php echo $this->Paginator->sort('Project', 'Vrijwilliger.project_naam');?></th>
		</tr>
		<?php foreach ($koppelingen as $i => $koppeling): ?>
			<?php
				$kurl = array(
					'action' => 'koppelingen',
// 					$koppeling['IzKlant']['id'],
				);
				$vurl = array(
					'action' => 'koppelingen',
// 					$koppeling['IzHulpaanbod']['IzVrijwilliger']['id'],
				);

// 				var_dump($koppeling); die;

				$mw = $koppeling['ViewIzKoppeling']['hulpvraag_medewerker_achternaam'];
// 				if ($mw != $viewmedewerkers[$koppeling['IzHulpaanbod']['medewerker_id']]) {
// 					$mw.= "<br>".$viewmedewerkers[$koppeling['IzHulpaanbod']['medewerker_id']];
// 				}
			?>
			<tr class="<?= (++$i % 2 == 0) ? 'altrow' : '' ?>">
				<td><?php echo $html->link($koppeling['ViewIzKoppeling']['klant_voornaam'], $kurl); ?>&nbsp;</td>
				<td><?php echo $html->link(combineer_naam($koppeling['ViewIzKoppeling']['klant_tussenvoegsel'], $koppeling['ViewIzKoppeling']['klant_achternaam']), $kurl); ?>&nbsp;</td>
				<td><?php echo $html->link($koppeling['ViewIzKoppeling']['vrijwilliger_voornaam'], $vurl); ?>&nbsp;</td>
				<td><?php echo $html->link(combineer_naam($koppeling['ViewIzKoppeling']['vrijwilliger_tussenvoegsel'], $koppeling['ViewIzKoppeling']['vrijwilliger_achternaam']), $vurl); ?>&nbsp;</td>
				<td><?php echo $mw; ?>&nbsp;</td>
				<td><?php //echo $date->show($koppeling['IzHulpvraag']['koppeling_startdatum']); ?>&nbsp;</td>
				<td><?php //echo $koppeling['IzKlant']['Klant']['werkgebied']; ?>&nbsp;</td>
				<td><?php //echo $koppeling['IzProject']['naam']; ?>&nbsp;</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<p>
		<?= $this->Paginator->counter(array(
			'format' => 'Pagina %page% van %pages%, met %current% van de %count% koppelingen',
		)) ?>
	</p>
	<div class="paging">
		<?= $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled')) ?>
		| <?= $this->Paginator->numbers() ?>
		| <?= $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled')) ?>
	</div>
</div>
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

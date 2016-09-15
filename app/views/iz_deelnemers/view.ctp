<?php echo $this->element('iz_subnavigation'); ?>

<?php 

$upload_url = array(
		'controller' => 'iz_deelnemers',
		'action' => 'upload',
		$id,
);

$iz_documents = array();
if (!empty($persoon['IzDeelnemer']['IzDeelnemerDocument'])) {
	$iz_documents = $persoon['IzDeelnemer']['IzDeelnemerDocument'];
}
?>

<h2><?= $persoon_model; ?></h2>

<div class="vrijwilligers ">
<div class="actions">
	<?= $this->element('persoon_view_basic',
		 array(
			'name' => $persoon_model,
			'data' => $persoon,
			'show_documents' => false,
			'document_model' => 'IzDeelnemer',
			'view' => $this,
			'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
	));
?>
<?= $this->element('diensten', array(
		'diensten' => $diensten,
))?>

<?php
if (!empty($id)) {
	
	echo $this->element('persoon_zrm', array(
		'persoon_model' => $persoon_model,
		'persoon' => $persoon,
	));
	
	echo "</br>";
	
	echo $this->element('persoon_document', array(
		'upload_url' => $upload_url,
		'documents' => $iz_documents,
		'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
	));
}
?>

<div class="print-invisible">

</div>

</div>

<div class="klanten view">
	<ul class="tabs" id="tabbed_view">
		<li<?php if ($this->action == 'aanmelding' || $this->action == 'toon_aanmelding') {
	echo ' class="tab_acted"';
} ?> >

<?php 
	echo $html->link('Aanmelding', array(
		'action' => 'toon_aanmelding',
		$persoon_model,
		$persoon[$persoon_model]['id'],
		$id,
	));
?>
		</li>
		<?php if (! empty($id)) {
			?>
		<li<?php if ($this->action == 'intakes' || $this->action == 'toon_intakes') {
				echo ' class="tab_acted"';
			} ?> >
		<?php 
			echo $html->link('Intake', array(
				'action' => 'toon_intakes',
				$id,
			)); ?>
		</li>
		<?php if (!empty($iz_intake)) {
				?>
		<?php if ($persoon_model == 'Vrijwilliger') {
					?>
		<li<?php if ($this->action == 'vrijwilliger_intervisiegroepen') {
						echo ' class="tab_acted"';
					} ?> >
		<?php 
			echo $html->link('Intervisiegroepen', array(
				'action' => 'vrijwilliger_intervisiegroepen',
				$id,
			)); ?>
		</li>
		<?php 
				} ?>
		<li<?php if ($this->action == 'verslagen_persoon') {
					echo ' class="tab_acted"';
				} ?> >
		<?php 
			echo $html->link('Verslagen', array(
				'action' => 'verslagen_persoon',
				$id,
			)); ?>
		</li>
		<li<?php if ($this->action == 'koppelingen') {
				echo ' class="tab_acted"';
			} ?> >
		<?php 
			echo $html->link('Koppelingen', array(
				'action' => 'koppelingen',
				$id,
			)); ?>
		</li>
		<?php 
			}
		} ?>
		<?php if (! empty($id)) {
			?>
		<li<?php if ($this->action == 'afsluiting') {
				echo ' class="tab_acted"';
			} ?> >
		<?php 
			echo $html->link('Afsluiting', array(
				'action' => 'afsluiting',
				$id,
			)); ?>
		</li>
		<?php 
		} ?>
	</ul>
	<br />
	<br/>
	<?php if ($is_afgesloten) {
			?>
	<p style="color: red;"><?= $persoon_model?> is afgesloten</p>
	<?php 
		} ?>
	
<div id='iz_content'>
<?php
if (empty($id)) {
			$this->action = "aanmelding";
		}
switch ($this->action) {
	case 'aanmelding':
		echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_aanmelding');
		break;
	case 'toon_aanmelding':
		echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_toon_aanmelding');
		break;
	case 'intakes':
		echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_intakes');
		break;
	case 'toon_intakes':
		if (empty($this->data['IzIntake'])) {
			echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_intakes');
		} else {
			echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_toon_intakes');
		}
		break;
	case 'vrijwilliger_intervisiegroepen':
		echo $this->element('../iz_deelnemers/vrijwilliger_intervisiegroepen');
		break;
	case 'verslagen':
		echo $this->element('../iz_deelnemers/verslagen');
		break;
	case 'verslagen_persoon':
		echo $this->element('../iz_deelnemers/verslagen');
		break;
	case 'koppelingen':
		echo $this->element('../iz_deelnemers/koppelingen');
		break;
	case 'afsluiting':
		echo $this->element('../iz_deelnemers/afsluiting');
		break;
}
?>
</div>

<?php

$this->Js->buffer(<<<EOS

Ecd.disable_all = function(active) {
	if(active) {
		$('#iz_content').find('*:input').each(function () {
			$(this).attr('disabled', true);
		});
		$('.unlock_koppeling').each(function () {
			$(this).hide();
		});
	}
}

Ecd.disable_all('{$is_afgesloten}');  
	 
EOS
);

?>
</div>
</div>

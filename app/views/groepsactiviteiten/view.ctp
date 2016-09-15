<?php echo $this->element('groepsactiviteiten_subnavigation'); ?>

<?php 
$upload_url = array(
		'controller' => 'groepsactiviteiten',
		'action' => 'upload',
		$persoon_model,
		$persoon[$persoon_model]['id'],
);
?>

<h2><?= $persoon_model; ?></h2>

<div class="vrijwilligers ">
<div class="actions">

<?= $this->element('persoon_view_basic',
	array(
		'name' => $persoon_model,
		'data' => $persoon,
		'show_documents' => false,
		'view' => $this,
		'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
)); ?>

<?= $this->element('diensten', array(
		'diensten' => $diensten,
))?>

<?= $this->element('persoon_document', array(
		'upload_url' => $upload_url,
		'documents' => $persoon['GroepsactiviteitenDocument'],
		'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
))?>

<div class="print-invisible">

</div>
</div>

<div class="klanten view">
	<ul class="tabs" id="tabbed_view">
		<?php if ($persoon_model == 'Klant') {
	?> 
		<li<?php if ($this->action == 'intakes') {
		echo ' class="tab_acted"';
	} ?> >
		<?php 
			echo $html->link('Intakes', array(
				'action' => 'intakes',
				$persoon_model,
				$persoon[$persoon_model]['id'],
			)); ?>
		</li>
		<?php 
} ?>
		<li<?php if ($this->action == 'verslagen') {
	echo ' class="tab_acted"';
} ?> >
		<?php 
			echo $html->link('Verslagen', array(
				'action' => 'verslagen',
				$persoon_model,
				$persoon[$persoon_model]['id'],
			));
		?>
		</li>
		<li<?php if ($this->action == 'groepen') {
			echo ' class="tab_acted"';
		} ?> >
		<?php 
			echo $html->link('Groepen', array(
				'action' => 'groepen',
				$persoon_model,
				$persoon[$persoon_model]['id'],
			));
		?>
		</li>
		<li<?php if ($this->action == 'activiteiten') {
			echo ' class="tab_acted"';
		} ?> >
		<?php 
			echo $html->link('Activiteiten', array(
				'action' => 'activiteiten',
				$persoon_model,
				$persoon[$persoon_model]['id'],
			));
		?>
		</li>
		<?php if (true || $persoon_model == 'Klant') {
			?>
		<li<?php if ($this->action == 'afsluiting') {
				echo ' class="tab_acted"';
			} ?> >
		<?php 
			echo $html->link('Afsluiting', array(
				'action' => 'afsluiting',
				$persoon_model,
				$persoon[$persoon_model]['id'],
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

		
<div id='content'>
<?php
switch ($this->action) {
	case 'intakes':
		
		if ($persoon_model == 'Vrijwilliger') {
			echo $this->element('../groepsactiviteiten/vrijwilliger_intake');
		} else {
			echo $this->element('../groepsactiviteiten/klant_intake');
		}
		break;
		
	case 'verslagen':
		
		echo $this->element('../groepsactiviteiten/verslagen');
		break;
		
	case 'groepen':
		
		echo $this->element('../groepsactiviteiten/groepen');
		break;
		
	case 'activiteiten':
		
		echo $this->element('../groepsactiviteiten/activiteiten');
		break;
		
	case 'afsluiting':
		
		echo $this->element('../groepsactiviteiten/afsluiting');
		break;
}
?>

</div>
</div>
</div>

<?php

$this->Js->buffer(<<<EOS

Ecd.disable_all = function(active) {
	if(active) {
		$('#content').find('*:input').each(function () {
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

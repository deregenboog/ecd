<?php

$url=$this->Html->url(
		array(
				'controller' => 'groepsactiviteiten',
				'action' => 'communcatie_settings',
				$persoon_model,
				$groepsactiviteit['id'],
		)
);

$communicatie = "";

if (!empty($groepsactiviteit['communicatie_email'])) {
	if (! empty($communicatie)) {
		$communicatie .= ", ";
	}
	$communicatie .= "Email";
}
if (!empty($groepsactiviteit['communicatie_post'])) {
	if (! empty($communicatie)) {
		$communicatie .= ", ";
	}
	$communicatie .= "Post";
}
if (!empty($groepsactiviteit['communicatie_telefoon'])) {
	if (! empty($communicatie)) {
		$communicatie .= ", ";
	}
	$communicatie .= "Telefoon";
}
?>
	
	<?= $communicatie; ?>
	<?php 
			$wrench = $html->image('wrench.png');
			echo '<a href="#" id="edit_communcatie_settings'.$groepsactiviteit['id'].'" class="communicatie_option">'.$wrench.'</a>';
	?>
	<?= $this->Form->create('Groepsactiviteit', array(
				'id' => "communicatie_settings".$groepsactiviteit['id'],
				'url' => array('action' => 'communcatie_settings', $groepsactiviteit['id']),
				'style' => 'display: none;',
	)); ?>
			<?= $this->Form->input($persoon_groepsactiviteiten_groepen.'.communicatie_email', array(
					'label' => 'Email',
					'checked' => $groepsactiviteit['communicatie_email'],
			)); ?>
			<?= $this->Form->input($persoon_groepsactiviteiten_groepen.'.communicatie_post', array(
					'label' => 'Post',
					'checked' => $groepsactiviteit['communicatie_post'],
			)); ?>
			<?= $this->Form->input($persoon_groepsactiviteiten_groepen.'.communicatie_telefoon', array(
					'label' => 'Telefoon',
					'checked' => $groepsactiviteit['communicatie_telefoon'],
			));
			?>
			<?= $this->Form->button('Opslaan', array(
					'id' => 'submit_communcatie_settings'.$groepsactiviteit['id'],
					'type' => 'button',
					'href' => '#',
				));
			 ?>
			<?= $this->Form->end(); ?>
	   
		
<?php 
	$this->Js->buffer("Ecd.communicatie_settings(".$groepsactiviteit['id'].",'".$url."');");
?>
 

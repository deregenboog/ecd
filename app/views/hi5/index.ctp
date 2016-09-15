<h2><?php
__('Hi5 Klantenlijst');
?></h2>
<?php
	echo $html->link('Nieuwe klant invoeren', array(
			'controller' => 'klanten',
			'action' => 'add',
		), array(),
		__('Hebt u de algemene klantenlijst al gechecked? Weet u zeker dat dit een nieuwe klant is?', true)
	);
	
	echo $form->create('Klant', array(
		'controller' => 'hi5',
		'action' => 'index',
		'id' => 'filters',
	));
	
	$dd = array(
		'type' => 'text',
		'label' => false,
	);
?>
<table class="index filtered">
	<thead>
		<tr>
			<th class="noborder"><?php 
				echo $form->input('id', $dd);
				?>
			</th>
			<th class="noborder"><?php 
				echo $form->input('voornaam', $dd);
				?>
			</th>
			<th class="noborder"><?php 
				echo $form->input('achternaam', $dd);
				?>
			</th>
			<th class="noborder"><?php 
				echo $form->input('geboortedatum', $dd);
			?>
			</th>
			<th class="noborder">
			<?= $form->input('show_all', array(
				'type' => 'checkbox',
				'label' => 'Toon alle Regenboog klanten',
				'checked' => false,
			));
			?>
			</th>
			<th class="noborder" colspan="5"></th>
		</tr>
<?php 

	$ajax_url = $this->Html->url('/Hi5/index', true);
	
	$this->Js->get('#filters');
	
	$this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
	
	$this->Js->get('#KlantShowAll');
	
	$this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

?>
		
	</thead>
	<tbody id="contentForIndex">
	<?php echo $this->element('hi5_klantenlijst'); ?>
	</tbody>
</table>
<?php 
echo $form->end();
echo $js->writeBuffer();?>

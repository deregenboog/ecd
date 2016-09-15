<h1><?php __('Kies je huidige locatie uit de onderstaande lijst:');?></h1>

<ul>
<?php
	foreach ($locaties as $locatie_id => $locatie_naam):?>
		<li> 
			<?php 
				echo $this->Html->link($locatie_naam,
					array('controller'=>'registraties', 'action'=>'index', $locatie_id));
			?>
		</li>
	<?php endforeach; ?>
</ul>


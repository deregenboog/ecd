<fieldset>
	<legend>Schorsingen</legend>
	
	<div class="editWrench"> 
		<?php 
		
			$magnifier = $html->image('zoom.png', array('alt' => 'view'));
			$url = array('controller' => 'schorsingen', 'action' => 'index', $klant['Klant']['id']);
			$opts = array('escape' => false, 'title' => __('view', true));
			
			echo $html->link($magnifier, $url, $opts);
			
		?>
	</div>
	
	<?php if (!empty($klant['Schorsing'])): ?>
		<ul>
			<?php foreach ($klant['Schorsing'] as $schorsing): ?>
				<li>
					<?php
					
						echo 'van <b>'.$date->show($schorsing['datum_van'], array('short'=>true));
						echo '</b> tot <b>'.$date->show($schorsing['datum_tot'], array('short'=>true));
						
						echo '</b>';
						
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>Geen schorsingen bekend</p>
	<?php endif; ?>
</fieldset>

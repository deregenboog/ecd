<fieldset>
	<legend>Opmerkingen</legend>
	<div class="editWrench"> 
		<?php 
			$magnifier = $html->image('zoom.png', array('alt' => 'view'));
			$url = array('controller' => 'opmerkingen', 'action' => 'index', $klant_id);
			$opts = array('escape' => false, 'title' => __('view', true));
			echo $html->link($magnifier, $url, $opts);
		?>
	</div>
	
	<?php if (!empty($opmerkingen)): ?>
		<ul class="wrappedList">
			<?php foreach ($opmerkingen as $opmerking): ?>
				<li>
					<?php
						echo '<b>';
						echo $date->show($opmerking['Opmerking']['created'],
							array('short'=>true)).', ';
						echo $opmerking['Categorie']['naam'].'</b>';
						echo ': '.$opmerking['Opmerking']['beschrijving'];
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>Geen opmerkingen bekend</p>
	<?php endif; ?>
</fieldset>

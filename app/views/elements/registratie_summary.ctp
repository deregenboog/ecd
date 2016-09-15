<fieldset>
	<legend>Laatste registraties</legend>
	<?php if (count($data) == 0): ?>
		<p>Geen registraties gevonden.</p>
	<?php else: ?>
		<div class="editWrenchFloat">
		<?php 
			$magnifier = $html->image('zoom.png', array('alt' => __('view', true)));
			$url = array('controller' => 'klanten', 'action' => 'registratie', $klant['Klant']['id']);
			$opts = array('escape' => false, 'title' => __('view', true));
			echo $html->link($magnifier, $url, $opts); ?>
		</div>
		<ul>
			<?php foreach ($data as $registratie): ?>
				<li>
					<?php echo 'Op '.$date->show($registratie['Registratie']['binnen'], array('separator' => ' ')).' bij<br/>'.$registratie['Locatie']['naam']?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</fieldset>

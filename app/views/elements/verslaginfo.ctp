<fieldset>
	<legend>Gegevens maatschappelijk werk</legend>
	<div class="vscroll">
		<table cellpadding="0" cellspacing="0">
		
		<?php
		
		if (! empty($verslaginfo['Verslaginfo'])) {
			
			foreach ($verslaginfo['Verslaginfo'] as $key => $val) {
				
				if ($key == 'id') {
					continue;
				}
				
				$p = strpos($key, '_id');
				
				if ($p !== false) {
					$key = substr($key, 0, $p);
					if (!empty($medewerkers[$val])) {
						$val = $medewerkers[$val];
					} else {
						continue;
					}
				}

				$val = trim($val);
				
				if (empty($val)) {
					continue;
				} 
			?>
			<tr>
				<td><?php echo Inflector::humanize($key); ?></td>
			</tr>
			<tr>
				<td><?php echo nl2br($val); ?></td>
			</tr>

		<?php
			}
		}
		?>
		</table>
	</div>
	<div class="editWrench">
	
		<?php 
		
			$wrench = $html->image('wrench.png');
			$url = array('controller' => 'maatschappelijk_werk', 'action' => 'verslaginfo', $klantId);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
			
		?>
		
	</div>
</fieldset>

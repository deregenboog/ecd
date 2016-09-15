<h2>Maatschappelijk werk</h2>
<div class="maatsWerk">

<?php

	$c = 0;
	
	foreach ($inventarisaties as $catId => &$group) {
		
		if (++$c > 3) {
			$class = 'smaller';
		} else {
			$class = '';
		}
		
		echo '<fieldset class="tree '.$class.'"><legend>';
		
		echo $group['rootName'].'</legend>';
		
		foreach ($group as $invId => $inv) {
			
			if ($invId !== 'rootName') {
				
				echo '<div class="node">';
				
				for ($i = 0; $i < $inv['Inventarisatie']['depth']; $i++) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				
				echo $inv['Inventarisatie']['titel'];
				
				if ($inv['Inventarisatie']['actie'] !== 'N') {
					
					if (empty($inv['Inventarisatie']['count'])) {
						$inv['Inventarisatie']['count'] = 0;
					}
					
					echo ': <big>'.$inv['Inventarisatie']['count'].'</big>';
					
				}
				
				echo '</div>';
				
			}
		}
		echo '</fieldset>';
	}
?>
	
</div>
<div class="actions">
</div>

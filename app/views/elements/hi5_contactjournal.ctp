<?php
	if ($viewElementOptions & HI5_CREATE_TB_CJ) {
?>

	<br/>
	<br/>

<?php
	echo $html->link('Contactjournal Trajectbegeleider',
		array(
				'controller' => 'Hi5',
				'action' => 'contactjournal',
				$klant_id,
				1,
)); 
?>

	<br/>
	<div style="margin-left: 30px;">(
<?php
	echo $countContactjournalTB; 
?> 
	notities)
	</div>

<?php } ?>

<?php

	if ($viewElementOptions & HI5_CREATE_WB_CJ) {
?>
	<br />
	<br />

<?php
	echo $html->link('Contactjournal Werkbegeleider',
		array(
				'controller' => 'Hi5',
				'action' => 'contactjournal',
				$klant_id,
				0,
		)); 
?>
	<br />
	<div style="margin-left: 30px;">(

<?php	
	echo $countContactjournalWB; 
?> 
	notities)
	</div>

<?php } ?>

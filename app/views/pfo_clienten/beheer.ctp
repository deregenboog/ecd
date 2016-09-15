<?php
echo $this->element('pfo_subnavigation');
?>
<h2>Beheer</h2>

<?php
	echo $html->link('Groepen', array(
		'controller' => 'PfoGroepen',
	));
	
	echo "<br/>";
	
	echo $html->link('Aard relatie', array(
		'controller' => 'PfoAardRelaties',
	));
	
	echo "<br/>";
?>

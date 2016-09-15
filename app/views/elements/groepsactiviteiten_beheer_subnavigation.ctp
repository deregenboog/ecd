<h2>Beheer</h2>
<ul>
<li>
<?php 
echo $html->link('Groepen', array(
	'controller' => 'GroepsactiviteitenGroepen',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Afgesloten groepen', array(
	'controller' => 'GroepsactiviteitenGroepen',
	'action' => 'index',
	'true',
));
?>
</li>
<li>
<?php 
echo $html->link('Reden einde koppeling', array(
	'controller' => 'GroepsactiviteitenRedenen',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Groepsactiviteiten afsluiting', array(
	'controller' => 'GroepsactiviteitenAfsluitingen',
	'action' => 'index',
));
?>
</li>
</ul>
<div>&nbsp;</div>

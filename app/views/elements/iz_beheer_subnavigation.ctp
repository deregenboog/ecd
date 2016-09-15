<h2>Beheer</h2>
<ul>
<li>
<?php 
echo $html->link('Projecten', array(
	'controller' => 'iz_projecten',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Intervisiegroepen', array(
	'controller' => 'iz_intervisiegroepen',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Reden einde koppeling', array(
	'controller' => 'iz_eindekoppelingen',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Reden afsluiting', array(
	'controller' => 'iz_afsluitingen',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Vraag/Aanbod beeindiging', array(
	'controller' => 'iz_vraagaanboden',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Ontstaan contacten', array(
	'controller' => 'iz_ontstaan_contacten',
	'action' => 'index',
));
?>
</li>
<li>
<?php 
echo $html->link('Binnengekomen via', array(
	'controller' => 'iz_via_personen',
	'action' => 'index',
));
?>
</li>
</ul>
<div>&nbsp;</div>

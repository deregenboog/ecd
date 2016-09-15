<?php
$dedupLinkTpl = $this->Html->link('%1$s', array(
	'controller' => 'klanten',
	'action' => 'findDuplicates',
	'%2$s',
));

?>
<h2>Lijst van mogelijk dubbele invoer</h2>

<ul>
<?php

foreach ($modes as $op => $label) {
	?> <li> <?php
		printf($dedupLinkTpl, __($label, true), $op); ?> </li> <?php

}

?>

</ul>

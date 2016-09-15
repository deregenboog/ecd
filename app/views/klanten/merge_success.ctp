<h1>Resultaat</h1>
<br/>
<h2>Gekopieerde inhoud</h2>
<br/>
<ul>
<?php foreach ($counts as $name => $count) {
	echo '<li>'.$name.' - '.$count.'</li>';
} ?>
</ul>
<br/>
<h2>Opties</h2>
<br/>
<ul>
	<li>
		<?=
			$this->Html->link('Bekijk klant', array(
				'controller' => 'klanten',
				'action' => 'view',
				$newKlantId,
			));
		?>
	</li>
	<li>
		<?=
			$this->Html->link('Bewerk klant', array(
				'controller' => 'klanten',
				'action' => 'edit',
				$newKlantId,
			));
		?>
	</li>
	<li>
		<?=
			$this->Html->link('Lijst van mogelijk dubbele invoer', array(
				'controller' => 'klanten',
				'action' => 'findDuplicates',
			));
		?>
	</li>
</ul>

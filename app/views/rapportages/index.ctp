<h1>Rapportages</h1>
<p>Selecteer een rapportagemogelijkheid</p>
<ul>
	<li><?php echo $html->link('Klantrapportage', array('action' => 'lijst'))?></li>
	<li><?php echo $html->link('Locatierapportage', array('action' => 'locatie'))?></li>
	<li><?php echo $html->link('Klanten overzicht totaal', array('action' => 'locatie_klant'))?></li>
	<li><?php echo $html->link('Schorsingen', array('action' => 'schorsingen'))?></li>
	<li><?php echo $html->link('AWBZ-indicaties', array('action' => 'awbz_indicaties'))?></li>
	<li><?php echo $html->link('AWBZ facturatie', array('controller' => 'awbz', 'action' => 'rapportage'))?></li>
	<li><?php echo $html->link('Ecd management', array('action' => 'management'))?></li>
	<li><?php echo $html->link('Activering', array('action' => 'activering'))?></li>
	<li><?php echo $html->link('Geen hulpverlenerscontact', array('action' => 'geenHulpverlenerscontact'))?></li>
	<li><?php echo $html->link('Infobalierapportage', array('action' => 'infobalie'))?></li>
	<li><?php echo $html->link('Ladisrapportage', array('action' => 'ladis'))?></li>
	<li><?php echo $html->link('PFO rapportage', array('controller' => 'PfoClienten', 'action' => 'rapportage'))?></li>
	</ul>

<h1>Rapportages</h1>
<p>Selecteer een rapportagemogelijkheid</p>
<ul>
    <li><?= $html->link('Klantrapportage', ['action' => 'lijst']); ?></li>
    <li><?= $html->link('Locatierapportage', ['action' => 'locatie']); ?></li>
    <li><?= $html->link('Klantenoverzicht totaal', ['action' => 'locatie_klant']); ?></li>
    <li><?= $html->link('Schorsingen', ['controller' => 'inloop/schorsingen']); ?></li>
    <li><?= $html->link('AWBZ-indicaties', ['action' => 'awbz_indicaties']); ?></li>
    <li><?= $html->link('AWBZ facturatie', ['controller' => 'awbz', 'action' => 'rapportage']); ?></li>
    <li><?= $html->link('Ecd management', ['action' => 'management']); ?></li>
    <li><?= $html->link('Activering', ['action' => 'activering']); ?></li>
    <li><?= $html->link('Geen hulpverlenerscontact', ['action' => 'geenHulpverlenerscontact']); ?></li>
    <li><?= $html->link('Infobalierapportage', ['action' => 'infobalie']); ?></li>
    <li><?= $html->link('Ladisrapportage', ['action' => 'ladis']); ?></li>
    <li><?= $html->link('PFO rapportage', ['controller' => 'PfoClienten', 'action' => 'rapportage']); ?></li>
    <li><?= $html->link('Gerepatrieerd', ['action' => 'gerepatrieerd']); ?></li>
    <li><?= $html->link('Her-intakes', ['action' => 'herintakes']); ?></li>
    <li><?= $html->link('Overigen', ['controller' => 'app/rapportages']); ?></li>
</ul>

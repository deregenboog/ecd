Geachte mevrouw/ heer,

Onderstaande mevrouw/heer heeft aangegeven graag van jullie dienstenaanbod gebruik te willen maken. Wil je alsjeblieft contact opnemen met haar/hem om een afspraak te maken? Als het je niet lukt om contact te krijgen kun je mij (de intaker) via de mail  laten weten op welke wijze ik kan helpen om jullie met elkaar in contact te brengen. Mijn mailadres staat hieronder in de gegevens vermeld.

Met vriendelijke groet,

<?php
    $content = &$params['content'];
    echo $format->printData($content['Medewerker']['name'])."\n\n";
?>

Klant
-----------
<?php

$separator = array('separator' => ' '); //for the dates
$klant = &$params['content']['Klant'];
$klant_medewerker = '';
if ($klant['Medewerker']['voornaam']) {
    $klant_medewerker .= $klant['Medewerker']['voornaam'].' ';
}
if ($klant['Medewerker']['tussenvoegsel']) {
    $klant_medewerker .= $klant['Medewerker']['tussenvoegsel'].' ';
}
if ($klant['Medewerker']['achternaam']) {
    $klant_medewerker .= $klant['Medewerker']['achternaam'];
}

echo 'Voornaam: '.$klant['voornaam']."\n";

if ($klant['tussenvoegsel']) {
    echo 'Tussenvoegsel: '.$klant['tussenvoegsel']."\n";
}

echo 'Achternaam: '.$klant['achternaam']."\n";

if ($klant['roepnaam']) {
    echo 'Roepnaam: '.$klant['roepnaam']."\n";
}
echo 'Geslacht: '.$klant['Geslacht']['volledig']."\n";

echo 'Geboortedatum: ';
echo $date->show($klant['geboortedatum'], $separator);
echo "\n";

echo 'Geboorteland: ';
echo (!empty($klant['Geboorteland']))? $klant['Geboorteland']['land'] : 'onbekend';
echo "\n";

echo 'Nationaliteit: ';
echo (!empty($klant['Nationaliteit']))? $klant['Nationaliteit']['naam'] : 'onbekend';
echo "\n";

echo 'BSN: '.(($klant['BSN'])? $klant['BSN'] : '')."\n";

echo 'Laatste TBC controle: ';
if ($klant['laatste_TBC_controle']) {
    echo $date->show($klant['laatste_TBC_controle'], $separator);
}
echo "\n";
?>

Adresgegevens
-------------------  
<?php
echo 'Postadres: '.$format->printData($data['Intake']['postadres'])."\n";
echo 'Postcode: '.$format->printData($content['Intake']['postcode'])."\n";
echo 'Woonplaats: ';
echo $format->printData($content['Intake']['woonplaats'])."\n";
echo __('verblijf_in_NL_sinds', true).': ';
echo $format->printData(
    $date->show($content['Intake']['verblijf_in_NL_sinds'], $separator));
echo  "\n";
echo __('verblijf_in_amsterdam_sinds', true).': ';
echo $format->printData($date->show(
    $content['Intake']['verblijf_in_amsterdam_sinds'], $separator));
echo "\n";
echo __('verblijfstatus', true).': ';
echo $format->printData($content['Verblijfstatus']['naam'])."\n\n";
echo '------------------'."\n";
echo 'Naam intaker: ';
echo $format->printData($content['Medewerker']['name']);
echo "\n";
echo 'E-mail intaker: ';
echo $format->printData($content['Medewerker']['email']);
echo "\n";
echo 'Datum intake: '.$format->printData(
    $date->show($content['Intake']['datum_intake'], $separator));
echo "\n";

?>
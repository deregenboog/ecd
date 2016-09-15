Geachte mevrouw/ heer,

Onderstaande mevrouw/heer heeft een agressie schorsing gekregen.
Met vriendelijke groet,

<?php
    $content = &$params['content'];
    echo 'Medewerker: '.$content['medewerker']."\n\n";
    echo $content['url']."\n\n";
$klant = $content['Klant'];
?>

Klant
-----------
<?php

echo 'Naam: '.$klant['Klant']['name']."\n";
echo 'Geslacht: '.$klant['Geslacht']['volledig']."\n";

?>


Schorsing
-----------
<?php

$separator = array('separator' => ' '); //for the dates
$klant = &$params['content']['Klant'];
$schorsing = $params['content']['Schorsing'];

echo "Datum van            : {$schorsing['datum_van']}\n";
echo "Datum tot            : {$schorsing['datum_tot']}\n";
echo "Locatie              : {$schorsing['locatie_naam']}\n";
echo "Agressie tegen (1)   : {$schorsing['aggressie_tegen_medewerker']}\n";
echo "Agressie doelwit (1) : {$schorsing['aggressie_doelwit']}\n";
echo "Agressie tegen (2)   : {$schorsing['aggressie_tegen_medewerker2']}\n";
echo "Agressie doelwit (2) : {$schorsing['aggressie_doelwit2']}\n";
echo "Agressie tegen (3)   : {$schorsing['aggressie_tegen_medewerker3']}\n";
echo "Agressie doelwit (3) : {$schorsing['aggressie_doelwit3']}\n";
echo "Agressie tegen (4)   : {$schorsing['aggressie_tegen_medewerker4']}\n";
echo "Agressie doelwit (4) : {$schorsing['aggressie_doelwit4']}\n";
echo "Reden                : {$schorsing['reden']}\n";
if (!empty($schorsing['overig_reden'])) {
    echo "Overige reden        : {$schorsing['overig_reden']}\n";
}
echo "Aangifte gedaan      : {$schorsing['aangifte']}\n";
echo "Nazorg nodig         : {$schorsing['nazorg']}\n";
echo "Opmerking            : {$schorsing['remark']}\n";
echo "Locatiehoofd         : {$schorsing['locatiehoofd']}\n";
echo "Opmerking            : {$schorsing['bijzonderheden']}\n";
?>


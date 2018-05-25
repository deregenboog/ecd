<?php
    $options = ['separator' => ' '];
    $datum_van_vandaag = $this->Date->show(date('Y-m-d'), $options);
    $begindatum_schorsing = $this->Date->show($begindatum_schorsing, $options);
    $einddatum_schorsing_pp = $this->Date->show($einddatum_schorsing_pp, $options);
?>
<table border="1" cellpadding="4">
    <thead>
        <tr align="center">
            <th colspan="2"><h1>SCHORSINGSFORMULIER</h1></th>
        </tr>
    </thead>
    <tr>
        <td colspan="2">
            <p>
Op <?= $begindatum_schorsing ?> bent u geschorst bij locatie(s) <?= $locatie ?>.
De reden hiervoor is dat u zich niet aan de huisregels heeft gehouden.
Hieronder vindt u een korte beschrijving van het incident en de duur van de schorsing.
Ook informeren we u over de manier waarop u bezwaar kunt maken tegen uw schorsing.
            </p>
        </td>
    </tr>
    <tr>
        <td><b>Naam bezoeker</b></td>
        <td><?= $klant_naam ?></td>
    </tr>
    <tr>
        <td><b>Locatie(s)</b></td>
        <td><?= $locatie ?></td>
    </tr>
    <tr>
        <td><b>Naam locatiehoofd</b></td>
        <td><?= $locatiehoofd ?></td>
    </tr>
    <tr>
        <td><b>Reden schorsing</b></td>
        <td><?= implode("\n", $redenen)."\n\n".$opmerking_uit_schorsing ?></td>
    </tr>
    <tr>
        <td>
            <b>Duur schorsing</b>
            <p>
Na een schorsing van een half jaar of langer volgt altijd een herintake,
waarbij opnieuw wordt vastgesteld of u bij de doelgroep van de inloophuizen hoort.
Voor dit gesprek dient u zelf een afspraak te maken met het locatiehoofd van de locatie die u heeft geschorst.
            </p>
        </td>
        <td><?= sprintf('%s (%s t/m %s)', $lengte_schorsing, $begindatum_schorsing, $einddatum_schorsing_pp) ?></td>
    </tr>
    <tr>
        <td><b>Bijzonderheden</b></td>
        <td><?= $bijzonderheden ?></td>
    </tr>
    <tr>
        <td><b>Hoe kunt u bezwaar maken</b></td>
        <td>
            <p>Bent u het niet eens met uw schorsing, dan kunt u een afspraak maken voor een gesprek met het locatiehoofd of met de manager inloophuizen.</p>
            <p>U kunt zich wenden tot onafhankelijk cliënt-vertrouwenspersoon Lina Berger: 06 34293959.</p>
            <p>U kunt terecht bij de onafhankelijke klachtencommissie van het POA: 06 14264972 info@klachtencommissiepoa.nl.</p>
            <p>U kunt een klacht indienen bij de landelijke Geschillencommissie Zorg.</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
In en om al onze locaties staan rust, veiligheid en gezelligheid voor alle bezoekers, deelnemers en medewerkers voorop. We rekenen erop dat u hier in de toekomst weer een bijdrage aan zult leveren.
        </td>
    </tr>
</table>

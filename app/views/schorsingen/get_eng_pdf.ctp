<?php
    $options = ['separator' => ' '];
    $datum_van_vandaag = $this->Date->show(date('Y-m-d'), $options);
    $begindatum_schorsing = $this->Date->show($begindatum_schorsing, $options);
    $einddatum_schorsing_pp = $this->Date->show($einddatum_schorsing_pp, $options);
?>

<table border="1" cellpadding="4">
    <thead>
        <tr align="center">
            <th colspan="2"><h1>SUSPENSION NOTE</h1></th>
        </tr>
    </thead>
    <tr>
        <td colspan="2">
            <p>
On <?= $begindatum_schorsing ?> you were suspended at our location(s) <?= $locatie ?>.
The reason for your suspension was a disobedience of the house rules.
Please find in the following table a short description of the incident and the length of your suspension.
Also we would like to inform you about the way you can appeal against this decision.
            </p>
        </td>
    </tr>
    <tr>
        <td><b>Name visitor</b></td>
        <td><?= $klant_naam ?></td>
    </tr>
    <tr>
        <td><b>Location(s)</b></td>
        <td><?= $locatie ?></td>
    </tr>
    <tr>
        <td><b>Name head of location</b></td>
        <td><?= $locatiehoofd ?></td>
    </tr>
    <tr>
        <td><b>Reason for suspension</b></td>
        <td><?= implode("\n", $redenen)."\n\n".$opmerking_uit_schorsing ?></td>
    </tr>
    <tr>
        <td><b>Length of suspension</b></td>
        <td><?= sprintf('%s (%s t/m %s)', $lengte_schorsing, $begindatum_schorsing, $einddatum_schorsing_pp) ?></td>
    </tr>
    <tr>
        <td><b>Special notes</b></td>
        <td><?= $bijzonderheden ?></td>
    </tr>
    <tr>
        <td><b>How to appeal</b></td>
        <td>
            <p>If you do not agree with your suspension, you can make an apointment with the head of location or the region manager.</p>
            <p>You can contact the independent confidential mediator Lina Berger: 06 34293959.</p>
            <p>You can also contact the independent Complaints Commission of POA: 06 14264972 info@klachtencommissiepoa.nl.</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
Safety, cosines, peace and quiet are our main concern for visitors, neighbours and workers alike inside and outside of our locations. We are confident of your cooperation and contribution to these values in the future.
        </td>
    </tr>
</table>

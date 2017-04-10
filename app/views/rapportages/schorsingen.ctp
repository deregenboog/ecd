<div class="form">
    <fieldset>
        <legend>Schorsingen</legend>
        <p>
            <?php
            echo 'Rapportage van ';
            if ($this->data) {
                if ($this->data['options']['location'] == 0) {
                    echo 'alle locaties';
                } else {
                    echo $locations[$this->data['options']['location']];
                }
                echo ' met de gegevens van '.$this->data['date_from']['day'].'-'.$this->data['date_from']['month'].'-'.$this->data['date_from']['year'].' tot en met '.$this->data['date_to']['day'].'-'.$this->data['date_to']['month'].'-'.$this->data['date_to']['year'];
            } else {
                echo 'alle locaties met alle opgeslagen gegevens';
            }?>
        </p>
        <fieldset>
            <table>
                <tr>
                    <th>#</th>
                    <th>Naam (roepnaam)</th>
                    <th>Locatie</th>
                    <th>Begindatum</th>
                    <th>Einddatum</th>
                </tr>
                <?php if (!empty($clients)) {
                foreach ($clients as $klant_id => &$klant) {
                    ?>
                    <?php foreach ($klant['Schorsing'] as &$sch) {
                        ?>
                <tr>
                    <td><?= $klant_id ?></td>
                    <td>
                        <?php
                            echo $klant['name'].' ';
                        if (!empty($klant['roepnaam'])) {
                            echo '('.$klant['roepnaam'].')';
                        } ?>
                    </td>
                    <td><?= $sch['Locatie']['naam']; ?></td>
                    <td><?=  $format->printData($date->show($sch['Schorsing']['datum_van'])); ?></td>
                    <td><?=  $format->printData($date->show($sch['Schorsing']['datum_tot'])); ?></td>
                </tr>
                <?php
                    } ?>
                <?php
                }
            } ?>
            </table>
        </fieldset>
    </fieldset>
</div>

<div class="actions">
    <?= $this->element('report_filter');?>
</div>

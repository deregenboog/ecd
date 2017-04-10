<?php
    $years = [
        '2013' => '2013',
        '2014' => '2014',
        '2015' => '2015',
        '2016' => '2016',
        '2017' => '2017',
    ];
    echo $this->element('pfo_subnavigation');
?>


<?php if (isset($contact_momenten) && count($contact_momenten) > 0) {
    ?>
<div class="form">
        <fieldset>
            <legend>Aantal contactmomenten <?= $year; ?></legend>
            <table class="fixedwidth">
        <?php
        echo "<tr><th>&nbsp;</th>";
    foreach ($contact_momenten as $cm) {
        foreach ($cm as $header => $c) {
            echo "<th>{$header}</th>";
        }
        break;
    }
    foreach ($contact_momenten as $k => $cm) {

        echo "</tr>";
        echo "<td>";

        if (isset($groepen[$k])) {
            echo $groepen[$k];
        } else {
            echo $k;
        }

        echo "</td>";

        foreach ($cm as $k2 => $v2) {
            echo "<td>{$v2}</td>";
        }

        echo "</tr>";

    } ?>

            </table>
        </fieldset>
        <fieldset>
            <legend>Aantal personen <?= $year; ?></legend>
            <table class="fixedwidth">
<?php

    $total=0;
    foreach ($groep_total as $k => $cm) {
        //$cm['subq']['groep']]
                $groep=$groepen[$cm['subq']['groep']];
        $count=$cm['0']['count'];
        $total += $count;
        echo "</tr><td>{$groep}</td><td>{$count}<td></td></tr>";
    }
    echo "</tr><td>Totaal</td><td>{$total}<td></td></tr>";

?>

    </table>

    </fieldset>
</div>
<?php //debug($groep_total)?>

<?php } ?>

<div class="actions">
<fieldset>
    <legend>Rapportage opties</legend>
    <?php
    echo $this->Form->create('PfoClient', array(
        'url' => array(
            'action' => 'rapportage',
        ),
    ));

    ?>
    <h4>Periode</h4>
    <?php


    echo $this->Form->input('jaar', array(
        'options' => $years,
    ));

    echo $form->end(array('label' => 'Ga'));
    ?>
</fieldset>

</div>
</div>

<?php $today = date('Y-m-d'); ?>

<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)); ?>
    <?= $this->element('diensten', array( 'diensten' => $diensten, )); ?>
    <?= $this->element('hi5_traject', array('data' => $klant)); ?>
    <?= $this->element('hi5_intake', array('viewElementOptions' => $viewElementOptions, 'data' => $klant)); ?>
    <?= $this->element('hi5_evaluatie', array('viewElementOptions' => $viewElementOptions, 'data' => $klant)); ?>
    <?php
        echo $this->element('hi5_contactjournal', array(
            'viewElementOptions' => $viewElementOptions,
            'countContactjournalTB' => $countContactjournalTB,
            'klant_id' => $klant['Klant']['id'],
            'countContactjournalWB' => $countContactjournalWB,
        ));
    ?>
</div>

<div class="intakes view">
    <?php
        echo '<div class="editWrench">';
        $printer_img = $this->Html->image('printer.png');
        echo '<a href="#" onclick="window.print()">'.$printer_img.'</a>';
        echo '</div>';
    ?>

    <div class="fieldset">
        <h1><?php __('HI5 Intake'); ?></h1>
        <fieldset>
            <legend>Algemeen</legend>
            <table class='fixedwidth'>
                <?php
                    echo $format->printTableLine(
                        'Naam intaker',
                        $intake['Medewerker']['name']);

                    echo $format->printTableLine(
                        'Datum van intake',
                        $intake['Hi5Intake']['datum_intake'],
                        FormatHelper::DATE);
                ?>
            </table>
        </fieldset>

        <fieldset>
            <legend>Adresgegevens</legend>
            <table class="fixedwidth">
                <?php
                    echo $format->printTableLine(
                        'Primaire problematiek',
                        $intake['PrimaireProblematiek']['naam']);

                    echo $format->printTableLine(
                        'Adres',
                        $intake['Hi5Intake']['postadres']);

                    echo $format->printTableLine(
                        'Postcode',
                        $intake['Hi5Intake']['postcode']);

                    echo $format->printTableLine(
                        'Woonplaats',
                        $intake['Hi5Intake']['woonplaats']);

                    echo $format->printTableLine(
                        'Verblijft in Nederland sinds',
                        $intake['Hi5Intake']['verblijf_in_NL_sinds'],
                        FormatHelper::DATE);

                    echo $format->printTableLine(
                        'Verblijft in Amsterdam sinds',
                        $intake['Hi5Intake']['verblijf_in_amsterdam_sinds'],
                        FormatHelper::DATE);

                    echo $format->printTableLine(
                        'Verblijfstatus',
                        $intake['Verblijfstatus']['naam']);
                ?>
            </table>
        </fieldset>

        <fieldset>
            <legend>Locatiekeuze</legend>
            <table class="fixedwidth">
                <?php
                    echo $format->printTableLine(
                        'Eerste locatiekeuze',
                        $intake['Locatie1']['naam']);
                    echo $format->printTableLine(
                        'Tweede locatiekeuze',
                        $intake['Locatie2']['naam']);
                    echo $format->printTableLine(
                        'Derde locatiekeuze',
                        $intake['Locatie3']['naam']);
                    echo $format->printTableLine(
                        'Werklocatie',
                        $intake['Werklocatie']['naam']);
                    echo $format->printTableLine(
                        'mag_gebruiken',
                        $intake['Hi5Intake']['mag_gebruiken'],
                        FormatHelper::JANEE);
                ?>
            </table>
        </fieldset>

        <fieldset>
            <legend>Legitimatie</legend>
            <table class="fixedwidth">
                <?php
                    echo $format->printTableLine(
                        'Legitimatie',
                        $intake['Legitimatie']['naam']);
                    echo $format->printTableLine(
                        'Legitimatienummer',
                        $intake['Hi5Intake']['legitimatie_nummer']);
                    echo $format->printTableLine(
                        'Legitimatie geldig tot',
                        $intake['Hi5Intake']['legitimatie_geldig_tot'],
                        FormatHelper::DATE);
                ?>
            </table>
        </fieldset>

        <fieldset>
            <legend>Verslaving</legend>
            <?php if ($intake['PrimaireProblematiek']['id']): ?>
                <h3>Primaire problematiek</h3>
                <table class="fixedwidth">
                    <?php
                        echo $format->printTableLine(
                            'Primaire problematiek',
                            $intake['PrimaireProblematiek']['naam']);
                        echo $format->printTableLine(
                            'Verslavingsperiode',
                            $intake['PrimaireProblematieksperiode']['naam']);

                        echo $format->printTableLine(
                            'Hoe gebruikt client?',
                            $format->collect($intake['Primaireproblematieksgebruikswijze']),
                            FormatHelper::UL_LIST);
                    ?>
                </table>

                <h3>Secundaire problematiek</h3>
                <table class="fixedwidth">
                    <?php
                        echo $format->printTableLine(
                            'Verslaving',
                            $format->collect($intake['Verslaving']),
                            FormatHelper::UL_LIST);

                        echo $format->printTableLine(
                            'Overige vormen van verslavingen',
                            $intake['Hi5Intake']['verslaving_overig']);

                        echo $format->printTableLine(
                            'Verslavingsfrequentie',
                            $intake['Verslavingsfrequentie']['naam']);

                        echo $format->printTableLine(
                            'Verslavingsperiode',
                            $intake['Verslavingsperiode']['naam']);

                        echo $format->printTableLine(
                            'Hoe gebruikt client?',
                            $format->collect($intake['Verslavingsgebruikswijze']),
                            FormatHelper::UL_LIST);

                    ?>
                </table>
                <h3>Algemene problematiek</h3>
                <table class="fixedwidth">
                    <?php
                        echo $format->printTableLine('Wat is de datum van het eerste gebruik?',$intake['Hi5Intake']['eerste_gebruik'],FormatHelper::DATE);
                    ?>
                </table>
        <?php else: ?>
                <h3>Problematiek</h3>
                <table class="fixedwidth">
                    <?php
                        echo $format->printTableLine(
                            'Verslaving',
                            $format->collect($intake['Verslaving']),
                            FormatHelper::UL_LIST);

                        echo $format->printTableLine(
                            'Overige vormen van verslavingen',
                            $intake['Hi5Intake']['verslaving_overig']);
                    ?>
                </table>
            <?php endif; ?>
        </fieldset>

        <fieldset>
            <legend>Inkomen en woonsituatie</legend>
            <table class="fixedwidth">
                <?php
                    echo $format->printTableLine('Inkomen',$format->collect($intake['Inkomen']), FormatHelper::UL_LIST);
                    echo $format->printTableLine('Overige vormen van inkomen', $intake['Hi5Intake']['inkomen_overig']);
                    echo $format->printTableLine('Wat is de woonsituatie?', $intake['Woonsituatie']['naam']);
                ?>
            </table>
        </fieldset>

        <fieldset>
            <legend>Wensen en verwachtingen</legend>
            <table class="fixedwidth">
                <?php
                    if (!empty($intake['Bedrijfitem1']['Bedrijfsector'])) {
                        echo $format->printTableLine('Bedrijfssector1',	$intake['Bedrijfitem1']['Bedrijfsector']['name']);
                    } else {
                        echo $format->printTableLine('Bedrijfsector1', '');
                    }
                    echo $format->printTableLine('Project1', $intake['Bedrijfitem1']['name']);

                    if (!empty($intake['Bedrijfitem2']['Bedrijfsector'])) {
                        echo $format->printTableLine('Bedrijfssector2',	$intake['Bedrijfitem2']['Bedrijfsector']['name']);
                    } else {
                        echo $format->printTableLine('Bedrijfsector2', '');
                    }
                    echo $format->printTableLine('Project2',$intake['Bedrijfitem2']['name']);
                ?>
            </table>
        </fieldset>

        <fieldset>
            <?php
                if (! empty($zrmReport)) {
                    echo $this->element('zrm_view', array('data' => $zrmReport));
                }
            ?>
        </fieldset>

        <table class="fixedwidth">
            <?php
                $question_cnt = 0;
                $values = array();
                foreach ($intake['Hi5Answer'] as $answer) {
                    $values[$answer['id']] = $answer['Hi5IntakesAnswer']['hi5_answer_text'];
                }

                $lastCategory = false;
                foreach ($hi5Questions as $questionKey => $questionDetails) {

                    if ($questionDetails['category'] != $lastCategory) {
                        printf('<tr><td colspan="2" class="white-background"><h2>%s</h2></td></tr>', $questionDetails['category']);
                        $lastCategory = $questionDetails['category'];
                    }
                    if (isset($questionDetails['answers'])) {
                        $questionLabel = $questionDetails['question'];

                        if (! empty($questionDetails['answers']['checkbox'])) {
                            $checked = array_intersect_key($questionDetails['answers']['checkbox'], $values);
                            if (count($checked) > 0) {
                                echo $format->printTableLine($questionLabel, $checked, FormatHelper::UL_LIST);
                                $questionLabel = '';
                            }
                        }
                        if (! empty($questionDetails['answers']['dropdown'])) {
                            $checked = array_intersect_key($questionDetails['answers']['dropdown'], $values);
                            if (count($checked) > 0) {
                                echo $format->printTableLine($questionLabel, $checked, FormatHelper::UL_LIST);
                                $questionLabel = '';
                            }
                        }
                        if (! empty($questionDetails['answers']['open'])) {
                            $open_id = array_keys($questionDetails['answers']['open']);
                            $open_id = array_pop($open_id);
                            if (! empty($values[$open_id])) {
                                echo $format->printTableLine($questionLabel, $values[$open_id]);
                                $questionLabel = '';
                            }
                        }
                    }
                }

            ?>
        </table>
    </fieldset>
</div>

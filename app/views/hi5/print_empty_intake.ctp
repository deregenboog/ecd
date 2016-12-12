<?php $today = date('Y-m-d'); ?>

<div class="intakes view no_li_bullets">
    <div class="fieldset">
        <h1><?php __('HI5 Intake'); ?></h1>
        <div class="fieldset">
            <h2>Algemeen</h2>
            <table class="fixedwidth">
                <?php
                    echo $format->printEmptyTableLine('Naam intaker');
                    echo $format->printEmptyTableLine('Datum van intake');
                ?>
            </table>
        </div>

        <div class="fieldset">
            <h2>Adresgegevens</h2>
            <table class="fixedwidth">
                <?php
                    echo $format->printEmptyTableLine('Primaire problematiek');
                    echo $format->printEmptyTableLine('Adres');
                    echo $format->printEmptyTableLine('Postcode');
                    echo $format->printEmptyTableLine('Woonplaats');
                    echo $format->printEmptyTableLine('Verblijft in Nederland sinds');
                    echo $format->printEmptyTableLine('Verblijft in Amsterdam sinds');
                    echo $format->printEmptyTableLine(
                        'Verblijfstatus',
                        $verblijfstatussen,
                        FormatHelper::UL_LIST_RADIO
                    );
                ?>
            </table>
        </div>

        <div class="fieldset">
            <h2>Locatiekeuze</h2>
            <table class="fixedwidth">
                <?php
                    echo $format->printEmptyTableLine('Eerste locatiekeuze');
                    echo $format->printEmptyTableLine('Tweede locatiekeuze');
                    echo $format->printEmptyTableLine('Derde locatiekeuze');
                    echo $format->printEmptyTableLine('Werklocatie');
                    echo $format->printEmptyTableLine('mag_gebruiken','',FormatHelper::JANEE);
                ?>
            </table>
        </div>

        <div class="fieldset">
            <h2>Legitimatie</h2>
            <table class="fixedwidth">
                <?php
                    echo $format->printEmptyTableLine('Legitimatie',$legitimaties,FormatHelper::UL_LIST_RADIO);
                    echo $format->printEmptyTableLine('Legitimatienummer');
                    echo $format->printEmptyTableLine('Legitimatie geldig tot');
                ?>
            </table>
        </div>

        <div class="fieldset">
            <h2>Verslaving</h2>
            <h3>Problematiek</h3>
            <table class="fixedwidth">
                <?php
                    echo $format->printEmptyTableLine('Verslaving',$problems,FormatHelper::UL_LIST_CHECKBOXES);
                    echo $format->printEmptyTableLine('Overige vormen van verslavingen');
                ?>
            </table>
        </div>

        <div class="fieldset">
            <h2>Inkomen en woonsituatie</h2>
            <table class="fixedwidth">
                <?php
                    echo $format->printEmptyTableLine('Inkomen',$inkomens,FormatHelper::UL_LIST_CHECKBOXES);
                    echo $format->printEmptyTableLine('Overige vormen van inkomen');
                    echo $format->printEmptyTableLine('Wat is de woonsituatie?',$woonsituaties,FormatHelper::UL_LIST_RADIO);
                ?>
            </table>
        </div>

        <div class="fieldset">
            <h2>Wensen en verwachtingen</h2>
            <table class="fixedwidth">
                <?php
                    echo $format->printEmptyTableLine('Bedrijfssector1',$bedrijfSectors,FormatHelper::UL_LIST_RADIO);
                    echo $format->printEmptyTableLine('Project1');
                    echo $format->printEmptyTableLine('Bedrijfssector2',$bedrijfSectors,FormatHelper::UL_LIST_RADIO);
                    echo $format->printEmptyTableLine('Project2');
                ?>
            </table>
        </div>

        <table class="fixedwidth">
            <?php
                $question_cnt = 0;
                $lastCategory = false;
                foreach ($hi5Questions as $questionKey => $questionDetails) {
                    if ($questionDetails['category'] != $lastCategory) {
                        printf('<tr><td colspan="2" class="white-background"><h2>%s</h2></td></tr>', $questionDetails['category']);
                        $lastCategory = $questionDetails['category'];
                    }
                    if (isset($questionDetails['answers'])) {
                        $questionLabel = $questionDetails['question'];

                        if (! empty($questionDetails['answers']['checkbox'])) {
                            echo $format->printEmptyTableLine(
                                $questionLabel,
                                $questionDetails['answers']['checkbox'],
                                FormatHelper::UL_LIST_CHECKBOXES
                            );
                            $questionLabel = '';
                        }

                        if (! empty($questionDetails['answers']['dropdown'])) {
                            echo $format->printEmptyTableLine(
                                $questionLabel,
                                $questionDetails['answers']['dropdown'],
                                FormatHelper::UL_LIST_RADIO
                            );
                            $questionLabel = '';
                        }

                        if (! empty($questionDetails['answers']['open'])) {
                            echo $format->printEmptyTableLine($questionLabel);
                            $questionLabel = '';
                        }
                    }
                }
            ?>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        window.print();
    });
</script>

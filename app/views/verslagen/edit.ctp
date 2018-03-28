
<div class="verslagen form">
<?php
    echo $this->Form->create('Verslag');

    $name="";

    if ($klant['Klant']['voornaam'] != "") {
        $name.=$klant['Klant']['voornaam']." ";
    }

    if ($klant['Klant']['roepnaam'] != "") {
        $name.="(".$klant['Klant']['roepnaam'].") ";
    }

    if ($klant['Klant']['tussenvoegsel'] != "") {
        $name.=$klant['Klant']['tussenvoegsel']." ";
    }

    $name.=$klant['Klant']['achternaam'];

?>

    <fieldset class="tree">
        <div class="cell">
            <h3>Nieuw verslag voor <?= $name ?></h3>
            <div class="scroll-box">
<?php
    echo 'Medewerker: '.$medewerkers[$intaker_id];

    echo $this->Form->hidden('id');

    echo $this->Form->hidden('klant_id', array('value' => $klant_id));

    echo $this->Form->hidden('medewerker_id', array('label' => 'Naam intaker', 'default' => $intaker_id ));

    $default = null;

    if (!empty($this->data['Verslage']['datum'])) {
        $default = date('Y-m-d');
    }

    echo $this->Date->input('Verslag.datum', $default, array('label' => 'Datum'));

    foreach ($inventarisaties as $catId => &$group) {

        echo '<fieldset class="no-border"><legend>'.$group['rootName'].'</legend>';
        echo '<span class="movableDropdown" style="display: none" id="dropdownFor'.$catId.'">';

        $dropdowns = array();

        foreach ($doorverwijzers as $type => &$options) {

            $dropdown_id =
                'InventarisatiesVerslagen'.$catId.
                'DoorverwijzerId'.Inflector::classify($type);

            if (!empty($this->data['InventarisatiesVerslagen'][$catId]['doorverwijzer_id'])) {
                $doorverwijzer_id = $this->data['InventarisatiesVerslagen'][$catId]['doorverwijzer_id'];
            } else {
                $doorverwijzer_id = null;
            }

            $dropdowns[$dropdown_id] = $doorverwijzer_id;

            echo $this->Form->input(
                'InventarisatiesVerslagen.'.
                $catId.'.doorverwijzer_id',
                array(
                    'options' => $options,
                    'empty' => 'doorverwezen naar:',
                    'label' => false,
                    'div' => false,
                    'id' => $dropdown_id,
                    'class' => $type,
                    'disabled' => true,
                    'value' => $doorverwijzer_id,
                )
            );
        }

        echo '</span>';

        foreach ($group as $invId => &$inv) {

            if ($invId !== 'rootName') {

                $actie = & $inv['Inventarisatie']['actie'];

                echo '<div class="node">';

                $inventarisatieId = $inv['Inventarisatie']['id'];

                echo '&nbsp;&nbsp;&nbsp;';

                for ($i = 1; $i < $inv['Inventarisatie']['depth']; $i++) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }

                $radioId = 'InventarisatiesVerslagen'.
                $inventarisatieId.'InventarisatieId';

                if ($actie !== 'N') {

                    if (
                        !empty($this->data['InventarisatiesVerslagen'][$catId]['inventarisatie_id']) &&
                        $inventarisatieId == $this->data['InventarisatiesVerslagen'][$catId]['inventarisatie_id']
                    ) {

                        $checked = ' checked="checked"';

                    } else {
                        $checked = '';
                    }

                    echo '<input id="'.$radioId.'" type="radio"
                        name="data[InventarisatiesVerslagen][' .
                                $catId.'][inventarisatie_id]" value="'.
                                $inventarisatieId.'"'.$checked.'/>'."\n";
                }

                echo '<label for="'.$radioId.'">';

                echo $inv['Inventarisatie']['titel'];

                echo '</label>';

                echo $this->Js->get('#'.$radioId)->event('change', 'treeRadioChanged("#'.$radioId.'", "'.$catId.'", "'.$actie.'")');

                if ($checked) {
                    $this->Js->buffer('treeRadioChanged("#'.$radioId.'", "'.$catId.'", "'.$actie.'", true);');
                    $dropdown_id = key($dropdowns);
                    $doorverwijzer_id = current($dropdowns);
                    $this->Js->buffer('$('.json_encode('#'.$dropdown_id).').val('.json_encode($doorverwijzer_id).');');
                }

                if (array_key_exists($actie, $doorverwijzers)) {
                    echo '<span class="dropdownContainer"></span>';
                }
                            echo '</div>';
                        }
                    }
                    echo '</fieldset>';
                }

                echo '<fieldset>';

                echo $this->Form->input('locatie_id', array(
                    'empty' => __('Overige', true), ));

                echo '</fieldset>';

                echo '<div class="divContactSoort">';

                echo $this->Form->input('contactsoort_id', array('type' => 'radio', 'legend' => __('Contactsoort', true).'*', 'div' => false));

                echo '</div>';

                echo $this->Form->input('aanpassing_verslag', array(
                    'type' => 'select',
                    'label' => 'Duur gesprek (aantal hulpverleners x aantal uren)',
                    'options' => [
                        20 => '0:20 uur',
                        40 => '0:40 uur',
                        60 => '1:00 uur',
                        80 => '1:20 uur',
                        100 => '1:40 uur',
                        120 => '2:00 uur',
                        240 => '4:00 uur',
                        360 => '6:00 uur',
                        480 => '8:00 uur',
                    ],
                ));

?>

            </div><!-- scroll-box end -->
        </div>
        <div class="cell">
            <div id="verslagen-right-col">
                <h3>Vorige verslagen</h3>
                <div class="scroll-box" id="verslagen-index">
                    <?= $this->element('verslagen_index', array(compact('verslagen'))); ?>
                </div>
                <h3>Nieuwe opmerking</h3>
                <div id="verslagen-opmerking">
                    <?php
                    echo $this->Form->input('opmerking', array(
                        'type' => 'textfield',
                        'label' => false,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="submit">
        <?php
            echo $this->Form->submit('Verslag opslaan', array('div' => false));
            echo '&nbsp;&nbsp;';
            echo $this->Html->link('Annuleren', array('action' => 'view', $klant_id));
        ?>
                </div>
    <?php
        echo $this->Form->end();
        echo $this->Js->writeBuffer();
    ?>

</div>

<script type="text/javascript">
    $('#VerslagAanpassingVerslag').parent('div').hide();
    $(function () {
        duurGesprekVisibility();
        $('.divContactSoort input').click(duurGesprekVisibility);

        function duurGesprekVisibility() {
            if ($('.divContactSoort input:checked').val() == 3) {
                $('#VerslagAanpassingVerslag').parent('div').show();
                $('.scroll-box').scrollTop(100000);

            } else {
                $('#VerslagAanpassingVerslag').parent('div').hide();
            }
        }
    });
</script>

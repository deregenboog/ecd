<?php $form = $this->Form; ?>
<?= $form->create(
    null,
    array('url' => array(
        'controller' => 'klanten',
        'action' => 'merge',
        'ids' => $ids,
    ),
)); ?>

<table id="tabKlantMerge">
    <tr>
        <th>Id</th>
        <?php foreach ($klanten as $klant): ?>
            <th>
                <?= $klant['Klant']['id'] ?><br/>
                <?= $this->Form->input('Klant.merge.', array(
                    'type' => 'checkbox',
                    'label' => 'Samenvoegen',
                    'value' => $klant['Klant']['id'],
                    'checked' => true,
                )); ?>
            </th>
        <?php endforeach; ?>
        <th>Uitkomst</th>
    </tr>
    <?php
        printKlantMergeRow($klanten, $form, 'Klant.voornaam', 'Voornaam');
        printKlantMergeRow($klanten, $form, 'Klant.tussenvoegsel', 'Tussenvoegsel');
        printKlantMergeRow($klanten, $form, 'Klant.achternaam', 'Achternaam');
        printKlantMergeRow($klanten, $form, 'Klant.roepnaam', 'Roepnaam');
        printKlantMergeRow($klanten, $form, 'Klant.geboortedatum', 'Geboortedatum', true, null, $this->Date, 'show');
        printKlantMergeRow($klanten, $form, 'Klant.BSN', 'BSN');
        printKlantMergeRow($klanten, $form, 'Klant.laatste_TBC_controle', 'Laatste TBC controle', true, null, $this->Date, 'show');
        printKlantMergeRow($klanten, $form, 'Klant.doorverwijzen_naar_amoc', 'Doorverwijzen naar Amoc', true, null, $this->Format, 'printJaNee');
        printKlantMergeRow($klanten, $form, 'Klant.MezzoID', 'MezzoID');
        printKlantMergeRow($klanten, $form, 'Geslacht.volledig', 'Geslacht', true, 'Klant.geslacht_id');
        printKlantMergeRow($klanten, $form, 'Geboorteland.land', 'Geboorteland', true, 'Klant.land_id');
        printKlantMergeRow($klanten, $form, 'Nationaliteit.naam', 'Nationaliteit', true, 'Klant.nationaliteit_id');
        printKlantMergeRow($klanten, $form, 'LasteIntake.datum_intake', 'Laatste Intake', false, null, $this->Date, 'show');
        printKlantMergeRow($klanten, $form, 'LaatsteRegistratie.binnen', 'Laatste Registratie', false, null, $this->Date, 'show');
    ?>
</table>
<?= $form->end(__('Submit', true)); ?>

<?php function printKlantMergeRow($klanten, $form, $path, $label, $showRadio = true, $radioName = null, $helper = null, $function = null) { ?>
    <tr>
        <td><b><?= $label ?></b></td>
        <?php foreach ($klanten as $klant): ?>
            <?php
                $klantId = $klant['Klant']['id'];
                $value = Set::classicExtract($klant, $path);
                if ($helper && $function):
                    $value = $helper->$function($value);
                endif;
            ?>
            <td data-klantId="<?= $klantId ?>">
                <?php if ($showRadio): ?>
                    <?php if (! $radioName): ?>
                        <?php $radioName = $path; ?>
                    <?php endif; ?>
                    <?= $form->radio($radioName, array($klantId => $value), array('hiddenField' => false)); ?>
                <?php else: ?>
                    <?= $value; ?>
                <?php endif; ?>
            </td>
        <?php endforeach; ?>
        <td></td>
<?php } ?>

<script type="text/javascript">
    $(function () {
        $('#tabKlantMerge input:radio')
            .click(radioClicked)
            .filter('*:checked').each(radioClicked);

        $('#tabKlantMerge input:checkbox').click(mergeCheckClicked);

        $('#tabKlantMerge tr').not(':first').each(function () {
            var lastText = null;
            var lastId = null;
            $(this).find('label').each(function () {
                if (! lastText || $(this).text().length > 0) {
                    lastText = $(this).text();
                    lastId = $(this).attr('for');
                }
            });
            if (lastId && lastText.length != 0) {
                $('#' + lastId).click();
            } else {
                $(this).find('input:radio:first').click();
            }
        });

        function mergeCheckClicked() {
            var klantId = $(this).attr('value');
            var radios = $('#tabKlantMerge td[data-klantId=' + klantId + ']  input:radio');
            if ($(this).is(':checked')) {
                radios.removeAttr('disabled');
            } else {
                radios
                    .attr('disabled', 'disabled')
                    .each(function () {
                        if ($(this).is(':checked')) {
                            $(this)
                                .parents('tr')
                                .find('input:radio:enabled:first')
                                .click();
                        }
                    });
            }
        }

        function radioClicked() {
            $(this).parents('tr').find('td:last').html(
                $(this).parent().find('label').html()
            );
        }
    });
</script>

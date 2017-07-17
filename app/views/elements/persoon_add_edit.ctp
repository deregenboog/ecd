<?php
    $model = $persoon_model;
    $controller = strtolower(Inflector::pluralize($persoon_model));
    if (empty($step)) {
        $step = null;
    }
    $werkgebied = '';
    if (!empty($this->data[$persoon_model]['werkgebied'])) {
        $werkgebied = $this->data[$persoon_model]['werkgebied'];
    }
    $postcodegebied = '';
    if (!empty($this->data[$persoon_model]['postcodegebied'])) {
        $postcodegebied = $this->data[$persoon_model]['postcodegebied'];
    }
?>

<?php
    if (!empty($this->data[$model]['id'])) {
        $referer = ['controller' => $controller, 'action' => 'view', $this->data[$model]['id']];
    }
    if (isset($this->data[$model]['referer'])) {
        $referer = $this->data[$model]['referer'];
    }
    if (!empty($referer)) {
        echo $this->Html->link('Terug', $referer);
    }

?>
<div class="klanten">
    <?= $this->Form->create($model, ['url' => ['step' => 4, 'generic' => true]]) ?>
    <fieldset class="twoDivs">
        <legend><?php __('Persoonsgegevens bewerken'); ?></legend>
        <div class="leftDiv">
            <?= $this->Form->hidden('referer') ?>
            <?= $this->Form->hidden('generic', ['value' => 1]) ?>
            <?= $this->Form->input('id') ?>
            <?= $this->Form->input('voornaam') ?>
            <?= $this->Form->input('tussenvoegsel') ?>
            <?= $this->Form->input('achternaam') ?>
            <?= $this->Form->input('roepnaam') ?>
            <?= $this->Form->input('geslacht_id') ?>
        </div>
        <div class="rightDiv">
            <?= $date->input("{$model}.geboortedatum", null, [
                    'label' => 'Geboortedatum',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'rangeHigh' => date('Y-m-d'),
                ]) ?>
            <?= $this->Form->input('land_id', ['label' => 'Geboorteland']) ?>
            <?= $this->Form->input('nationaliteit_id') ?>
            <?= $this->Form->input('BSN') ?>
            <?= $this->Form->input('medewerker_id', ['empty' => '']) ?>
            <?php if ($name === 'vrijwilligers'): ?>
                <?= $this->Form->input('vog_aangevraagd', ['label' => 'VOG aangevraagd']) ?>
                <?= $this->Form->input('vog_aanwezig', ['label' => 'VOG aanwezig']) ?>
                <?= $this->Form->input('overeenkomst_aanwezig', ['label' => 'Vrijwilligersovereenkomst aanwezig']) ?>
            <?php endif; ?>
        </div>
    </fieldset>
    <fieldset class="twoDivs">
        <legend><?php __('Contactgegevens bewerken'); ?></legend>
        <div class="leftDiv">
            <?= $this->Form->input('adres') ?>
            <?= $this->Form->input('postcode', ['class' => 'postcode']) ?>
            <?= $this->Form->input('werkgebied', ['type' => 'hidden', 'class' => 'werkgebied']) ?>
            <label>&nbsp;Werkgebied:</label>
            <div id="werkgebied_display"><?= $werkgebied ?></div>
            <?= $this->Form->input('postcodegebied', ['type' => 'hidden', 'class' => 'postcodegebied']) ?>
            <label>&nbsp;Postcodegebied:</label>
            <div id="postcodegebied_display"><?= $postcodegebied ?></div>
            <?= $this->Form->input('plaats') ?>
            <?= $this->Form->input('email') ?>
        </div>
        <div class="rightDiv">
            <?= $this->Form->input('mobiel') ?>
            <?= $this->Form->input('telefoon') ?>
            <?= $this->Form->input('opmerking') ?>
            <?= $this->Form->input('geen_post') ?>
            <?= $this->Form->input('geen_email') ?>
        </div>
    </fieldset>
    <?= $this->Form->submit(__('Opslaan', true), ['div' => 'submit']) ?>
    <div class="submit">
        <?php if (!empty($this->data[$model]['id']) && $user_is_administrator): ?>
            <?= $this->Html->link(__('Delete', true),
                [
                    'action' => 'disable',
                    $this->data[$model]['id'],
                ],
                ['class' => 'delete-button'],
                __('Are you sure you want to delete the client?', true)
            ) ?>
        <?php endif; ?>
     </div>
    <?= $this->Form->end() ?>
</div>

<?php
    $stadsdeelUrl = json_encode($this->Html->url(
        ['controller' => 'vrijwilligers', 'action' => 'get_stadsdeel']
    ));
    $this->Js->buffer("
        var updateArea = function() {
            var val = $(this).val();
            if (val.length >= 4) {
                $.post(
                    ".$stadsdeelUrl.",
                    {postcode: val},
                    function(data) {
                        if (data.stadsdeel) {
                            $('#".$persoon_model."Werkgebied').val(data.stadsdeel);
                            $('#werkgebied_display').text(data.stadsdeel);
                        }
                        if (data.postcodegebied) {
                            $('#".$persoon_model."Postcodegebied').val(data.postcodegebied);
                            $('#postcodegebied_display').text(data.postcodegebied);
                        }
                    },
                    'json'
                );
            }
        }
        $(document).ready(function() {
            $('.postcode').keyup(updateArea);
            $('.postcode').change(updateArea);
        });
    ");
?>

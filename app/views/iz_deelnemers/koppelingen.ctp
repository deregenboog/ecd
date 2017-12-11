<?php
    $now = strtotime(date('Y-m-d'));
    switch ($persoon_model) {
        case 'Klant':
            $label_other = 'Vrijwilliger';
            $toevoegen_label = 'Hulpvraag';
            break;
        case 'Vrijwilliger':
            $label_other = 'Klant';
            $toevoegen_label = 'Hulpaanbod';
            break;
        default:
            $label_other = 'undefined';
            $toevoegen_label = 'undefined';
            break;
    }

    $url = $this->Html->url(['controller' => 'iz_deelnemers', 'action' => 'koppelingen', $id], true);
    $startdatum = date('Y-m-d');

    if (!empty($this->data['IzKoppeling']['startdatum'])):
        if (is_array($this->data['IzKoppeling']['startdatum'])):
            if (!empty($this->data['IzKoppeling']['startdatum']['year']) && !empty($this->data['IzKoppeling']['startdatum']['month']) && !empty($this->data['IzKoppeling']['startdatum']['day'])):
                $startdatum = $this->data['IzKoppeling']['startdatum']['year'].'-'.$this->data['IzKoppeling']['startdatum']['month'].'-'.$this->data['IzKoppeling']['startdatum']['day'];
            endif;
        else:
            $startdatum = $this->data['IzKoppeling']['startdatum'];
        endif;
    endif;

    $einddatum = date('Y-m-d');
    $einddatum = null;

    if (!empty($this->data['IzKoppeling']['einddatum'])) {
        if (is_array($this->data['IzKoppeling']['einddatum'])) {
            if (!empty($this->data['IzKoppeling']['einddatum']['year']) && !empty($this->data['IzKoppeling']['einddatum']['month']) && !empty($this->data['IzKoppeling']['einddatum']['day'])) {
                $einddatum = $this->data['IzKoppeling']['einddatum']['year'].'-'.$this->data['IzKoppeling']['einddatum']['month'].'-'.$this->data['IzKoppeling']['einddatum']['day'];
            }
        } else {
            $einddatum = $this->data['IzKoppeling']['einddatum'];
        }
    }

    $show_add = 'none';

    if (isset($this->data['IzDeelnemer']['form']) && $this->data['IzDeelnemer']['form'] == 'add') {
        $show_add = 'block';
    }

    $add_icon = $html->image('add.png');
    $delete_icon = $html->image('delete.png');
?>

<h2 style="display: inline-block;"><?= $toevoegen_label; ?></h2>
<a href="#" id="addIzKoppeling"><?= $add_icon; ?></a>
<div id="IzKoppelingToevoegen" style="display : <?= $show_add; ?>;">
    <?= $this->Form->create('IzKoppeling', ['url' => $url]) ?>
    <table>
        <tbody>
            <tr>
                <td>
                    <?= $this->Form->input('project_id', ['label' => 'Project', 'options' => $activeprojects]) ?>
                    <?= $this->Form->hidden('IzDeelnemer.id', ['value' => $id]) ?>
                    <?= $this->Form->hidden('IzDeelnemer.form', ['value' => 'add']) ?>
                </td>
                <td>
                    <?= $date->input('IzKoppeling.startdatum', $startdatum, [
                        'label' => 'Start datum',
                        'rangeHigh' => (date('Y') + 10).date('-m-d'),
                        'rangeLow' => (date('Y') - 19).date('-m-d'),
                    ]) ?>
                    <br/>
                    <?= $this->Form->hidden('IzDeelnemer.einddatum', ['value' => null]) ?>
                </td>
                <td>
                    <?= $this->Form->input('medewerker_id', ['label' => 'Coordinator']) ?>
                    <?= $this->Form->submit('Toevoegen') ?>
                </td>
            </tr>
        </tbody>
    </table>
    <?= $this->Form->end(); ?>
</div>

<table>
    <thead>
        <tr>
            <th>Project</th>
            <th>Vraag Startdatum<br/>Coordinator</th>
            <th>Verslag</th>
            <?php if ($persoon_model == 'Klant'): ?>
                <th>Koppelen aan<br/><?= $label_other; ?></th>
            <?php endif; ?>
            <th>Afsluiten</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($koppelingen as $key => $koppeling):
            $tmp_selectableprojects = $selectableprojects;
            $tmp_selectableprojects[$koppeling['IzKoppeling']['project_id']] = $projects[$koppeling['IzKoppeling']['project_id']];
            $tmp_selectableprojects = $activeprojects;

            unset($tmp_selectableprojects['']);

            $startdatum = date('Y-m-d');

            if (!empty($koppeling['IzKoppeling']['startdatum'])) {
                if (is_array($koppeling['IzKoppeling']['startdatum'])) {
                    if (!empty($koppeling['IzKoppeling']['startdatum']['year']) && !empty($koppeling['IzKoppeling']['startdatum']['month']) && !empty($koppeling['IzKoppeling']['startdatum']['day'])) {
                        $startdatum = $koppeling['IzKoppeling']['startdatum']['year'].'-'.$koppeling['IzKoppeling']['startdatum']['month'].'-'.$koppeling['IzKoppeling']['startdatum']['day'];
                    }
                } else {
                    $startdatum = $koppeling['IzKoppeling']['startdatum'];
                }
            }

            $startdatum_koppeling = null;

            if (!empty($koppeling['IzKoppeling']['startdatum_koppeling'])) {
                if (is_array($koppeling['IzKoppeling']['startdatum_koppeling'])) {
                    if (!empty($koppeling['IzKoppeling']['startdatum_koppeling']['year']) && !empty($koppeling['IzKoppeling']['startdatum_koppeling']['month']) && !empty($koppeling['IzKoppeling']['startdatum_koppeling']['day'])) {
                        $startdatum_koppeling = $koppeling['IzKoppeling']['startdatum_koppeling']['year'].'-'.$koppeling['IzKoppeling']['startdatum_koppeling']['month'].'-'.$koppeling['IzKoppeling']['startdatum_koppeling']['day'];
                    }
                } else {
                    $startdatum_koppeling = $koppeling['IzKoppeling']['startdatum_koppeling'];
                }
            }

            $url_aanpassen = $this->Html->url(['controller' => 'iz_deelnemers', 'action' => 'koppeling_aanpassen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']], true);
            $url_verslag = $this->Html->url(['controller' => 'iz_deelnemers', 'action' => 'verslagen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']], true);

            if ($koppeling['IzKoppeling']['section'] != 0) {
                continue;
            }

            $this->data = $koppeling;
            $this->Form->data = $koppeling;
        ?>
            <?= $this->Form->create('IzKoppeling', ['url' => $url_aanpassen, 'id' => $id]); ?>
            <tr>
                <td>
                    <?= $this->Form->input('IzKoppeling.project_id', ['label' => '', 'options' => $tmp_selectableprojects, 'value' => $koppeling['IzKoppeling']['project_id']]); ?>
                </td>
                <td>
                    <?= $date->input('IzKoppeling.startdatum', $startdatum, [
                        'id' => 'startdatum'.$koppeling['IzKoppeling']['project_id'],
                        'label' => '',
                        'rangeHigh' => (date('Y') + 10).date('-m-d'),
                        'rangeLow' => (date('Y') - 19).date('-m-d'),
                    ]); ?>
                    <br/>
                    <?= $this->Form->input('medewerker_id', ['label' => '', 'style' => 'width: 100px']) ?>
                    <br/>
                    <?php //$viewmedewerkers[$this->data['IzKoppeling']['medewerker_id']] ?>
                    <?= $this->Form->submit(); ?>
                </td>
                <?= $this->Form->end(); ?>
                <td>
                    <?php //$date->show($this->data['IzKoppeling']['einddatum'], array('short'=>true));?>
                    <?= $html->link('Verslag', $url_verslag); ?>
                </td>
                <?php if ($persoon_model == 'Klant'): ?>
                    <td>
                        <?= $this->Form->create('IzKoppeling', ['url' => $url]) ?>
                        <?= $this->Form->hidden('id'); ?>
                        <?= $this->Form->hidden('IzDeelnemer.id', ['value' => $id])?>
                        <?= $this->Form->hidden('IzDeelnemer.form', ['value' => 'koppel'])?>
                        <?= $date->input('IzKoppeling.koppeling_startdatum', $startdatum_koppeling,
                            [
                                'id' => 'koppeling_startdatum'.$koppeling['IzKoppeling']['project_id'],
                                'label' => '',
                                'rangeHigh' => (date('Y') + 10).date('-m-d'),
                                'rangeLow' => (date('Y') - 19).date('-m-d'),
                            ]); ?>
                        <br/>
                        <?= $this->Form->input('iz_koppeling_id', [
                           'type' => 'select',
                            'label' => '',
                            'options' => ['' => ''] + $this->data['iz_koppeling_id'],
                            'style' => 'width: 100px',
                        ]); ?>
                        <?= $this->Form->submit('Koppelen'); ?>
                        <?= $this->Form->end(); ?>
                    </td>
                <?php endif; ?>
                <td width='200px' id="editvakoppeling<?= $key; ?>">
                    <a href="#" id="eindeVaIzKoppeling<?= $key; ?>"  class='editvakoppeling'><?= $delete_icon; ?></a>
                    <div id="VaIzKoppelingAfsluitElement<?= $key; ?>" style="display: none;">
                        <?= $this->element('../iz_deelnemers/koppelingen_vraag_aanbod_afsluiten', ['key' => $key]); ?>
                    </div>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<br/>

<h2 style="display: inline-block;">Actieve koppelingen</h2>
<table>
    <thead>
        <tr>
            <th>Project</th>
            <th>Startdatum</th>
            <th>Einddatum</th>
            <th><?= $label_other ?></th>
            <th>Verslag</th>
            <th>Coordinator</th>
            <?php if ($persoon_model != 'Vrijwilliger'): ?>
                <th>Afsluiten</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($koppelingen as $key => $koppeling): ?>
            <?php if ($koppeling['IzKoppeling']['section'] != 2): ?>
                <?php continue; ?>
            <?php endif; ?>

            <?php
                $url_aanpassen = $this->Html->url(['controller' => 'iz_deelnemers', 'action' => 'koppeling_aanpassen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']], true);
                $url_verslag = $this->Html->url(['controller' => 'iz_deelnemers', 'action' => 'verslagen', $id, 'iz_koppeling_id' => $koppeling['IzKoppeling']['id']], true);

                $url = ['action' => 'koppelingen',  $koppeling['IzKoppeling']['iz_deelnemer_id_of_other']];
                $this->data = $koppeling;

                $this->Form->data = $koppeling;
                $koppeling_startdatum = date('Y-m-d');
            ?>

            <?php if (!empty($koppeling['IzKoppeling']['koppeling_startdatum'])): ?>
                <?php if (is_array($koppeling['IzKoppeling']['koppeling_startdatum'])): ?>
                    <?php if (!empty($koppeling['IzKoppeling']['koppeling_startdatum']['year'])
                        && !empty($koppeling['IzKoppeling']['koppeling_startdatum']['month'])
                        && !empty($koppeling['IzKoppeling']['koppeling_startdatum']['day'])
                    ): ?>
                        <?php $koppeling_startdatum = $koppeling['IzKoppeling']['koppeling_startdatum']['year'].'-'.$koppeling['IzKoppeling']['koppeling_startdatum']['month'].'-'.$koppeling['IzKoppeling']['koppeling_startdatum']['day']; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php $koppeling_startdatum = $koppeling['IzKoppeling']['koppeling_startdatum']; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?= $this->Form->create('IzKoppeling', ['url' => $url_aanpassen]) ?>
            <tr>
                <td>
                    <?php if (isset($projects[$this->data['IzKoppeling']['project_id']])): ?>
                        <?= $projects[$this->data['IzKoppeling']['project_id']] ?>
                    <?php else: ?>
                        onbekend
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($persoon_model != 'Klant'): ?>
                        <?= $date->show($this->data['IzKoppeling']['koppeling_startdatum'], ['short' => true]) ?>
                    <?php else: ?>
                        <?= $date->input('IzKoppeling.koppeling_startdatum', $koppeling_startdatum, [
                            'id' => 'koppeling_startdatum'.$koppeling['IzKoppeling']['project_id'],
                            'label' => '',
                            'rangeHigh' => (date('Y') + 10).date('-m-d'),
                            'rangeLow' => (date('Y') - 19).date('-m-d'),
                        ]) ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?= $date->show($this->data['IzKoppeling']['koppeling_einddatum'], ['short' => true]); ?>
                </td>
                <td>
                    <?= $html->link($koppeling['IzKoppeling']['name_of_koppeling'], $url); ?>
                </td>
                <td>
                    <?= $html->link('Verslag', $url_verslag); ?>
                </td>
                <td>
                    <?= $this->Form->input('medewerker_id', ['label' => '', 'style' => 'width: 100px']); ?>
                    <?= $this->Form->submit('Aanpassen'); ?>
                    <?= $this->Form->end(); ?>
                </td>
                <?php if ($persoon_model == 'Klant'): ?>
                    <td width='200px' id="editkoppeling<?= $key; ?>">
                        <a href="#" id="eindeIzKoppeling<?= $key; ?>"  class='editkoppeling'><?= $delete_icon; ?></a>
                        <div id="IzKoppelingAfsluitElement<?= $key; ?>" style="display: none;">
                            <?= $this->element('../iz_deelnemers/koppelingen_afsluiten', ['key' => $key]); ?>
                        </div>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br/>
<?= $this->element('../iz_deelnemers/koppelingen_vraag_aanbod_afgesloten') ?>

<br/>
<?= $this->element('../iz_deelnemers/koppelingen_afgesloten', ['label_other' => $label_other]) ?>

<?php $this->Js->buffer(<<<EOS
    Ecd.add_koppeling = function() {
        function addhandlers() {
            $('#addIzKoppeling').click(function(){
                $('#IzKoppelingToevoegen').toggle();
                if($('#IzKoppelingToevoegen').is(':hidden')) {
                    $('#IzKoppelingProjectId').val('');
                }
            });

        }
        addhandlers();
    }

    Ecd.edit_koppeling = function() {
        function addhandlers() {
            $('.editkoppeling').each(function(index) {
                id = $(this).attr("id").substring(16);
                id = '#IzKoppelingAfsluitElement'+id;
                $(this).click({id: id},function(c) {
                    $('.editkoppeling').each(function(index) {
                        id = $(this).attr("id").substring(16);
                        if ( id != c.data.id) {
                            $(id).hide();
                        }
                    });
                    $(c.data.id).toggle();
                });
            });
            $('.editvakoppeling').each(function(index) {
                id = $(this).attr("id").substring(18);
                id = '#VaIzKoppelingAfsluitElement'+id;
                $(this).click({id: id},function(c) {
                    $('.editkoppeling').each(function(index) {
                        id = $(this).attr("id").substring(16);
                        id = '#VaIzKoppelingAfsluitElement'+id;
                        if ( id != c.data.id) {
                            $(id).hide();
                        }
                    });
                    $(c.data.id).toggle();
                });
            });
        }
        addhandlers();
    }

    $('.unlock_koppeling').click(function(){
        return confirm("Koppeling heropenen?");
    });

    Ecd.add_koppeling();
    Ecd.edit_koppeling();
EOS
);


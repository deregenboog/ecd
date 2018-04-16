<?= $this->element('iz_subnavigation') ?>

<?php
    $upload_url = ['controller' => 'iz_deelnemers', 'action' => 'upload', $id];
    $iz_documents = [];
    if (!empty($persoon['IzDeelnemer']['IzDeelnemerDocument'])) {
        $iz_documents = $persoon['IzDeelnemer']['IzDeelnemerDocument'];
    }
?>

<h2><?= $persoon_model ?></h2>
<div class="vrijwilligers">
    <div class="actions">
        <?= $this->element('persoon_view_basic', [
            'name' => $persoon_model,
            'data' => $persoon,
            'show_documents' => false,
            'document_model' => 'IzDeelnemer',
            'view' => $this,
            'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
        ]) ?>
        <?= $this->element('diensten', ['diensten' => $diensten]) ?>

        <?php if (!empty($id)): ?>
            <?= $this->element('persoon_zrm', [
                'persoon_model' => $persoon_model,
                'persoon' => $persoon,
            ]) ?>
            <br>
            <?= $this->element('persoon_document', [
                'upload_url' => $upload_url,
                'documents' => $iz_documents,
                'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
            ]) ?>
        <?php endif; ?>

        <div class="print-invisible"></div>
    </div>

    <div class="klanten view">
        <ul class="tabs" id="tabbed_view">
            <li class="<?= in_array($this->action, ['aanmelding', 'toon_aanmelding']) ? 'tab_acted' : '' ?>">
                <?= $html->link('Aanmelding', [
                    'action' => 'toon_aanmelding',
                    $persoon_model,
                    $persoon[$persoon_model]['id'],
                    $id,
                ]) ?>
            </li>
            <?php if (!empty($id)): ?>
                <li class="<?= in_array($this->action, ['intakes', 'toon_intakes']) ? 'tab_acted' : '' ?>">
                    <?= $html->link('Intake', [
                        'action' => 'toon_intakes',
                        $id,
                    ]); ?>
                </li>
                <?php if (!empty($iz_intake)): ?>
                    <?php if ($persoon_model == 'Vrijwilliger'): ?>
                        <li class="<?= $this->action == 'vrijwilliger_intervisiegroepen' ? 'tab_acted' : '' ?>">
                            <?= $html->link('Intervisiegroepen', [
                                'action' => 'vrijwilliger_intervisiegroepen',
                                $id,
                            ]); ?>
                        </li>
                    <?php endif; ?>
                    <li class="<?= $this->action == 'verslagen_persoon' ? 'tab_acted' : '' ?>">
                        <?= $html->link('Verslagen', ['action' => 'verslagen_persoon', $id]); ?>
                    </li>
                    <li class="<?= $this->action == 'koppelingen' ? 'tab_acted' : '' ?>">
                        <?= $html->link('Koppelingen', ['action' => 'koppelingen', $id]); ?>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (!empty($id)): ?>
                <li class="<?= $this->action == 'afsluiting' ? 'tab_acted' : '' ?>">
                    <?= $html->link('Afsluiting', [
                        'action' => 'afsluiting',
                        $id,
                    ]); ?>
                </li>
            <?php endif; ?>
        </ul>
        <br><br>

        <?php if ($is_afgesloten): ?>
            <p style="color: red;"><?= $persoon_model?> is afgesloten</p>
        <?php endif; ?>

        <div id='iz_content'>
            <?php
                if (empty($id)):
                    $this->action = 'aanmelding';
                endif;

                switch ($this->action):
                    case 'aanmelding':
                        echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_aanmelding');
                        break;
                    case 'toon_aanmelding':
                        echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_toon_aanmelding');
                        break;
                    case 'intakes':
                        echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_intakes');
                        break;
                    case 'toon_intakes':
                        if (empty($this->data['IzIntake'])) {
                            echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_intakes');
                        } else {
                            echo $this->element('../iz_deelnemers/'.strtolower($persoon_model).'_toon_intakes');
                        }
                        break;
                    case 'vrijwilliger_intervisiegroepen':
                        echo $this->element('../iz_deelnemers/vrijwilliger_intervisiegroepen');
                        break;
                    case 'verslagen':
                        echo $this->element('../iz_deelnemers/verslagen');
                        break;
                    case 'verslagen_persoon':
                        echo $this->element('../iz_deelnemers/verslagen');
                        break;
                    case 'koppelingen':
                        echo $this->element('../iz_deelnemers/koppelingen');
                        break;
                    case 'afsluiting':
                        echo $this->element('../iz_deelnemers/afsluiting');
                        break;
                endswitch;
            ?>
        </div>
    </div>
</div>

<?php
    $this->Js->buffer(<<<EOS
        Ecd.disable_all = function(active) {
            if (active) {
                $('#iz_content').find('*:input').each(function () {
                    $(this).attr('disabled', true);
                });
                $('.unlock_koppeling').each(function () {
                    $(this).hide();
                });
            }
        }
        Ecd.disable_all('{$is_afgesloten}');
EOS
);
?>

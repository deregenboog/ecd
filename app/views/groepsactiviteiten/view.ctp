<?= $this->element('groepsactiviteiten_subnavigation') ?>

<?php $upload_url = array(
    'controller' => 'groepsactiviteiten',
    'action' => 'upload',
    $persoon_model,
    $persoon[$persoon_model]['id'],
); ?>

<h2><?= $persoon_model; ?></h2>

<div class="vrijwilligers">
    <div class="actions">
        <?= $this->element(
            'persoon_view_basic',
            [
                'name' => $persoon_model,
                'data' => $persoon,
                'show_documents' => false,
                'view' => $this,
                'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
            ]
        ); ?>
        <?= $this->element('diensten', ['diensten' => $diensten])?>
        <?= $this->element('persoon_document', [
                'upload_url' => $upload_url,
                'documents' => $persoon['GroepsactiviteitenDocument'],
                'logged_in_user_id' => $this->Session->read('Auth.Medewerker.id'),
        ]) ?>
        <div class="print-invisible">
        </div>
    </div>

    <div class="klanten view">
        <ul class="tabs" id="tabbed_view">
            <?php if ($persoon_model == 'Klant'): ?>
                <li class="<?= $this->action == 'intakes' ? 'tab_acted' : '' ?>">
                    <?= $html->link('Intakes', array(
                        'action' => 'intakes',
                        $persoon_model,
                        $persoon[$persoon_model]['id'],
                    )); ?>
                </li>
            <?php endif; ?>
            <li class="<?= $this->action == 'verslagen' ? 'tab_acted' : '' ?>">
                <?= $html->link('Verslagen', array(
                    'action' => 'verslagen',
                    $persoon_model,
                    $persoon[$persoon_model]['id'],
                )) ?>
            </li>
            <li class="<?= $this->action == 'groepen' ? 'tab_acted' : '' ?>">
                <?= $html->link('Groepen', array(
                    'action' => 'groepen',
                    $persoon_model,
                    $persoon[$persoon_model]['id'],
                )); ?>
            </li>
            <li class="<?= $this->action == 'activiteiten' ? 'tab_acted' : '' ?>">
                <?= $html->link('Activiteiten', array(
                    'action' => 'activiteiten',
                    $persoon_model,
                    $persoon[$persoon_model]['id'],
                )) ?>
            </li>
            <li class="<?= $this->action == 'afsluiting' ? 'tab_acted' : '' ?>">
                <?= $html->link('Afsluiting', array(
                    'action' => 'afsluiting',
                    $persoon_model,
                    $persoon[$persoon_model]['id'],
                )); ?>
            </li>
        </ul>

        <br>
        <br>
        <?php if ($is_afgesloten): ?>
            <p style="color: red;"><?= $persoon_model?> is afgesloten</p>
        <?php endif; ?>

        <div id='content'>
            <?php
                switch ($this->action):
                    case 'intakes':
                        if ($persoon_model == 'Vrijwilliger') {
                            echo $this->element('../groepsactiviteiten/vrijwilliger_intake');
                        } else {
                            echo $this->element('../groepsactiviteiten/klant_intake');
                        }
                        break;

                    case 'verslagen':
                        echo $this->element('../groepsactiviteiten/verslagen');
                        break;

                    case 'groepen':
                        echo $this->element('../groepsactiviteiten/groepen');
                        break;

                    case 'activiteiten':
                        echo $this->element('../groepsactiviteiten/activiteiten');
                        break;

                    case 'afsluiting':
                        echo $this->element('../groepsactiviteiten/afsluiting');
                        break;
                endswitch;
            ?>
        </div>
    </div>
</div>

<?php $this->Js->buffer(<<<EOS
    Ecd.disable_all = function(active) {
        if(active) {
            $('#content').find('*:input').each(function () {
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

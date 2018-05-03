<script type="text/javascript">
    var amocCountries = <?= json_encode($amocCountries) ?>;
</script>

<?php echo $this->Html->link('Terug naar klantoverzicht', ['controller' => 'klanten', 'action' => 'view', $this->data['Klant']['id']]); ?>

<div class="klanten">
    <?= $this->Form->create('Klant') ?>
    <fieldset class="twoDivs">
        <legend><?php __('Klant persoonsgegevens bewerken'); ?></legend>
        <div class="leftDiv">
            <?= $this->Form->input('id') ?>
            <?= $this->Form->hidden('referer') ?>
            <?= $this->Form->input('voornaam') ?>
            <?= $this->Form->input('tussenvoegsel') ?>
            <?= $this->Form->input('achternaam') ?>
            <?= $this->Form->input('roepnaam') ?>
            <?= $this->Form->input('geslacht_id') ?>
            <?= $this->Form->input('overleden', ['type' => 'checkbox', 'label' => 'Overleden']) ?>
        </div>
        <div class="rightDiv">
            <?= $date->input('Klant.geboortedatum', null,
                [
                    'label' => 'Geboortedatum',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'rangeHigh' => date('Y-m-d'),
                ]
            ) ?>
            <?= $this->Form->input('land_id', ['label' => 'Geboorteland']) ?>
            <?= $this->Form->input('doorverwijzen_naar_amoc', [
                'label' => __('Ik wil deze persoon wegens taalproblemen doorverwijzen naar AMOC', true),
            ]) ?>
            <div class="amocLandWarning">
                <?= __('Personen uit dit land worden doorgestuurd naar AMOC.', true) ?>
                <?= $this->Html->link(__('Verwijsbrief printen', true),
                    ['action' => 'printLetter', $this->data['Klant']['id']],
                    ['target' => '_blank']
                ) ?>
                <?= $this->Form->input('nationaliteit_id') ?>
                <?= $this->Form->input('BSN') ?>
                <?= $date->input('Klant.laatste_TBC_controle', null,
                    [
                        'label' => 'Laatste TBC controle',
                        'rangeLow' => (date('Y') - 20).date('-m-d'),
                        'rangeHigh' => date('Y-m-d'),
                    ]
                ) ?>
                <?= $this->Form->input('medewerker_id', ['empty' => '']) ?>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->submit(__('Opslaan', true), ['div' => 'submit']); ?>
     <div class="submit">
        <?php if (false /* disabled */ && $user_is_administrator): ?>
            <?= $this->Html->link(__('Delete', true),
                ['action' => 'disable', $this->data['Klant']['id']],
                ['class' => 'delete-button'],
                __('Are you sure you want to delete the client?', true)
            ); ?>
        <?php endif; ?>
     </div>
    <?= $this->Form->end(); ?>
</div>

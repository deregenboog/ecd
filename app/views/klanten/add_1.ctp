<script type="text/javascript">
    var amocCountries = <?= json_encode($amocCountries) ?>;
</script>
<div class="klanten">
    <?= $this->Form->create('Klant', ['url' => ['step' => 1, 'generic'=>$generic]]); ?>
        <fieldset class="twoDivs">
            <legend><?php __('Persoonsgegevens nieuwe klant, stap 1'); ?></legend>
            <div class="leftDiv">
                <?= $this->Form->hidden('referer'); ?>
                <?= $this->Form->input('voornaam'); ?>
                <?= $this->Form->input('tussenvoegsel'); ?>
                <?= $this->Form->input('achternaam'); ?>
                <?= $date->input('Klant.geboortedatum', 'empty', [
                    'label' => 'Geboortedatum',
                    'rangeLow' => (date('Y') - 100).date('-m-d'),
                    'rangeHigh' => date('Y-m-d'),
                ]); ?>
            </div>
        </fieldset>
    <?= $this->Form->end(__('Volgende', true)); ?>
</div>

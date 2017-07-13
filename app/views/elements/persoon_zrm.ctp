<fieldset>
    <legend>ZRM</legend>
    <p>
        <?= $this->Html->link('ZRM overzicht', [
            'controller' => 'klanten',
            'action' => 'zrm',
            $persoon[$persoon_model]['id'],
        ]) ?>
        <br>
        <?= $this->Html->link('ZRM toevoegen', [
            'controller' => 'klanten',
            'action' => 'zrm_add',
            $persoon[$persoon_model]['id'],
        ]) ?>
        <br>
    </p>
</fieldset>

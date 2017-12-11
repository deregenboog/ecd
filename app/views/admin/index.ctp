<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?= $this->Html->link(__('ZRM configuratie', true), array('controller' => 'ZrmSettings', 'action' => 'matrix')) ?></li>
        <li><?= $this->Html->link(__('Medewerkers uit dienst', true), array( 'action' => 'uit_dienst')) ?></li>
        <li><?= $this->Html->link(__('Cache', true), array('controller' => 'medewerkers', 'action' => 'clear_cache')) ?></li>
        <li><?= $this->Html->link(__('ZRM intitalisatie', true), array('controller' => 'ZrmSettings', 'action' => 'update_table')) ?></li>
        <li><?= $this->Html->link(__('Models', true), array( 'action' => 'edit_models')) ?></li>
        <li><?= $this->Html->link(__('PHP info', true), array( 'action' => 'phpinfo')) ?></li>
    </ul>
</div>

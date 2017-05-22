<div class="locaties form">
<?php echo $this->Form->create('Locatie');?>
    <fieldset>
        <legend><?php __('Add Locatie'); ?></legend>
    <?php
        echo $this->Form->input('naam');
        echo $this->Form->input('datum_van');
        echo $this->Form->input('datum_tot');
        echo $this->Form->input('nachtopvang');
        echo $this->Form->input('gebruikersruimte');
        echo $this->Form->input('maatschappelijkwerk');
        echo $this->Form->input('tbc_check');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Html->link(__('List Locaties', true), array('action' => 'index'));?></li>
    </ul>
</div>

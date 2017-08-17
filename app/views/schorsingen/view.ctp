<div class="schorsingen view">
<h2><?php  __('Schorsing');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $schorsing['Schorsing']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Datum Van'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $schorsing['Schorsing']['datum_van']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Datum Tot'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $schorsing['Schorsing']['datum_tot']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Locatie'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $this->Html->link($schorsing['Locatie']['naam'], array('controller' => 'locaties', 'action' => 'view', $schorsing['Locatie']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Klant'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $this->Html->link($schorsing['Klant']['id'], array('controller' => 'klanten', 'action' => 'view', $schorsing['Klant']['id'])); ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Remark'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $schorsing['Schorsing']['remark']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Created'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $schorsing['Schorsing']['created']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Modified'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $schorsing['Schorsing']['modified']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Schorsing', true), array('action' => 'edit', $schorsing['Schorsing']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('Delete Schorsing', true), array('action' => 'delete', $schorsing['Schorsing']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $schorsing['Schorsing']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Schorsingen', true), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Schorsing', true), array('action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Locaties', true), array('controller' => 'locaties', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Locatie', true), array('controller' => 'locaties', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Klanten', true), array('controller' => 'klanten', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Klant', true), array('controller' => 'klanten', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Redenen', true), array('controller' => 'redenen', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Reden', true), array('controller' => 'redenen', 'action' => 'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php __('Related Redenen');?></h3>
    <?php if (!empty($schorsing['Reden'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Naam'); ?></th>
        <th><?php __('Created'); ?></th>
        <th><?php __('Modified'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($schorsing['Reden'] as $reden):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $reden['id'];?></td>
            <td><?php echo $reden['naam'];?></td>
            <td><?php echo $reden['created'];?></td>
            <td><?php echo $reden['modified'];?></td>
            <td class="actions">
                <?php echo $this->Html->link(__('View', true), array('controller' => 'redenen', 'action' => 'view', $reden['id'])); ?>
                <?php echo $this->Html->link(__('Edit', true), array('controller' => 'redenen', 'action' => 'edit', $reden['id'])); ?>
                <?php echo $this->Html->link(__('Delete', true), array('controller' => 'redenen', 'action' => 'delete', $reden['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $reden['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Reden', true), array('controller' => 'redenen', 'action' => 'add'));?> </li>
        </ul>
    </div>
</div>

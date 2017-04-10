<div class="pfoVerslagen view">
<h2><?php  __('Pfo Verslag');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $pfoVerslag['PfoVerslag']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Contact Type'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo $pfoVerslag['PfoVerslag']['contact_type']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) {
    echo $class;
}?>><?php __('Verslag'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) {
    echo $class;
}?>>
            <?php echo utf8_encode($pfoVerslag['PfoVerslag']['verslag']); ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Pfo Verslag', true), array('action' => 'edit', $pfoVerslag['PfoVerslag']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('Delete Pfo Verslag', true), array('action' => 'delete', $pfoVerslag['PfoVerslag']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $pfoVerslag['PfoVerslag']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Pfo Verslagen', true), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Pfo Verslag', true), array('action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Pfo Clienten Pfo Verslagen', true), array('controller' => 'pfo_clienten_pfo_verslagen', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Pfo Clienten Pfo Verslag', true), array('controller' => 'pfo_clienten_pfo_verslagen', 'action' => 'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php __('Related Pfo Clienten Pfo Verslagen');?></h3>
    <?php if (!empty($pfoVerslag['PfoClientenVerslag'])):?>
    <table cellpadding = "0" cellspacing = "0">
    <tr>
        <th><?php __('Id'); ?></th>
        <th><?php __('Pfo Client Id'); ?></th>
        <th><?php __('Pfo Verslag Id'); ?></th>
        <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
        $i = 0;
        foreach ($pfoVerslag['PfoClientenVerslag'] as $pfoClientenVerslag):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
        <tr<?php echo $class;?>>
            <td><?php echo $pfoClientenVerslag['id'];?></td>
            <td><?php echo $pfoClientenVerslag['pfo_client_id'];?></td>
            <td><?php echo $pfoClientenVerslag['pfo_verslag_id'];?></td>
            <td class="actions">
                <?php echo $this->Html->link(__('View', true), array('controller' => 'pfo_clienten_verslagen', 'action' => 'view', $pfoClientenVerslag['id'])); ?>
                <?php echo $this->Html->link(__('Edit', true), array('controller' => 'pfo_clienten_verslagen', 'action' => 'edit', $pfoClientenVerslag['id'])); ?>
                <?php echo $this->Html->link(__('Delete', true), array('controller' => 'pfo_clienten_verslagen', 'action' => 'delete', $pfoClientenVerslag['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $pfoClientenVerslag['id'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__('New Pfo Clienten Pfo Verslag', true), array('controller' => 'pfo_clienten_pfo_verslagen', 'action' => 'add'));?> </li>
        </ul>
    </div>
</div>

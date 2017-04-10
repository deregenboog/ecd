<div class="pfoVerslagen index">
    <h2><?php __('Pfo Verslagen');?></h2>
    <table cellpadding="0" cellspacing="0">
    <tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('contact_type');?></th>
            <th><?php echo $this->Paginator->sort('verslag');?></th>
            <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($pfoVerslagen as $pfoVerslag):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
    <tr<?php echo $class;?>>
        <td><?php echo $pfoVerslag['PfoVerslag']['id']; ?>&nbsp;</td>
        <td><?php echo $pfoVerslag['PfoVerslag']['contact_type']; ?>&nbsp;</td>
        <td><?php echo utf8_encode($pfoVerslag['PfoVerslag']['verslag']); ?>&nbsp;</td>
        <td class="actions">
            <?php echo $this->Html->link(__('View', true), array('action' => 'view', $pfoVerslag['PfoVerslag']['id'])); ?>
            <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $pfoVerslag['PfoVerslag']['id'])); ?>
            <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $pfoVerslag['PfoVerslag']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $pfoVerslag['PfoVerslag']['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
    ));
    ?>	</p>

    <div class="paging">
        <?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
     |	<?php echo $this->Paginator->numbers();?>
 |
        <?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
    </div>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Pfo Verslag', true), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Pfo Clienten Pfo Verslagen', true), array('controller' => 'pfo_clienten_pfo_verslagen', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Pfo Clienten Pfo Verslag', true), array('controller' => 'pfo_clienten_pfo_verslagen', 'action' => 'add')); ?> </li>
    </ul>
</div>

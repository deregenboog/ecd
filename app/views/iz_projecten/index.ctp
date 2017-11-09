<?php echo $this->element('iz_subnavigation'); ?>
<?php echo $this->element('iz_beheer_subnavigation'); ?>
<?php
        $wrench = $html->image('add.png');
        $url = array('action' => 'add');
        $opts = array('escape' => false, 'title' => __('add', true));
        echo $html->link($wrench, $url, $opts);
        echo $this->Html->link(__('Nieuw project', true), array('action' => 'add'));
?>
<div>&nbsp;</div>
<div class="izProjecten ">
    <h2><?php __('Iz Projecten');?></h2>
    <table cellpadding="0" cellspacing="0">
    <tr>
            <th><?php echo $this->Paginator->sort('naam');?></th>
            <th><?php echo $this->Paginator->sort('startdatum');?></th>
            <th><?php echo $this->Paginator->sort('einddatum');?></th>
            <th><?php echo $this->Paginator->sort('heeft_koppelingen');?></th>
            <th><?php echo $this->Paginator->sort('Prestatieberekening', 'prestatie_strategy');?></th>
            <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($izProjecten as $izProject):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
    <tr<?php echo $class;?>>
        <td><?php echo $izProject['IzProject']['naam']; ?>&nbsp;</td>
        <td><?php echo $izProject['IzProject']['startdatum']; ?>&nbsp;</td>
        <td><?php echo $izProject['IzProject']['einddatum']; ?>&nbsp;</td>
        <td><?php echo $izProject['IzProject']['heeft_koppelingen'] == 1 ? 'Ja' : 'Nee' ; ?>&nbsp;</td>
        <td><?php echo $izProject['IzProject']['prestatie_strategy'] == IzBundle\Entity\IzProject::STRATEGY_PRESTATIE_STARTED ? 'Gestart' : 'Totaal' ; ?>&nbsp;</td>
        <td class="actions">
            <?php
                $wrench = $html->image('wrench.png');
                $url = array('action' => 'edit', $izProject['IzProject']['id']);
                $opts = array('escape' => false, 'title' => __('edit', true));
                echo $html->link($wrench, $url, $opts);
            ?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => 'Pagina %page% van %pages%, met %current% records van %count% totaal, beginnend op record %start%, eindigend op %end%',
    ));
    ?>	</p>

    <div class="paging">
        <?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
     |	<?php echo $this->Paginator->numbers();?>
 |
        <?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
    </div>
</div>

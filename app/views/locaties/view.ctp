<div class="locaties view">
    <h2><?= __('Locatie') ?></h2>
    <dl>
        <dt><?php __('Id'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['id'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Naam'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['naam'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Datum Van'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['datum_van'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Datum Tot'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['datum_tot'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Nachtopvang'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['nachtopvang'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Gebruikersruimte'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['gebruikersruimte'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Maatschappelijk Werk'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['maatschappelijkwerk'] ?>
            &nbsp;
        </dd>
        <dt><?php __('TBC Check'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['tbc_check'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Created'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['created'] ?>
            &nbsp;
        </dd>
        <dt><?php __('Modified'); ?></dt>
        <dd>
            <?= $locatie['Locatie']['modified'] ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Locatie', true), array('action' => 'edit', $locatie['Locatie']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('Delete Locatie', true), array('action' => 'delete', $locatie['Locatie']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $locatie['Locatie']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Locaties', true), array('action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Locatie', true), array('action' => 'add')); ?> </li>
    </ul>
</div>

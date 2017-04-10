<?= $this->element('pfo_subnavigation') ?>
<?php
    $support_group = array();
    foreach ($pfoClient['PfoClientenSupportgroup'] as $sg) {
        $support_group[$sg['pfo_supportgroup_client_id']] = $clienten[$sg['pfo_supportgroup_client_id']];
    }
?>

<div class="actions">
    <?= $this->element('pfobasic', array('data' => $pfoClient)); ?>
    <?= $this->element('pfodocuments', array('data' => $pfoClient, 'group' => Attachment::GROUP_PFO));?>
</div>

<div class="pfoClients view">
    <?php
        $found = null;
        foreach ($pfoClient['CompleteGroup'] as $value) {
            if ($value == $pfoClient['PfoClient']['id']) {
                continue;
            }

            if ($found == null) {
                echo "<h2>Dit profiel is gekoppeld aan </h2>";
                $found = true;
            }

            $main = "";
            if ($pfoClient['hoofd_client_id'] == $value) {
                $main = " (hoofdclient)";
            }

            $link = $this->Html->link($clienten[$value].$main, array(
                'action' => 'view',
                $value,
            ));

            echo $link." &nbsp;";
        }
    ?>
    <div>&nbsp;</div>

    <?= $this->element('pfo_verslag', ['pfo_client' => $pfoClient]) ?>
    <div>&nbsp;</div>
    <?php foreach ($pfoClient['PfoVerslag'] as $verslag): ?>
        <?= $this->element('pfo_verslag', ['data' => $verslag, 'pfo_client' => $pfoClient]) ?>
    <?php endforeach; ?>
</div>

<?php
    if (! empty($data)) {
        $zrmReport = $data;
    }

    $title = $zrmReport['ZrmReport']['request_module'];
    if (isset($zrm_data['zrm_names'][$zrmReport['ZrmReport']['request_module']])) {
        $title = $zrm_data['zrm_names'][$zrmReport['ZrmReport']['request_module']];
    }
?>

<table class="zrmreport">
    <caption>
        <?= $title ?>,
        <?= $date->show($zrmReport['ZrmReport']['created'], array('short' => true)) ?>
    </caption>
    <thead>
        <tr>
            <th>Domein</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($zrm_data['zrm_items'] as $k => $v):
            if (empty($zrmReport['ZrmReport'][$k])) {
                continue;
            }

            $w = $zrmReport['ZrmReport'][$k];
            if ($w > 10) {
                $w = 10;
            }
        ?>
            <tr>
                <td><?= $v ?></td>
                <td>
                    <p style="width: <?= 50 * $w ?>px;">
                        <?= $zrmReport['ZrmReport'][$k] ?>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>

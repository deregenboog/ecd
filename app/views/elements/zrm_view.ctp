<?php
    if (!empty($data)) {
        $zrmReport = $data;
    }

    if (!isset($zrmReportModel)) {
        $zrmReportModel = current(array_keys($zrmReport));
    }

    $title = $zrmReport[$zrmReportModel]['request_module'];
    if (isset($zrmData[$zrmReportModel]['zrm_names'][$zrmReport[$zrmReportModel]['request_module']])) {
        $title = $zrmData[$zrmReportModel]['zrm_names'][$zrmReport[$zrmReportModel]['request_module']];
    }
?>

<table class="zrmreport">
    <caption>
        <?= $title ?>,
        <?= $date->show($zrmReport[$zrmReportModel]['created'], array('short' => true)) ?>
    </caption>
    <thead>
        <tr>
            <th>Domein</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($zrmData['zrm_items'] as $k => $v): ?>
            <?php
                if (empty($zrmReport[$zrmReportModel][$k])):
                    continue;
                endif;
                $w = $zrmReport[$zrmReportModel][$k];
                if ($w > 10):
                    $w = 10;
                endif;
            ?>
            <tr>
                <td><?= $v ?></td>
                <td>
                    <p style="width: <?= 50 * $w ?>px;">
                        <?= $zrmReport[$zrmReportModel][$k] ?>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>

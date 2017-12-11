<?php if (isset($repatrieringen)): ?>
    <div class="form">
        <fieldset>
            <legend>Repatriëringen per land van bestemming</legend>
            <table>
                <?php foreach ($repatrieringen as $repatriering): ?>
                    <tr>
                        <td><?= $landen[$repatriering['Afsluiting']['land_id']] ?></td>
                        <td><?= $repatriering[0]['aantal'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </fieldset>
    </div>
<?php endif; ?>

<div class="actions">
    <?= $this->element('report_filter', [
        'disableLocation' => true,
        'disableGender' => true,
    ]) ?>
</div>

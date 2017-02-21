<?php
    $paginator->options(array(
        'update' => '#contentForIndex',
        'evalScripts' => true,
    ));
?>

<table id="clientList" class="index filtered">
    <tr>
        <th class="klantnrCol">
            <?= $this->Paginator->sort('Klantnr', 'id', $filter_options) ?>
        </th>
        <th class="voornaamCol">
            <?= $this->Paginator->sort('Voornaam/(Roepnaam)', 'name1st_part', $filter_options) ?>
        </th>
        <th class="achternaamCol">
            <?= $this->Paginator->sort('Achternaam', 'name2nd_part', $filter_options) ?>
        </th>
        <th class="gebortedatumCol">
            <?= $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options) ?>
        </th>
        <th class="geschlachtCol">
            <?= $this->Paginator->sort('G', 'geslacht_id', $filter_options) ?>
        </th>
        <th class="1eIntakeCol">
            Gebruikersruimte
        </th>
        <th class="2eIntakesCol">Intake-locatie</th>
        <th class="3eIntakesCol"></th>
        <th class="laatsteIntakeCol">
            <?php if (empty($maatschappelijkwerk)): ?>
                <?= $this->Paginator->sort('Laatste intake', 'LasteIntake.datum_intake', $filter_options) ?>
            <?php else: ?>
                Laatste verslag
            <?php endif; ?>
        </th>
        <?php if (empty($maatschappelijkwerk)): ?>
            <th class="aantalIntakesCol">Aantal intakes</th>
        <?php endif; ?>
        <?php if (isset($bot) && $bot): ?>
            <th class="">BOT</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($klanten as $i => $klant): ?>
        <?php $altrow = ($i % 2 == 0) ? ' altrow' : ''; ?>
        <?php if (!isset($add_to_list)): ?>
            <?php
                if (!isset($rowOnclickUrl)) {
                    $url = $html->url(array('controller' => 'klanten', 'action' => 'view', $klant['Klant']['id']));
                } else {
                    $urlArray = $rowOnclickUrl;
                    $urlArray[] = $klant['Klant']['id'];
                    $url = $this->Html->url($urlArray);
                }
            ?>
            <tr class="klantenlijst-row<?php echo $altrow;?>"
                onMouseOver="this.style.cursor='pointer'"
                onClick="location.href='<?php echo $url; ?>';"
                id='klanten_<?php echo $klant['Klant']['id']?>'>
        <?php else:?>
            <tr class="klantenlijst-row<?php echo $altrow;?>" onMouseOver="this.style.cursor='pointer'" id='klanten_<?php echo $klant['Klant']['id']?>'>
        <?php endif;?>
            <td class="klantnrCol">
                <?= $klant['Klant']['id'] ?>
            </td>
            <td class="voornaamCol">
                <?= $this->Format->name1st($klant['Klant']) ?>
            </td>
            <td class="achternaamCol">
                <?= $klant['Klant']['name2nd_part'] ?>
            </td>
            <td class="gebortedatumCol">
                <?= $klant['Klant']['geboortedatum'] ?>
            </td>
            <td class="geschlachtCol">
                <?= $klant['Geslacht']['afkorting'] ?>
            </td>
            <td class="1eIntakeCol">
                <?php if (isset($klant['LasteIntake']['Locatie1']['naam'])): ?>
                    <?= $klant['LasteIntake']['Locatie1']['naam'] ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td class="2eIntakesCol">
                <?php if (isset($klant['LasteIntake']['Locatie2']['naam'])): ?>
                    <?= $klant['LasteIntake']['Locatie2']['naam'] ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td class="3eIntakesCol">
                <?php if (isset($klant['LasteIntake']['Locatie3']['naam'])): ?>
                    <?= $klant['LasteIntake']['Locatie3']['naam'] ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <?php if (empty($maatschappelijkwerk)): ?>
                <td class="laatsteIntakeCol">
                    <?php if (isset($klant['LasteIntake']['datum_intake'])): ?>
                        <?= $date->show($klant['LasteIntake']['datum_intake'], array('short'=>true)) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            <?php else: ?>
                <td class="laatsteIntakeCol">
                    <?php if (isset($klant['Klant']['laatste_verslag_datum'])): ?>
                        <?= $date->show($klant['Klant']['laatste_verslag_datum'], array('short'=>true)) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            <?php endif; ?>
            <?php if (empty($maatschappelijkwerk)): ?>
                <td class=""><?= count($klant['Intake']) ?></td>
            <?php endif; ?>
            <?php if (isset($bot) && $bot): ?>
                <td class="">
                    <?php if (isset($klant['BackOnTrack']['id'])): ?>
                        <?= $date->show($klant['BackOnTrack']['startdatum'], array('short'=>true)) ?>
                        <?= $date->show($klant['BackOnTrack']['einddatum'], array('short'=>true)) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>

<?php if (isset($add_to_list)): ?>
    <?php foreach ($klanten as $klant): ?>
        <?php $this->Js->get('#klanten_'.$klant['Klant']['id'])->event(
            'click',
            $this->Js->request(
                '/registraties/ajaxAddRegistratie/' . $klant['Klant']['id'] . '/' . $locatie_id,
                [
                    'update' => '#registratielijst',
                    'dataExpression' => true,
                    'evalScripts' => true,
                    'method' => 'post',
                    'before' => '$("#loading").css("display","block")',
                    'complete' => '$("#loading").css("display","none");',
                ]
            )
        ); ?>
    <?php endforeach; ?>
<?php endif; ?>

<p>
    <?= $this->Paginator->counter([
        'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
    ]) ?>
</p>

<?php $num_filter = $filter_options; ?>
<?php if (isset($locatie_id)): ?>
    <?php $num_filter['url'][0] = $filter_options['url'][0].$locatie_id; ?>
<?php endif; ?>

<div class="paging">
    <?= $this->Paginator->prev('<< '.__('previous', true), $filter_options, null, array('class'=>'disabled')) ?>
    | <?= $this->Paginator->numbers($num_filter) ?>
    | <?= $this->Paginator->next(__('next', true).' >>', $filter_options, null, array('class' => 'disabled')) ?>
</div>

<?php if ($this->name === 'Registraties' && $this->action === 'index'): ?>
    <?php $this->Js->get('.klantenlijst-row')->event(
        'click',
        $this->Js->request(
            '/registraties/index/' . $locatie_id,
            [
                'update' => '#contentForIndex',
                'before' => '$("#filters :input[type=\'text\']").val("");',
            ]
        )
    ); ?>
<?php endif; ?>
<?= $js->writeBuffer() ?>

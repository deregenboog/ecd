<?php
    $default = [
        'id' => true,
        'name1st_part' => true,
        'name2nd_part' => true,
        'name' => false,
        'geboortedatum' => true,
        'medewerker_id' => true,
        'bot' => false,
        'iz_projecten' => false,
        'werkgebied' => false,
        'iz_coordinator' => false,
        'medewerker_ids' => false,
        'iz_datum_aanmelding' => false,
        'last_zrm' => false,
        'dummycol' => false,
        'vog_aangevraagd' => true,
        'vog_aanwezig' => true,
        'overeenkomst_aanwezig' => true,
    ];
    if (empty($fields)) {
        $fields = [];
    }
    $fields = array_merge($default, $fields);

    $paginator->options([
        'update' => '#contentForIndex',
        'evalScripts' => true,
    ]);
?>

<table id="clientList" class="index filtered">
    <tr>
        <?php if ($fields['id']): ?>
            <th class="klantnrCol">
                <?php echo $this->Paginator->sort('Nr', 'id', $filter_options); ?>
            </th>
        <?php endif; ?>
        <?php if ($fields['name1st_part']): ?>
            <th class="voornaamCol">
                <?php echo $this->Paginator->sort('Voornaam/ (Roepnaam)', 'name1st_part', $filter_options); ?>
            </th>
        <?php endif; ?>
        <?php if ($fields['name2nd_part']): ?>
            <th class="achternaamCol">
                <?php echo $this->Paginator->sort('Achternaam', 'name2nd_part', $filter_options); ?>
            </th>
        <?php endif; ?>
        <?php if ($fields['geboortedatum']): ?>
            <th class="gebortedatumCol">
                <?php echo $this->Paginator->sort('Geboortedatum', 'geboortedatum', $filter_options); ?>
            </th>
        <?php endif; ?>
        <?php if ($fields['medewerker_id']): ?>
            <th class="medewerkerCol">Medewerker</th>
        <?php endif; ?>
        <?php if ($fields['bot']): ?>
            <th class="botCol">BOT'</th>
        <?php endif; ?>
        <?php if ($fields['iz_projecten']): ?>
            <th class="izProjectsCol">Project</th>
        <?php endif; ?>
        <?php if ($fields['iz_coordinator']): ?>
            <th class="IzCoordinatorCol">Coordinator</th>
        <?php endif; ?>
        <?php if ($fields['medewerker_ids']): ?>
            <th class="IzMedewerkerCol">Medewerker</th>
        <?php endif; ?>
        <?php if ($fields['werkgebied']): ?>
            <th class="werkgebiedCol">Werkgebied</th>
        <?php endif; ?>
        <?php if ($fields['vog_aangevraagd']): ?>
            <th class="overeenkomstCol">VOG<br>aangevraagd</th>
        <?php endif ?>
        <?php if ($fields['vog_aanwezig']): ?>
            <th class="overeenkomstCol">VOG<br>aanwezig</th>
        <?php endif ?>
        <?php if ($fields['overeenkomst_aanwezig']): ?>
            <th class="overeenkomstCol">Vrijwilligers-<br>overeenkomst</th>
        <?php endif ?>
        <?php if ($fields['iz_datum_aanmelding']): ?>
            <th class="IzDatumaanmeldingCol">
                <?= $this->Paginator->sort('Datum aanmelding', 'IzDeelnemer.datum_aanmelding', $filter_options); ?>
            </th>
        <?php endif; ?>
        <?php if ($fields['last_zrm']): ?>
            <th class="lastZrmCol">
                Laatste ZRM
            </th>
        <?php endif; ?>
        <?php if ($fields['dummycol']): ?>
            <th class="show_allCol">
               &nbsp;
            </th>
        <?php endif; ?>
    </tr>
    <?php foreach ($personen as $i => $persoon): ?>
        <?php if (!isset($add_to_list)): ?>
            <?php
                if (!isset($rowOnclickUrl)) {
                    $url = $html->url(['controller' => 'klanten', 'action' => 'view', $persoon[$persoon_model]['id']]);
                } else {
                    $urlArray = $rowOnclickUrl;
                    $urlArray[] = $persoon[$persoon_model]['id'];
                    $url = $this->Html->url($urlArray);
                }
            ?>
            <tr class="klantenlijst-row <?= ($i % 2 == 0) ? null : 'altrow' ?>"
                    onMouseOver="this.style.cursor='pointer'"
                    onClick="location.href='<?php echo $url; ?>';"
                    id='klanten_<?= $persoon[$persoon_model]['id'] ?>'>
        <?php else:?>
            <tr class="klantenlijst-row <?= ($i % 2 == 0) ? null : 'altrow' ?>"
                    onMouseOver="this.style.cursor='pointer'"
                    id='klanten_<?= $klant['Klant']['id'] ?>'>
        <?php endif; ?>
        <?php if ($fields['id']): ?>
            <td class="klantnrCol"><?php echo $persoon[$persoon_model]['id']; ?>&nbsp;</td>
        <?php endif; ?>
        <?php if ($fields['name1st_part']): ?>
            <td class="voornaamCol"><?= $this->Format->name1st($persoon[$persoon_model]); ?>&nbsp;</td>
        <?php endif; ?>
        <?php if ($fields['name2nd_part']): ?>
            <td class="achternaamCol">
                <?php if (isset($persoon[$persoon_model]['name2nd_part'])): ?>
                    <?= $persoon[$persoon_model]['name2nd_part']; ?>
                <?php endif; ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['geboortedatum']): ?>
            <td class="gebortedatumCol">
                <?= $date->show($persoon[$persoon_model]['geboortedatum'], ['short' => true]); ?>&nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['medewerker_id']): ?>
            <td class="medewerkerCol">
                <?php if (isset($viewmedewerkers[$persoon[$persoon_model]['medewerker_id']])): ?>
                    <?= $viewmedewerkers[$persoon[$persoon_model]['medewerker_id']] ?>
                <?php endif; ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['bot']): ?>
            <td class="">
                <?php if (isset($klant['BackOnTrack']['id'])): ?>
                    <?= $date->show($klant['BackOnTrack']['startdatum'], ['short' => true]) ?>
                    <?= $date->show($klant['BackOnTrack']['einddatum'], ['short' => true]) ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        <?php endif; ?>
        <?php if ($fields['iz_projecten']): ?>
            <td class="">
                <?= $persoon[$persoon_model]['projectlist'] ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['iz_coordinator']): ?>
            <td class="">
                <?php if (isset($persoon['IzDeelnemer']['IzIntake']['medewerker_id'])): ?>
                    <?= $viewmedewerkers[$persoon['IzDeelnemer']['IzIntake']['medewerker_id']] ?>
                <?php endif; ?>
            </td>
        <?php endif; ?>
        <?php if ($fields['medewerker_ids']): ?>
            <td class="">
                <?php if (!empty($persoon[$persoon_model]['medewerker_ids'])): ?>
                    <?php
                        $s = '';
                        foreach ($persoon[$persoon_model]['medewerker_ids'] as $medewerker_id) {
                            if (!empty($s)) {
                                $s .= ', <br/>';
                            }
                            $s .= $viewmedewerkers[$medewerker_id];
                        }
                    ?>
                    <?= $s ?>
                <?php endif; ?>
            </td>
        <?php endif;?>
        <?php if ($fields['werkgebied']): ?>
            <td class="">
                <?= $persoon[$persoon_model]['werkgebied'] ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['vog_aangevraagd']): ?>
            <td class="">
                <?= $persoon[$persoon_model]['vog_aangevraagd'] ? 'Ja' : 'Nee' ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['vog_aanwezig']): ?>
            <td class="">
                <?= $persoon[$persoon_model]['vog_aanwezig'] ? 'Ja' : 'Nee' ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['overeenkomst_aanwezig']): ?>
            <td class="">
                <?= $persoon[$persoon_model]['overeenkomst_aanwezig'] ? 'Ja' : 'Nee' ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['iz_datum_aanmelding']): ?>
            <td class="">
                <?= $date->show($persoon['IzDeelnemer']['datum_aanmelding'], ['short' => true]); ?>
                &nbsp;
            </td>
        <?php endif; ?>
        <?php if ($fields['last_zrm']): ?>
            <?php
                $zrm = strtotime($persoon['Klant']['last_zrm']);
                $now = strtotime(date('Y-m-d'));

                $style = '';
                if ($zrm < $now - 5 * 30 * 24 * 60 * 60) {
                    $style = 'background-color: orange; color: white;';
                }

                if ($zrm < $now - 6 * 30 * 24 * 60 * 60) {
                    $style = 'background-color: red; color: white;';
                }

                if (empty($persoon['Klant']['last_zrm'])) {
                    $style = '';
                }
            ?>
            <td class="" style="<?= $style; ?>">
                <?= $date->show($persoon['Klant']['last_zrm'], ['short' => true]); ?>
            </td>
        <?php endif; ?>
        <?php if ($fields['dummycol']): ?>
            <td class="show_allCol" >&nbsp;</td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>

<?php if (isset($add_to_list)): ?>
    <?php foreach ($klanten as $klant): ?>
        <?php $this->Js->get('#klanten_'.$klant['Klant']['id'])->event('click',
                $this->Js->request('/registraties/ajaxAddRegistratie/'.$klant['Klant']['id'].'/'.$locatie_id,
                    ['update' => '#registratielijst',
                        'dataExpression' => true,
                        'evalScripts' => true,
                        'method' => 'post',
                        'before' => '$("#loading").css("display","block")',
                        'complete' => '$("#loading").css("display","none");',
                    ]
                )
            );
        ?>
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
    <?= $this->Paginator->prev('<< '.__('previous', true), $filter_options, null, ['class' => 'disabled']) ?>
    | <?= $this->Paginator->numbers($num_filter); ?>
    | <?= $this->Paginator->next(__('next', true).' >>', $filter_options, null, ['class' => 'disabled']) ?>
</div>

<?php if ($this->name === 'Registraties' && $this->action === 'index'): ?>
    <?php $this->Js->get('.klantenlijst-row')->event('click',
            $this->Js->request('/registraties/index/'.$locatie_id, [
                'update' => '#contentForIndex',
                'before' => '$("#filters :input[type=\'text\']").val("");',
            ]
        )
    ); ?>
<?php endif; ?>
<?= $js->writeBuffer() ?>

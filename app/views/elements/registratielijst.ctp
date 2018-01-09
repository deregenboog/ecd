<br>
<br>
<?php
    $registered_clients = count($active_registraties) + count($gebruikersruimte_registraties);
    $unregistered_counter = 0;
    $current_klant_id = null;
    foreach ($past_registraties as &$registratie) {
        // skipping repeated registrations (assuming that they're sorted properly on buiten!)
        if ($registratie['Registratie']['klant_id'] != $current_klant_id) {
            $current_klant_id = $registratie['Registratie']['klant_id'];
            ++$unregistered_counter;
        }
    }
    $day = 'today';
    $hour = 14;
    $ok_image = $this->Html->image('small-green-ok.png', [
        'class' => 'registratie-ok-icon',
    ]);
?>
<h2>Bezoekersregistratie voor <?php echo $locatie_name; ?></h2>
<p>Aantal geregistreerde klanten op dit moment: <?= $registered_clients; ?></p>
<p>Totaal aantal geregistreerde klanten:
    <?= $registered_clients + $unregistered_counter; ?>
</p>
<p>Datum: <?= $this->Date->show(date('Y-m-d')); ?></p>
<table class="index sortable">
    <thead>
        <tr>
            <th class="backIconCol">&nbsp;</th>
            <th class="voornaamCol"><a href="javascript:void(null);" onclick="ecd_sandbox.alphaSortTable('voornaamCol');">Voornaam/(Roepnaam)</a></th>
            <th class="registraiteAchternaam achternaamCol">
                <a href="javascript:void(null);" onclick="ecd_sandbox.alphaSortTable('achternaamCol');">Achternaam</a>
            </th>
            <th><?php __('In'); ?></th>
            <th><?php __('Uit'); ?></th>
            <th></th>
            <th><a href="javascript:void(null);" onclick="ecd_sandbox.sortTableOnShowerOrder();">Douche</a></th>
            <th><?php __('Kleding'); ?></th>
            <th><?php __('Maaltijd'); ?></th>
            <th>Activering<br/>(geen&nbsp;Hi5)</th>
            <th class ="mwCol"><a href="javascript:void(null);" onclick="ecd_sandbox.sortTableOnMwOrder();">Maatsch.<br/>werk</a></th>
            <th><?php __('TBC-check'); ?></th>
            <th><?php __('Schorsingen'); ?></th>
            <th>Opmerkingen</th>
        </tr>
    </thead>
    <tbody class="active_registraties">
        <?php $i = 0; ?>
        <?php $active = 0; ?>
        <?php foreach ($active_registraties as &$registratie): ?>
            <?php
                $class = null;
                if (1 == $i++ % 2) {
                    $class = 'class="altrow"';
                }
                $url = $html->url(['controller' => 'klanten', 'action' => 'view', $registratie['Registratie']['klant_id']]);
            ?>
            <tr <?php /*echo $class;*/?>>
                <td class="backIconCol">
                    <div class="xButton">
                        <?php
                            echo $this->Js->link(
                                $this->Html->image('undo.png'),
                                [
                                    'controller' => 'registraties',
                                    'action' => 'delete',
                                    $registratie['Registratie']['id'],
                                    $registratie['Registratie']['locatie_id'],
                               ],
                               [
                                    'escape' => false,
                                    'update' => '#registratielijst',
                                    'before' => '$("#loading").css("display","block")',
                                    'complete' => '$("#loading").css("display","none");applyLastSorting();',
                                    'title' => 'Registratie verwijderen',
                                    'confirm' => 'Weet u zeker dat u deze registratie wilt verwijderen?',
                               ]
                            );
                            echo $js->writeBuffer();
                        ?>
                    </div>
                </td>
                <td class="voornaamCol">
                    <?= $this->Html->link($this->Format->name1st($registratie['Klant']),
                        ['controller' => 'klanten', 'action' => 'view', $registratie['Registratie']['klant_id']]); ?>
                </td>
                    <td class="registraiteAchternaam achternaamCol">
                    <?= $this->Html->link($registratie['Klant']['achternaam'],
                        ['controller' => 'klanten', 'action' => 'view', $registratie['Registratie']['klant_id']]);
                    ?>
                    &nbsp;
                </td>
                <td><?= date('H:i', strtotime($registratie['Registratie']['binnen'])); ?>
                </td>
                <td>
                    <?php if ($registratie['Registratie']['buiten'] == null): ?>
                        <?php ++$active; ?>
                        <?php $img = $html->image('time_go.png', ['alt' => 'Uitchecken']); ?>
                        <?= $this->Js->link($img, [
                                'controller' => 'registraties',
                                    'action' => 'registratieCheckOut',
                                $registratie['Registratie']['id'],
                                $registratie['Registratie']['locatie_id'],
                            ],
                            [
                                'escape' => false,
                                'update' => '#registratielijst',
                                'before' => '$("#loading").css("display","block")',
                                'complete' => '$("#loading").css("display","none");applyLastSorting()',
                        ]); ?>
                        <?= $js->writeBuffer(); ?>
                    <?php endif; ?>
                </td>
                <td></td>
                <td id='douche__<?php echo $registratie['Registratie']['id']; ?>' class="doucheCol">
                    <?= $this->element('registratie_checkboxes', [
                        'fieldname' => 'douche',
                        'registratie' => $registratie,
                        'locatie_id' => $locatie_id,
                        ]
                    ); ?>
                </td>
                <td id='kleding__<?php echo $registratie['Registratie']['id']; ?>'>
                    <?= $this->element('registratie_checkboxes', [
                        'fieldname' => 'kleding',
                        'registratie' => $registratie, ]
                    ); ?>
                </td>
                <td id='maaltijd__<?= $registratie['Registratie']['id']; ?>'>
                    <?= $this->element('registratie_checkboxes', [
                        'fieldname' => 'maaltijd',
                        'registratie' => $registratie, ]
                    ); ?>
                </td>
                <td id='activering__<?= $registratie['Registratie']['id']; ?>'>
                    <?= $this->element('registratie_checkboxes', [
                        'fieldname' => 'activering',
                        'registratie' => $registratie, ]
                    ); ?>
                </td>
                <td id='mw__<?php echo $registratie['Registratie']['id']; ?>' class="mwCol">
                    <?= $this->element('registratie_checkboxes', [
                            'fieldname' => 'mw',
                            'registratie' => $registratie,
                            'locatie_id' => $locatie_id,
                        ]
                    ); ?>
                </td>
                <td>
                    <?php if (1 == $locatie['tbc_check']): ?>
                        <?= $registratie['Klant']['laatste_TBC_controle_message']; ?>&nbsp;
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                        if (
                            $registratie['Klant']['active_schorsingen'] != 'geen' &&
                            $registratie['Klant']['active_schorsingen'] != 'schorsing verlopen'
                        ) {
                            $strong = '<span class="warning">';
                            $strong_end = '</span>';
                        } else {
                            $strong = $strong_end = '';
                        }
                        echo $strong;
                        echo $html->link($registratie['Klant']['active_schorsingen'],
                            ['controller' => 'schorsingen', 'action' => 'index',
                                $registratie['Registratie']['klant_id'], $locatie_id, ]
                        );
                        echo $strong_end;
                    ?>
                    &nbsp;
                </td>
                <td>
                    <?= $html->link($registratie['Klant']['opmerkingen'],
                        ['controller' => 'opmerkingen', 'action' => 'index',
                            $registratie['Registratie']['klant_id'], $locatie_id, ]
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>

    <?php if (isset($gebruikersruimte_registraties) && is_array($gebruikersruimte_registraties) && count($gebruikersruimte_registraties) > 0): ?>
        <thead>
            <tr>
                <th colspan="14">Gebruikersruimte:</th>
            </tr>
            <tr>
                <th class="backIconCol">&nbsp;</th>
                <th class="voornaamCol">
                    <a href="javascript:void(null);" onclick="ecd_sandbox.alphaSortTable('voornaamCol');">Voornaam/(Roepnaam)</a>
                </th>
                <th class="registraiteAchternaam achternaamCol">
                    <a href="javascript:void(null);" onclick="ecd_sandbox.alphaSortTable('achternaamCol');">Achternaam</a>
                </th>
                <th><?php __('In'); ?></th>
                <th><?php __('Uit'); ?></th>
                <th class ="gbrvCol"><a href="javascript:void(null);" onclick="ecd_sandbox.sortTableOnGbrvOrder();">Gbr.<br/>Volgorde</a></th>
                <th><a href="javascript:void(null);" onclick="ecd_sandbox.sortTableOnShowerOrder();">Douche</a></th>
                <th><?php __('Kleding'); ?></th>
                <th><?php __('Maaltijd'); ?></th>
                <th>Activering<br/>(geen&nbsp;Hi5)</th>
                <th class ="mwCol">
                    <a href="javascript:void(null);" onclick="ecd_sandbox.sortTableOnMwOrder();">Maatsch.<br>werk</a>
                </th>
                <th><?php __('TBC-check'); ?></th>
                <th><?php __('Schorsingen'); ?></th>
                <th>Opmerkingen</th>
            </tr>
        </thead>
        <tbody class="gebruikers">
            <?php $i = 0; ?>
            <?php $active = 0; ?>
            <?php foreach ($gebruikersruimte_registraties as $registratie): ?>
                <?php
                    $class = null;
                    if (1 == $i++ % 2) {
                        $class = ' class="altrow"';
                    }
                    $url = $html->url(['controller' => 'klanten', 'action' => 'view', $registratie['Registratie']['klant_id']]);
                ?>
                <tr>
                    <td class="backIconCol">
                        <div class="xButton">
                            <?= $this->Js->link(
                                $this->Html->image('undo.png'),
                                [
                                    'controller' => 'registraties',
                                    'action' => 'delete',
                                    $registratie['Registratie']['id'],
                                    $registratie['Registratie']['locatie_id'],
                                ],
                                [
                                    'escape' => false,
                                    'update' => '#registratielijst',
                                    'before' => '$("#loading").css("display","block")',
                                    'complete' => '$("#loading").css("display","none");applyLastSorting()',
                                    'title' => 'Registratie verwijderen',
                                    'confirm' => 'Weet u zeker dat u deze registratie wilt verwijderen?',
                                ]
                            ); ?>
                        </div>
                    </td>
                    <td class="voornaamCol">
                        <?= $this->Html->link(
                            $this->Format->name1st($registratie['Klant']),
                            [
                                'controller' => 'klanten',
                                'action' => 'view',
                                $registratie['Registratie']['klant_id'],
                            ]
                        ); ?>
                    </td>
                    <td class="achternaamCol">
                        <?= $this->Html->link(
                            $registratie['Klant']['achternaam'],
                            [
                                'controller' => 'klanten',
                                'action' => 'view',
                                $registratie['Registratie']['klant_id'],
                            ]
                        ); ?>
                    </td>
                    <td>
                        <?= date('H:i', strtotime($registratie['Registratie']['binnen'])); ?>
                    </td>
                    <td>
                        <?php
                            if ($registratie['Registratie']['buiten'] == null) {
                                ++$active;
                                $img = $html->image('time_go.png', ['alt' => 'Uitchecken']);
                                echo $this->Js->link($img, [
                                    'controller' => 'registraties',
                                    'action' => 'registratieCheckOut',
                                    $registratie['Registratie']['id'],
                                    $registratie['Registratie']['locatie_id'], ],
                                    [
                                        'escape' => false,
                                        'update' => '#registratielijst',
                                        'before' => '$("#loading").css("display","block")',
                                        'complete' => '$("#loading").css("display","none");applyLastSorting();',
                                ]);
                                echo $js->writeBuffer();
                            }
                        ?>
                    </td>
                    <td id='gbrv__<?php echo $registratie['Registratie']['id']; ?>' class="gbrvCol">
                        <?= $this->element('registratie_checkboxes', [
                            'fieldname' => 'gbrv',
                            'registratie' => $registratie,
                            'locatie_id' => $locatie_id,
                        ]); ?>
                    </td>
                    <td id='douche__<?php echo $registratie['Registratie']['id']; ?>' class="doucheCol">
                        <?= $this->element('registratie_checkboxes', [
                            'fieldname' => 'douche',
                            'registratie' => $registratie,
                            'locatie_id' => $locatie_id,
                        ]); ?>
                    </td>
                    <td id='kleding__<?php echo $registratie['Registratie']['id']; ?>'>
                        <?= $this->element('registratie_checkboxes', [
                            'fieldname' => 'kleding',
                            'registratie' => $registratie,
                        ]); ?>
                    </td>
                    <td id='maaltijd__<?php echo $registratie['Registratie']['id']; ?>'>
                        <?= $this->element('registratie_checkboxes', [
                            'fieldname' => 'maaltijd',
                            'registratie' => $registratie, ]
                        ); ?>
                    </td>
                    <td id='activering__<?php echo $registratie['Registratie']['id']; ?>'>
                        <?= $this->element('registratie_checkboxes', [
                            'fieldname' => 'activering',
                            'registratie' => $registratie, ]
                        ); ?>
                    </td>
                    <td id='mw__<?php echo $registratie['Registratie']['id']; ?>' class="mwCol">
                        <?= $this->element('registratie_checkboxes', [
                            'fieldname' => 'mw',
                            'registratie' => $registratie,
                            'locatie_id' => $locatie_id,
                        ]); ?>
                    </td>
                    <td>
                        <?php if (1 == $locatie['tbc_check']): ?>
                            <?= $registratie['Klant']['laatste_TBC_controle_message']; ?>&nbsp;
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $html->link($registratie['Klant']['active_schorsingen'],
                            ['controller' => 'schorsingen', 'action' => 'index',
                            $registratie['Registratie']['klant_id'], $locatie_id, ]
                        ); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?= $html->link($registratie['Klant']['opmerkingen'], [
                            'controller' => 'opmerkingen',
                            'action' => 'index',
                            $registratie['Registratie']['klant_id'],
                            $locatie_id,
                        ]); ?>
                    </td>
                </tr>
            </tbody>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($past_registraties) && is_array($past_registraties) && count($past_registraties) > 0): ?>
        <thead>
            <tr>
                <th colspan="3">Uitgecheckt:</th>
                <th>In</th>
                <th>Uit</th>
                <th>Duur</th>
                <th>Douche</th>
                <th>Kleding</th>
                <th>Maaltijd</th>
                <th>Activering<br/>(geen&nbsp;Hi5)</th>
                <th class ="mwCol">Maatsch.<br/>werk</th>
                <th>TBC-check</th>
                <th>Schorsingen</th>
                <th>Opmerkingen</th>
            </tr>
        </thead>
        <tbody class="deregistered">
            <?php $i = 0; ?>
            <?php $active = 0; ?>
            <?php $current_klant_id = null; ?>
            <?php foreach ($past_registraties as &$registratie): ?>
                <?php
                    if ($registratie['Registratie']['klant_id'] == $current_klant_id) {
                        continue;
                    } else {
                        $current_klant_id = $registratie['Registratie']['klant_id'];
                        ++$unregistered_counter;
                    }
                    $class = null;
                    if (1 == $i++ % 2) {
                        $class = ' class="altrow"';
                    }
                ?>
                <tr id="registratielijst_klant_<?=$registratie['Registratie']['klant_id']; ?>">
                    <td class="backIconCol">&nbsp;</td>
                    <td class="voornaamCol clickable">
                        <?= $this->Format->name1st($registratie['Klant']); ?>&nbsp;
                    </td>
                    <td class="achternaamCol clickable">
                        <?php echo $registratie['Klant']['achternaam']; ?>&nbsp;
                    </td>
                    <td class="clickable">
                        <?= date('H:i', strtotime($registratie['Registratie']['binnen'])); ?>
                    </td>
                    <td class="clickable">
                        <?= date('H:i', strtotime($registratie['Registratie']['buiten'])); ?>
                    </td>
                    <td class="clickable">
                        <?php
                            $binnen = strtotime($registratie['Registratie']['binnen']);
                            $buiten = strtotime($registratie['Registratie']['buiten']);
                            $duur = $buiten - $binnen;
                            $hours = round($duur / HOUR);
                            $minutes = ($duur - $hours * HOUR) / MINUTE;
                            echo $hours.':'.sprintf('%02d', round($minutes));

                            if (date('Ymd', $binnen) !== date('Ymd', strtotime('now'))) {
                                echo '<br/><div style="position: relative">&nbsp;';
                                echo '<small style="position:absolute; left: -50px">('.
                                    date('Y-m-d', $buiten).')</small>';
                                echo '</div>';
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            $klant_id = $registratie['Registratie']['klant_id'];
                            $ajax_request = $this->Js->request('/registraties/ajaxAddRegistratie/'.
                                $klant_id.'/'.$locatie_id,
                                ['update' => '#registratielijst',
                                    'dataExpression' => true,
                                    'evalScripts' => true,
                                    'method' => 'post',
                                    'before' => '$("#loading").css("display","block")',
                                    'complete' => '$("#loading").css("display","none");',
                                ]
                            );

                            $confirm_msg = __('This client has been checked out less than an hour ago'.
                                '. Are you sure you want to register him/her again?',
                                true
                            );

                            $jquery_clickable_cells = '#registratielijst_klant_'.$klant_id.' td.clickable';

                            $request = $this->Js->request(
                                '/registraties/ajaxAddRegistratie/'.$klant_id.'/'.$locatie_id,
                                [
                                    'update' => '#registratielijst',
                                    'dataExpression' => true,
                                    'evalScripts' => true,
                                    'method' => 'post',
                                    'before' => '$("#loading").css("display","block")',
                                    'complete' => '$("#loading").css("display","none")',
                                ]
                            );

                            $onclick_script = '
                                var out = new Date('.$buiten.'*1000);
                                var now = new Date;
                                var ago = (now - out)/(60*1000);
                                if(ago < 60){
                                    confirm_action = confirm("'.$confirm_msg.'");
                                }else{
                                    confirm_action = true
                                }
                                if(confirm_action){'.$request.'}
                            ';

                            $this->Js->get($jquery_clickable_cells)->event('click', $onclick_script);
                        ?>
                        <?= $this->element('registratie_simple_checkbox', [
                                'fieldname' => 'douche',
                                'registratie' => &$registratie,
                            ]);
                        ?>
                    </td>
                    <td>
                        <?= $this->element('registratie_simple_checkbox', [
                            'fieldname' => 'kleding',
                            'registratie' => &$registratie,
                        ]); ?>
                    </td>
                    <td>
                        <?= $this->element('registratie_simple_checkbox', [
                            'fieldname' => 'maaltijd',
                            'registratie' => &$registratie,
                        ]); ?>
                    </td>
                    <td>
                        <?= $this->element('registratie_simple_checkbox', [
                            'fieldname' => 'activering',
                            'registratie' => &$registratie,
                        ]); ?>
                    </td>
                    <td>
                        <?= $this->element('registratie_simple_checkbox', [
                            'fieldname' => 'mw',
                            'registratie' => &$registratie,
                        ]); ?>
                    </td>
                    <td class="clickable">
                        <?php if (1 == $locatie['tbc_check']): ?>
                            <?= $registratie['Klant']['laatste_TBC_controle_message']; ?>&nbsp;
                        <?php endif; ?>
                    </td>
                    <td class="clickable">
                        <?= $registratie['Klant']['active_schorsingen']; ?>&nbsp;
                    </td>
                    <td class="clickable">
                        <?= $registratie['Klant']['opmerkingen']; ?>&nbsp;
                    </td>
                </tr>
            </tbody>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<?php if ((count($active_registraties) + count($gebruikersruimte_registraties)) > 0): ?>
    <div class="action">
        <?php
            $checkout = 'Alle bezoekers van '.$locatie_name.' uitchecken';
            $url = ['controller' => 'registraties', 'action' => 'checkoutAll', $locatie_id];
            $opts = ['escape' => false, 'title' => 'iedereen uitschrijven'];
        ?>
        <?= $this->Js->link($checkout, [
                'controller' => 'registraties',
                'action' => 'registratieCheckOutAll',
                $locatie_id,
            ],
            [
                'class' => 'checkout',
                'update' => '#registratielijst',
                'confirm' => 'Wil je echt iedereen uitchecken van '.$locatie_name.'?',
            ]
        ); ?>
    </div>
<?php endif; ?>
<?= $js->writeBuffer(); ?>

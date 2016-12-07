<?php
$logged_in_user_id = $this->Session->read('Auth.Medewerker.id');
if (!isset($plainText) || !$plainText) {
    echo '<div class="editWrench">';
    $printer_img = $this->Html->image('printer.png');
    echo '<a href="#" onclick="window.print()">'.$printer_img.'</a>';

    if ($data['Intake']['datum_intake'] == date('Y-m-d') &&
            $logged_in_user_id == $data['Intake']['medewerker_id']
        ) {
        $wrench = $html->image('wrench.png');
        $url = array('controller' => 'intakes', 'action' => 'edit', $data['Intake']['id']);
        $opts = array('escape' => false, 'title' => __('edit', true));
        echo $html->link($wrench, $url, $opts);
    }
    echo '</div>';
}
?>

<?php if (!isset($plainText) || !$plainText) {
    ?><fieldset>
    <legend><?php __('algemeen')?></legend><?php
} else {
    ?>
    <h3><?php __('algemeen')?></h3>
    <?php
}?>

    <table class='fixedwidth'>
        <tr>
            <td><?php __('medewerker')?></td>
            <td><?php echo $format->printData($data['Medewerker']['name']); ?></td>
        </tr>
        <tr>
            <td><?php __('datum_intake') ; ?></td>
            <td><?php echo $format->printData($date->show($data['Intake']['datum_intake'])); ?></td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
    ?></fieldset><?php
} ?>

<?php if (!isset($plainText) || !$plainText) {
    ?><fieldset>
    <legend><?php __('adresgegevens')?></legend>
    <?php
} else {
    ?>
    <h3><?php __('adresgegevens')?></h3>
    <?php
}?>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('postadres')?></td>
            <td><?php echo $format->printData($data['Intake']['postadres']); ?></td>
        </tr>
        <tr>
            <td><?php __('postcode')?></td>
            <td><?php echo $format->printData($data['Intake']['postcode']); ?></td>
        </tr>
        <tr>
            <td><?php __('woonplaats')?></td>
            <td><?php echo $format->printData($data['Intake']['woonplaats']); ?></td>
        </tr>
        <tr>
            <td><?php __('verblijf_in_NL_sinds')?></td>
            <td><?php echo $format->printData($date->show($data['Intake']['verblijf_in_NL_sinds'])); ?></td>
        </tr>
        <tr>
            <td><?php __('verblijf_in_amsterdam_sinds')?></td>
            <td><?php echo $format->printData($date->show($data['Intake']['verblijf_in_amsterdam_sinds'])); ?></td>
        </tr>
        <tr>
            <td><?php __('verblijfstatus')?></td>
            <td><?php echo $format->printData($data['Verblijfstatus']['naam']); ?></td>
        </tr>
        <tr>
            <td><?php __('Telefoonnummer')?></td>
            <td><?php echo $format->printData($data['Intake']['telefoonnummer']); ?></td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
    ?></fieldset><?php
} ?>

<?php if (!isset($plainText) || !$plainText) {
    ?><fieldset>
    <legend><?php __('locatiekeuze')?></legend>
    <?php
} else {
    ?>
    <h3><?php __('locatiekeuze')?></h3>
    <?php
}?>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('Intake locatie')?></td>
            <td><?php echo $format->printData($data['Locatie2']['naam']); ?></td>
        </tr>
        <tr>
            <td><?php __('Toegang inloophuis')?></td>
        <td><?php
            $tmp =	!empty($data['Intake']['toegang_inloophuis']) ? 'ja' : 'nee';
            echo $format->printData($tmp); ?></td>
        </tr>
        <tr>
            <td><?php __('Toegang gebruikersruimte')?></td>
            <td><?php echo $format->printData($data['Locatie1']['naam']); ?></td>
        </tr>
        <!--
        <tr>
            <td><?php __('locatie3')?></td>
            <td><?php echo $format->printData($data['Locatie3']['naam']); ?></td>
        </tr>
         -->
        <?php if ($klant['Klant']['geslacht_id'] == 2) {
                echo $format->printTableLine(
                __('Heeft toegang tot de Vrouwen Nacht Opvang', true),
                $data['Intake']['toegang_vrouwen_nacht_opvang'],
                FormatHelper::JANEE);
            } ?>
        <!--
        <tr>
            <td><?php __('mag_gebruiken')?></td>
            <td><?php echo $data['Intake']['mag_gebruiken'] == '1' ? 'Ja' : 'Nee';?></td>
        </tr>
        -->
    </table>
<?php if (!isset($plainText) || !$plainText) {
                ?></fieldset><?php
            } ?>

<?php if (!isset($plainText) || !$plainText) {
                ?><fieldset>
    <legend><?php __('legitimatie')?></legend>
    <?php
            } else {
                ?>
    <h3><?php __('legitimatie')?></h3>
    <?php
            }?>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('legitimatie')?></td>
            <td><?php echo $format->printData($data['Legitimatie']['naam']); ?></td>
        </tr>
        <tr>
            <td><?php __('legitimatie_nummer')?></td>
            <td><?php echo $format->printData($data['Intake']['legitimatie_nummer']); ?></td>
        </tr>
        <tr>
            <td><?php __('legitimatie_geldig_tot')?></td>
            <td><?php echo $format->printData($date->show($data['Intake']['legitimatie_geldig_tot'])); ?></td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
                ?></fieldset><?php
            } ?>

<?php if (!isset($plainText) || !$plainText) {
                ?><fieldset>
    <legend><?php __('verslaving')?></legend><?php
            } else {
                ?>
    <h3><?php __('verslaving')?></h3>
    <?php
            } ?>

<?php if ($data['PrimaireProblematiek']['id']): ?>
    <h3>Primaire problematiek</h3>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('Problematiek')?></td>
            <td>
                <?php echo $format->printData($data['PrimaireProblematiek']['naam']); ?>
            </td>
        </tr>
        <tr>
            <td><?php __('verslavingsfrequentie')?></td>
            <td><?php echo $format->printData($data['PrimaireProblematieksfrequentie']['naam']);?></td>
        </tr>
        <tr>
            <td><?php __('verslavingsperiode')?></td>
            <td><?php echo $format->printData($data['PrimaireProblematieksperiode']['naam']);?></td>
        </tr>
        <tr>
            <td><?php __('verslavingsgebruikwijze')?></td>
            <td>
                <?php if (!empty($data['Primaireproblematieksgebruikswijze'])) {
                ?>
                <ul>
                    <?php foreach ($data['Primaireproblematieksgebruikswijze'] as $primaireproblematieksgebruikswijze): ?>
                        <li>
                            <?php echo $primaireproblematieksgebruikswijze['naam']?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php
            } else {
                echo '-';
            }?>
            </td>
        </tr>
    </table>
    <h3>Secundaire problematiek</h3>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('verslavingen')?></td>
            <td>
                <?php if (!empty($data['Verslaving'])) {
                ?>
                <ul>
                    <?php foreach ($data['Verslaving'] as $verslaving): ?>
                        <li>
                            <?php echo $verslaving['naam']?>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($data['Intake']['verslaving_overig'] != ''): ?>
                        <li>
                            <?php echo $data['Intake']['verslaving_overig']; ?>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php
            } else {
                echo '-';
            }?>
            </td>
        </tr>
        <tr>
            <td><?php __('verslavingsfrequentie')?></td>
            <td><?php echo $format->printData($data['Verslavingsfrequentie']['naam']);?></td>
        </tr>
        <tr>
            <td><?php __('verslavingsperiode')?></td>
            <td><?php echo $format->printData($data['Verslavingsperiode']['naam']);?></td>
        </tr>
        <tr>
            <td><?php __('verslavingsgebruikwijze')?></td>
            <td>
                <?php if (!empty($data['Verslavingsgebruikswijze'])) {
                ?>
                <ul>
                    <?php foreach ($data['Verslavingsgebruikswijze'] as $verslavingsgebruikwijze): ?>
                        <li>
                            <?php echo $verslavingsgebruikwijze['naam']?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php
            } else {
                echo '-';
            }?>
            </td>
        </tr>
    </table>
    <h3>Algemene problematiek</h3>
    <table class='fixedwidth'>
        <tr>
            <td>
                Wat is de datum van het eerste gebruik?
            </td>
            <td>
                <?php
                    echo $format->printData($date->show(
                        $data['Intake']['eerste_gebruik']));
                ?>
            </td>
        </tr>
    </table>
<?php else: ?>
    <h3>Problematiek</h3>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('verslavingen')?></td>
            <td>
                <?php if (!empty($data['Verslaving'])) {
                ?>
                <ul>
                    <?php foreach ($data['Verslaving'] as $verslaving): ?>
                        <li>
                            <?php echo $verslaving['naam']?>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($data['Intake']['verslaving_overig'] != ''): ?>
                        <li>
                            <?php echo $data['Intake']['verslaving_overig']; ?>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php
            } else {
                echo '-';
            }?>
            </td>
        </tr>
    </table>
<?php endif; ?>
<?php if (!isset($plainText) || !$plainText) {
                    ?></fieldset><?php
                } ?>

<?php if (!isset($plainText) || !$plainText) {
                    ?><fieldset>
    <legend><?php __('inkomen_en_woonsituatie')?></legend><?php
                } else {
                    ?>
    <h3><?php __('inkomen_en_woonsituatie')?></h3>
    <?php
                }?>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('inkomen')?></td>
            <td>
                <?php if (!empty($data['Inkomen'])) {
                    ?>
                <ul>
                    <?php foreach ($data['Inkomen'] as $inkomen): ?>
                        <li>
                            <?php echo $inkomen['naam']?>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($data['Intake']['inkomen_overig'] != ''): ?>
                        <li>
                            <?php echo $data['Intake']['inkomen_overig']; ?>
                        </li>
                    <?php endif; ?>
                </ul>
                <?php
                } else {
                    echo '-';
                }?>
            </td>
        </tr>
        <tr>
            <td><?php __('woonsituatie')?></td>
            <td><?php echo $format->printData($data['Woonsituatie']['naam'])?></td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
                    ?></fieldset><?php
                } ?>

<?php if (!isset($plainText) || !$plainText) {
                    ?><fieldset>
    <legend><?php __('overige_hulpverlening')?></legend><?php
                } else {
                    ?>
    <h3><?php __('overige_hulpverlening')?></h3>
    <?php
                }?>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('instantie')?></td>
            <td>
                <?php if (!empty($data['Instantie'])) {
                    ?>
                <ul>
                    <?php foreach ($data['Instantie'] as $instantie): ?>
                        <li>
                            <?php echo $instantie['naam']?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php
                } else {
                    echo '-';
                }?>
            </td>
        </tr>
        <tr>
            <td><?php __('opmerking_andere_instanties')?></td>
            <td><?php echo $format->printData($data['Intake']['opmerking_andere_instanties']); ?></td>
        </tr>
        <tr>
            <td><?php __('medische_achtergrond')?></td>
            <td><?php echo $format->printData($data['Intake']['medische_achtergrond']); ?></td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
                    ?></fieldset><?php
                } ?>

<?php if (!isset($plainText) || !$plainText) {
                    ?><fieldset>
    <legend><?php __('verwachtingen_en_plannen')?></legend><?php
                } else {
                    ?>
    <h3><?php __('verwachtingen_en_plannen')?></h3>
    <?php
                }?>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('verwachting_dienstaanbod')?></td>
            <td><?php echo $format->printData($data['Intake']['verwachting_dienstaanbod']); ?></td>
        </tr>
        <tr>
            <td><?php __('toekomstplannen')?></td>
            <td><?php echo $format->printData($data['Intake']['toekomstplannen']); ?></td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
                    ?></fieldset><?php
                } ?>

<?php if (!isset($plainText) || !$plainText) {
                    ?><fieldset>
    <legend><?php __('Indruk')?></legend><?php
                } else {
                    ?>
    <h3><?php __('Indruk')?></h3>
    <?php
                }?>
    <table class='fixedwidth'>
        <tr>
            <td><?php __('indruk')?></td>
            <td><?php echo $format->printData($data['Intake']['indruk']); ?></td>
        </tr>
        <tr>
            <td><?php __('doelgroep') ?></td>
            <td>
                <?php if ($data['Intake']['doelgroep']): ?>
                    <p>Ja</p>
                <?php elseif ($data['Intake']['infobaliedoelgroep_id']): ?>

                    <p><?= $data['Infobaliedoelgroep']['naam'] ?>
                    </p>

                <?php else: ?>
                    <p>Nee</p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
                    ?></fieldset><?php
                } ?>

<?php if (!isset($plainText) || !$plainText) {
                    ?><fieldset>
    <legend><?php __('Ondersteuning')?></legend><?php
                } else {
                    ?>
    <h3><?php __('Ondersteuning')?></h3>
    <?php
                }?>
    <table class="fixedwidth">
        <tr>
            <td>Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen?</td>
            <td><?php echo $data['Intake']['informele_zorg'] == 1? 'Ja' : 'Nee'; ?></td>
        </tr>
        <tr>
            <td>Zou je het leuk vinden om overdag iets te doen te hebben?</td>
            <td><?php echo $data['Intake']['dagbesteding'] == 1? 'Ja' : 'Nee'; ?></td>
        </tr>
        <tr>
            <td>Zou je een plek in de buurt willen hebben waar je iedere
                    dag koffie kan drinken en mensen kan ontmoeten?</td>
            <td><?php echo $data['Intake']['inloophuis'] == 1? 'Ja' : 'Nee'; ?></td>
        </tr>
        <tr>
            <td>Heeft u hulp nodig met regelzaken?</td>
            <td><?php echo $data['Intake']['hulpverlening'] == 1? 'Ja' : 'Nee'; ?></td>
        </tr>
    </table>
<?php if (!isset($plainText) || !$plainText) {
                    ?></fieldset><?php
                } ?>
<?php
if (!isset($plainText) || !$plainText) {
                    if ($data['Intake']['datum_intake'] == date('Y-m-d') &&
            $logged_in_user_id == $data['Intake']['medewerker_id']
        ) {
                        echo '<div class="editWrench">';
                        $wrench = $html->image('wrench.png');
                        $url = array(
                'controller' => 'intakes',
                'action' => 'edit',
                $data['Intake']['id'],
            );
                        $opts = array('escape' => false, 'title' => __('edit', true));
                        echo $html->link($wrench, $url, $opts);
                        echo '</div>';
                    }
                }

    if (! empty($zrmReport)) {
        echo $this->element('zrm_view', array('data' => $zrmReport));
    }
?>

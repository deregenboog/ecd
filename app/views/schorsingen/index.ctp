<div class="borderedContent form">
    <?php
        $active_count = count($active_schorsingen);
        $expired_count = count($expired_schorsingen);
    ?>
    <h2 class="commentedHeader"><?php __('Schorsingen');?></h2>
    <p class="headerComment">
        <?php
            if (!empty($klant['Klant']['tussenvoegsel'])) {
                $klant['Klant']['tussenvoegsel'] = ' '.
                $klant['Klant']['tussenvoegsel'];
            }
            echo ' van '.$klant['Klant']['voornaam'].' '.
                $klant['Klant']['roepnaam'].$klant['Klant']['tussenvoegsel'].
                ' '.$klant['Klant']['achternaam'];
        ?>
    </p>
    <br/>
        <?php
            $add_url = array('action' => 'add', $klant_id);
            if ($locatie_id != null) {
                $add_url[] = $locatie_id;
            }
        ?>
        <?= $html->link('Schorsing toevoegen', $add_url) ?>
    <br/>
    <br/>
    <h3 class="commentedHeader">Huidige schorsingen</h3>
    <p class="headerComment">
        <?php
            echo '('.$active_count.' stuk'.($active_count == 1 ? '' : 's').')';
        ?>
    </p>
    <br/>
    <br/>
    <?php if ($active_count > 0): ?>
        <p>
        <?php foreach ($active_schorsingen as $schorsing): ?>
            <?php
               $schId = $schorsing['Schorsing']['id'];
                echo '<div id="ajax'.$schId.'" class="editWrenchFloat">';
                $wrench = $html->image('wrench.png');
                $url = array('action' => 'edit', $schId);
                $opts = array('escape' => false, 'title' => __('edit', true));
                echo $html->link($wrench, $url, $opts);
                echo '</div>';
                echo 'Op locatie '.$schorsing['Locatie']['naam'];
                echo ' geschorst van '.$date->show($schorsing['Schorsing']['datum_van']);
                echo ' tot en met '.$date->show($schorsing['Schorsing']['datum_tot']);
                if (!empty($schorsing['Reden'])):
                    echo ' voor:<br/>';
                    echo "\n<ul>";
                    foreach ($schorsing['Reden'] as $reden) {
                        echo "\n<li>\n";
                        echo $reden['naam'];
                        if ($reden['SchorsingenReden']['reden_id'] == 100) {
                            echo ' - '.$schorsing['Schorsing']['overig_reden'];
                        }
                        echo "\n</li>\n";
                    }
                    echo "</ul>\n";
                endif;

                echo '<br/>'."\n";

                if (!empty($schorsing['Schorsing']['remark'])):
                    echo 'Met als opmerking: '.$schorsing['Schorsing']['remark'].
                        '<br/>'."\n";
                endif;

                echo $this->Html->link(
                    __('print', true),
                    array('action' => 'get_pdf', $schorsing['Schorsing']['id']),
                    array('target' => '_blank')
                );

                echo ' | '.$this->Html->link(
                    __('print english', true),
                    array('action' => 'get_pdf', $schorsing['Schorsing']['id'], 1),
                    array('target' => '_blank')
                );

                echo '<br/>'."\n";
            endforeach;
        ?>
        </p>
    <?php else: ?>
        <p>Deze persoon is op dit moment niet geschorst.</p>
    <?php endif; ?>
    <p>&nbsp;</p>
    <h3 class="commentedHeader">Verlopen schorsingen</h3>
    <p class="headerComment">
        <?php
            echo '('.$expired_count.' stuk'.($active_count == 1 ? '' : 's').')';
        ?>
    </p>
    <br/>
    <br/>
    <?php if ($expired_count > 0): ?>
        <p>
        <?php foreach ($expired_schorsingen as $schorsing): ?>
            <div class="editWrenchFloat">
                <?php
                    $schId = $schorsing['Schorsing']['id'];
                    echo $this->Form->create('sch'.$schId);
                    echo $this->Form->input('Gezien', array(
                        'type' => 'checkbox',
                        'checked' => $schorsing['Schorsing']['gezien'],
                        'name' => 'data[Schorsing][gezien]',
                        'id' => 'sch'.$schId.'Gezien',
                    ));
                    echo $this->Form->end();
                    echo $this->Js->get('#sch'.$schId.'Gezien')->event('change',
                        $this->Js->request(
                            array('action' => 'gezien', $schId),
                            array(
                                'method' => 'post',
                                'async' => false,
                            )
                        )
                    );
                ?>
            </div>
            <?php
                echo 'Op locatie '.$schorsing['Locatie']['naam'];
                echo ' geschorst van '.$date->show($schorsing['Schorsing']['datum_van']);
                echo ' tot en met '.$date->show($schorsing['Schorsing']['datum_tot']);
                if (!empty($schorsing['Reden'])):
                    echo ' voor:<br/>';
                    echo '<ul>';
                    foreach ($schorsing['Reden'] as $reden) {
                        echo '<li>';
                        echo $reden['naam'];
                        echo '</li>';
                    }
                    echo '</ul>';
                endif;
                echo '<br/>'."\n";
                if (!empty($schorsing['Schorsing']['remark'])):
                    echo 'Met als opmerking: '.$schorsing['Schorsing']['remark'].
                        '<br/>'."\n";
                endif;
                echo '<br/>'."\n";
            endforeach;
            ?>
        </p>
    <?php else: ?>
        <p>Deze persoon is in het verleden niet geschorst.</p>
    <?php endif; ?>
    <?= $this->Js->writeBuffer() ?>
    <p>&nbsp;</p>
    <p>
        <?php
            if (isset($locatie_id)) {
                $target = array('controller' => 'registraties', 'action' => 'index', $locatie_id);
            } else {
                $target = array('controller' => 'klanten', 'action' => 'view', $klant_id);
            }
            echo $html->link('Terug', $target, array('class' => 'back'));
        ?>
    </p>
</div>
<div class="actions">
    <?= $this->element('klantbasic', array('data' => $klant)) ?>
</div>

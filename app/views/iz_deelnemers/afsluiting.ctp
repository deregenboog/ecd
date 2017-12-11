<?php
$url = $this->Html->url(array('controller' => 'iz_deelnemers', 'action' => 'afsluiting', $id), true);
$datumafsluiting = date('Y-m-d');

if (! empty($this->data['IzDeelnemer']['datumafsluiting'])) {

    if (is_array($this->data['IzDeelnemer']['datumafsluiting'])) {

        if (!empty($this->data['IzDeelnemer']['datumafsluiting']['year']) && !empty($this->data['IzDeelnemer']['datumafsluiting']['month']) && !empty($this->data['IzDeelnemer']['datumafsluiting']['day'])) {
            $datumafsluiting =$this->data['IzDeelnemer']['datumafsluiting']['year']."-".$this->data['IzDeelnemer']['datumafsluiting']['month']."-".$this->data['IzDeelnemer']['datumafsluiting']['day'];
        }

    } else {
        $datumafsluiting =$this->data['IzDeelnemer']['datumafsluiting'];
    }
}
?>
<fieldset class="data" id="afsluiting" style="display: block;">
    <h2>Afsluiting</h2>
    <?php if ($persoon_model == 'Klant' && $eropuit): ?>
        <?php $eropuitUrl = $this->Html->url(array(
            'controller' => 'groepsactiviteiten',
            'action' => 'groepen',
            'Klant',
            $iz_deelnemer['IzDeelnemer']['foreign_key'],
        )); ?>
        <font color='red'>
            Let op, deze persoon is lid van de groep "ErOpUit Kalender".
            Bij afsluiting van het IZ-dossier moet dat lidmaatschap wellicht ook opgezegd worden.
            <a href="<?= $eropuitUrl ?>">Klik hier</a> om dat te doen
            (hiervoor zijn ECD-toegangsrechten voor Groepsactiviteiten nodig).
        </font>
    <?php endif; ?>

    <?= $this->Form->create('IzDeelnemer', array('url' => $url)) ?>
    <br>
    <p style="color: red;">
    <?= !empty($has_active_koppelingen) ? 'Er is nog een lopende koppeling, of een open hulpvraag. Sluit die eerst, voor je het dossier sluit.' : ''; ?>
    </p>
    <br>
    <label>Datum afsluiting</label>
        <?= $date->input("IzDeelnemer.datumafsluiting", $datumafsluiting,
            array(
                'label' => 'Afsluit datum',
                'rangeHigh' => (date('Y') + 10).date('-m-d'),
                'rangeLow' => (date('Y') - 19).date('-m-d'),
            ));
        ?>
    <br>
    <label>Reden afsluiting:</label>
    <?php
        if (empty($is_afgesloten)) {
            echo $this->Form->input('iz_afsluiting_id', array(
                'options' => $iz_afsluitingen_active,
            ));
        } else {
            echo $iz_afsluitingen[$this->data['IzDeelnemer']['iz_afsluiting_id']]."<br/>";
        }
    ?>
    <br>
    <?= $this->Form->end('Opslaan') ?>
    <?php
    if ($is_afgesloten) {
        echo $this->Html->link('ErOpUit Kalender', array(
            'controller' => 'groepsactiviteiten',
            'action' => 'groepen',
            $persoon_model,
            $persoon[$persoon_model]['id'],
        ));
    }
    ?>

    </fieldset>



<?php

if (!empty($is_afgesloten)) {
    echo $html->link('Opnieuw aanmelden', array(
            'controller' => 'iz_deelnemers',
            'action' => 'opnieuw_aanmelden',
            $id,
    ));
}
$this->Js->buffer(<<<EOS

Ecd.afsluit_disable = function(active) {
    if(active) {
        $('#afsluiting').find('*:input').each(function () {
            $(this).attr('disabled', true);
        });
    }
}

Ecd.afsluit_disable('{$has_active_koppelingen}');

EOS
);

?>

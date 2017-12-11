<h2>Maatschappelijk werk</h2>
<?= $this->Html->link('Rapportage', array(
    'controller' => 'maatschappelijk_werk',
    'action' => 'rapportage',
)) ?>
<?= $this->Form->create('Klant', array('controller' => 'MaatschappelijkWerk', 'action'=>'index', 'id'=>'filters')) ?>
<?php
    $dd = array('type' => 'text', 'label' => false);
    $dm = array('type' => 'select', 'options' => $medewerkers, 'label' => false);
?>
<table class="filter">
    <tr>
        <td class="klantnrCol">
            <?= $this->Form->input('id', $dd) ?>
        </td>
        <td class="voornaamCol">
            <?= $this->Form->input('voornaam', $dd) ?>
        </td>
        <td class="achternaamCol">
            <?= $this->Form->input('achternaam', $dd) ?>
        </td>
        <td class="gebortedatumCol">
            <?= $this->Form->input('geboortedatum', $dd) ?>
        </td>
        <td class="medewerkerCol">
            <?= $this->Form->input('medewerker_id', $dm) ?>
        </td>
        <td class="datumCol">
            <?= $this->Date->input('verslagen.laatste_rapportage', null, [
                'label' => false,
                'rangeHigh' => date('Y').date('-m-d'),
                'rangeLow' => '2010'.date('-m-d'),
            ]); ?>
            <?= $this->Form->checkbox('alle_klanten', ['hiddenField' => false]) ?>
            <?= $this->Form->label('alle_klanten', 'Alle DRG-klanten tonen') ?>
        </td>
        <td colspan="1"></td>
    </tr>
</table>
<?= $this->Form->end() ?>
<?php
    $onclick_action = $rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
    $ajax_url = $this->Html->url('/MaatschappelijkWerk/index/rowUrl:'.$onclick_action, true);
    $this->Js->get('#filters');
    $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#KlantMedewerkerId');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#VerslagenLaatsteRapportage');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#VerslagenLaatsteRapportage-dd');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#VerslagenLaatsteRapportage-mm');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#KlantAlleKlanten');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
?>
<?= $this->Js->writeBuffer() ?>
<div id="contentForIndex">
    <?= $this->element('klantenlijst', array('maatschappelijkwerk' => true)) ?>
</div>

<h2><?= $persoon_model ?></h2>

<p>
    <?= $this->Html->link($this->Html->image('user_add.png'), ['action' => 'add'], ['escape' => false, 'title' => __('add', true)]) ?>
    <?= $this->Html->link(__('Nieuwe vrijwilliger', true), ['action' => 'add']) ?>
</p>

<?= $form->create($persoon_model, ['controller' => $persoon_model, 'action' => 'index', 'id' => 'filters', 'selection' => $persoon_model]) ?>
<?= $form->hidden('selectie', ['value' => $persoon_model]) ?>
<?php
    $dd = ['type' => 'text', 'label' => false];
    $dm = ['type' => 'select', 'options' => $medewerkers, 'label' => false];
?>
<table class="filter">
    <tr>
        <td class="klantnrCol">
            <?= $form->input('id', $dd) ?>
        </td>
        <td class="voornaamCol">
            <?= $form->input('voornaam', $dd) ?>
        </td>
        <td class="achternaamCol">
            <?= $form->input('achternaam', $dd) ?>
        </td>
        <td class="gebortedatumCol">
            <?= $form->input('geboortedatum', $dd) ?>
        </td>
        <td class="medewerkerCol">
            <?= $form->input('medewerker_id', $dm) ?>
        </td>
        <td class="overeenkomstCol">
            <?= $form->input('vog_aangevraagd', ['type' => 'select', 'options' => ['' => '', 0 => 'Nee', 1 => 'Ja'], 'label' => false]) ?>
        </td>
        <td class="overeenkomstCol">
            <?= $form->input('vog_aanwezig', ['type' => 'select', 'options' => ['' => '', 0 => 'Nee', 1 => 'Ja'], 'label' => false]) ?>
        </td>
        <td class="overeenkomstCol">
            <?= $form->input('overeenkomst_aanwezig', ['type' => 'select', 'options' => ['' => '', 0 => 'Nee', 1 => 'Ja'], 'label' => false]) ?>
        </td>
    </tr>
</table>
<?= $form->end() ?>
<div id="contentForIndex">
    <?= $this->element('personen_lijst', ['bot' => false]) ?>
</div>
<?php
    $onclick_action = $rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
    $ajax_url = $this->Html->url('/vrijwilligers/index/rowUrl:'.$onclick_action, true);
    $this->Js->get('#filters');
    $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#VrijwilligerMedewerkerId');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#VrijwilligerVogAangevraagd');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#VrijwilligerVogAanwezig');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#VrijwilligerOvereenkomstAanwezig');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
?>
<?= $this->Js->writeBuffer() ?>

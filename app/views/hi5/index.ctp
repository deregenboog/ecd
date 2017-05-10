<h2><?= __('Hi5 Klantenlijst') ?></h2>
<?= $html->link(
    'Nieuwe klant invoeren',
    [
        'controller' => 'klanten',
        'action' => 'add',
    ],
    [],
    __('Hebt u de algemene klantenlijst al gechecked? Weet u zeker dat dit een nieuwe klant is?', true)
) ?>
<?= $form->create('Klant', array(
        'controller' => 'hi5',
        'action' => 'index',
        'id' => 'filters',
    )) ?>
<?php $dd = ['type' => 'text', 'label' => false]; ?>
<table class="index filtered">
    <thead>
        <tr>
            <th class="noborder">
                <?= $form->input('id', $dd)?>
            </th>
            <th class="noborder">
                <?= $form->input('voornaam', $dd) ?>
            </th>
            <th class="noborder">
                <?= $form->input('achternaam', $dd) ?>
            </th>
            <th class="noborder">
                <?= $form->input('geboortedatum', $dd) ?>
            </th>
            <th class="noborder">
                <?= $form->input('show_all', [
                    'type' => 'checkbox',
                    'label' => 'Toon alle Regenboog klanten',
                    'checked' => false,
                ]) ?>
            </th>
            <th class="noborder" colspan="5"></th>
        </tr>
        <?php
            $ajax_url = $this->Html->url('/Hi5/index', true);
            $this->Js->get('#filters');
            $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
            $this->Js->get('#KlantShowAll');
            $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
        ?>
    </thead>
    <tbody id="contentForIndex">
        <?= $this->element('hi5_klantenlijst') ?>
    </tbody>
</table>
<?= $form->end() ?>
<?= $js->writeBuffer() ?>

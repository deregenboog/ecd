<?= $this->element('iz_subnavigation') ?>
<?=
    $html->link(
        'Nieuwe deelnemer',
        array('controller' => 'klanten', 'action' => 'add', 'generic' => true),
        array(),
        __('Hebt u de algemene deelnemerslijst al gecheckt? Weet u zeker dat dit een nieuwe deelnemer is?', true)
    )
?>
<br>
<h2><?= __('Deelnemers') ?></h2>
<?= $form->create('IzKlant', array('controller' => 'iz_deelnemers', 'action'=>'index', 'id' => 'filters', 'selection' => 'IzKlant')) ?>
<?= $form->hidden('selectie', array('value' => 'IzKlant')) ?>

<?php
    $dd = array('type' => 'text', 'label' => false);
    $dm = array('type' => 'select', 'style'=>'width: 160px', 'options' => array('' => '') + $viewmedewerkers, 'label' => false);
    $we = array('type' => 'select', 'style'=>'width: 100px', 'options' => array('' => '') + $werkgebieden, 'label' => false);
?>
<table class="filter">
    <tr>
        <td class="klantnrCol"><?= $form->input('id', $dd) ?></td>
        <td class="voornaamCol"><?= $form->input('voornaam', $dd) ?></td>
        <td class="achternaamCol"><?= $form->input('achternaam', $dd) ?></td>
        <td class="gebortedatumCol"><?= $form->input('geboortedatum', $dd) ?></td>
        <td class="izProjectsCol">
            <?= $this->Form->input(
                'project_id', array(
                    'label' => false,
                    'type' => 'select',
                    'style'=>'width: 100px',
                    'options' => $projectlists,
            )) ?>
        </td>
<?php
    echo '<td class="IzMedewerkerCol">'.$form->input('medewerker_id', $dm).'</td>';

    echo '<td class="werkgebiedCol">'.$form->input('werkgebied', $we).'</td>';
        echo '<td class="show_allCol">'.$form->input('show_all', array(
            'type' => 'checkbox',
            'label' => 'Toon alle Regenboog klanten',
            'checked' => false,
        )).'</td>';
    echo '<td colspan="2"></td>';

    echo '</tr></table>';

    echo $form->end();

    $onclick_action =
        $rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
    $ajax_url =
        $this->Html->url("/iz_deelnemers/index/{'IzKlant'}.selectie:{'IzKlant'}/rowUrl:{$onclick_action}", true);
    $this->Js->get('#filters');
    $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.'IzKlant'.'MedewerkerId');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.'IzKlant'.'Werkgebied');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.'IzKlant'.'ProjectId');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.'IzKlant'.'ShowAll');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

    echo $this->Js->writeBuffer();
?>

<div id="contentForIndex">
    <?= $this->element('iz_klanten') ?>
</div>

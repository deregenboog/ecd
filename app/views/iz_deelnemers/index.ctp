<?php
    echo $this->element('iz_subnavigation');
?>

<?php
    if ($persoon_model == 'Klant') {
        echo $html->link('Nieuwe deelnemer', array('controller' => 'klanten', 'action' => 'add', 'generic' => true), array(),
            __('Hebt u de algemene deelnemerslijst al gecheckt? Weet u zeker dat dit een nieuwe deelnemer is?', true));
    } else {
        echo $html->link('Nieuwe vrijwilliger', array('controller' => 'vrijwilligers', 'action' => 'add', 'generic' => true), array(),
            __('Hebt u de algemene vrijwilligerlijst al gecheckt? Weet u zeker dat dit een nieuwe vrijwilliger is?', true));
    }
    echo "<br/>";

    $wachtlijst_label = "";

    if ($wachtlijst) {
        $wachtlijst_label = '- Wachtlijst';
    }
?>

<h2><?= $persoon_model; ?> <?= $wachtlijst_label; ?></h2>

<?php

    echo $form->create($persoon_model, array('controller' => 'iz_deelnemers', 'action'=>'index', 'id'=>'filters', 'selection' => $persoon_model));

    echo $form->hidden('selectie', array('value' => $persoon_model));

    echo $form->hidden('wachtlijst', array('value' => $wachtlijst));

    $dd = array('type' => 'text', 'label' => false);
    $dm = array('type' => 'select', 'style'=>'width: 160px', 'options' => array('' => '') + $viewmedewerkers, 'label' => false);
    $we = array('type' => 'select', 'style'=>'width: 100px', 'options' => array('' => '') + $werkgebieden, 'label' => false);

    echo '<table class="filter"><tr>';

    echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';

    echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';

    echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';

    echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';

    echo '<td class="izProjectsCol">';

    echo $this->Form->input(
            'project_id', array(
                'label' => false,
                'type' => 'select',
                'style'=>'width: 100px',
                'options' => $projectlists,
        ));

    echo "</td>";

    echo '<td class="IzMedewerkerCol">'.$form->input('medewerker_id', $dm).'</td>';

    echo '<td class="werkgebiedCol">'.$form->input('werkgebied', $we).'</td>';

    if (empty($wachtlijst)) {

        echo '<td class="show_allCol">'.$form->input('show_all', array(
            'type' => 'checkbox',
            'label' => 'Toon alle Regenboog-klanten',
            'checked' => false,
        )).'</td>';

    }

    echo '<td colspan="2"></td>';

    echo '</tr></table>';

    echo $form->end();

    $onclick_action =
        $rowOnclickUrl['controller'].'.'.$rowOnclickUrl['action'];
    $ajax_url =
        $this->Html->url("/iz_deelnemers/index/{$persoon_model}.selectie:{$persoon_model}/rowUrl:{$onclick_action}", true);
    $this->Js->get('#filters');
    $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.$persoon_model.'MedewerkerId');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.$persoon_model.'Werkgebied');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.$persoon_model.'ProjectId');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');
    $this->Js->get('#'.$persoon_model.'ShowAll');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

    echo $this->Js->writeBuffer();
?>

<div id="contentForIndex">
    <?php
        echo $this->element('personen_lijst', array('iz' => true));
    ?>
</div>
<?php
?>




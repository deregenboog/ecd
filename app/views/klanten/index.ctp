<?php
    $userGroups = $this->Session->read('Auth.Medewerker.Group');
?>

<h2><?php __('Klantenlijst');?></h2>

<?php
    echo $html->link('Nieuwe klant invoeren', array('action' => 'add'), array(),
        __('Hebt u de algemene klantenlijst al gechecked? Weet u zeker dat dit een nieuwe klant is?', true));
    if (in_array(GROUP_TEAMLEIDERS, $userGroups) || in_array(GROUP_DEVELOP, $userGroups)) {
        echo ' | '.
            $this->Html->link('Lijst van mogelijk dubbele invoer', array(
                'controller' => 'klanten',
                'action' => 'findDuplicates',
            ));
    }

    echo $form->create('Klant', array('controller' => 'klanten', 'action'=>'index', 'id'=>'filters'));
    $dd = array('type' => 'text', 'label' => false);
    echo '<table class="filter"><tr>';
    echo '<td class="klantnrCol">'.$form->input('id', $dd).'</td>';
    echo '<td class="voornaamCol">'.$form->input('voornaam', $dd).'</td>';
    echo '<td class="achternaamCol">'.$form->input('achternaam', $dd).'</td>';
    echo '<td class="gebortedatumCol">'.$form->input('geboortedatum', $dd).'</td>';
    echo '<td colspan="3"></td>';
    echo '</tr></table>';

    echo $form->end();
    $ajax_url = $this->Html->url('/klanten/index', true);
    $this->Js->get('#filters');
    $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');
    echo $js->writeBuffer();
?>

<div id="contentForIndex">
    <?php echo $this->element('klantenlijst'); ?>
</div>

<?= $this->element('pfo_subnavigation'); ?>
<h2><?php __('PFO Clienten lijst'); ?></h2>

<?php
    $afsluitdatum = '';
    if ($active) {
        $afsluitdatum = '';
    }
    $a = ['' => ''];
    $groepen = $a + $groepen;
?>

<?= $html->link('Nieuwe klant invoeren', ['action' => 'add'], [], __('Weet u zeker dat dit een nieuwe klant is?', true)); ?>

<?= $form->create('PfoClient', ['controller' => 'pfo_clienten', 'action' => 'index', 'id' => 'filters']); ?>
    <table class="filter">
        <tr>
            <td class="pfovoornaamColHeader">
                <?= $form->input('roepnaam', ['type' => 'text', 'label' => false]); ?>
            </td>
            <td class="pfoachternaamColHeader">
                <?= $form->input('achternaam', ['type' => 'text', 'label' => false]); ?>
            </td>
            <td class="pfogroepColHeader">
                <?= $form->input('groep', ['type' => 'select', 'options' => $groepen, 'label' => false]); ?>
            </td>
            <td class="medewerkerCol">
                <?= $form->input('medewerker_id', ['type' => 'select', 'options' => $medewerkers, 'label' => false]); ?>
            </td>
            <td class="medewerkerCol">
                <?= $afsluitdatum; ?>
            </td>
        </tr>
    </table>
<?= $form->end(); ?>

<div id="contentForIndex">
    <?php echo $this->element('pfoclientenlijst'); ?>
</div>

<?php
    if (empty($active)) {
        $ajax_url = $this->Html->url('/pfo_clienten/index', true);
    } else {
        $ajax_url = $this->Html->url('/pfo_clienten/index/'.$active, true);
    }

    $this->Js->get('#PfoClientRoepnaam');
    $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');

    $this->Js->get('#PfoClientAchternaam');
    $this->Js->event('keyup', 'ajaxFilter("'.$ajax_url.'")');

    $this->Js->get('#PfoClientGroep');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

    $this->Js->get('#PfoClientMedewerkerId');
    $this->Js->event('change', 'ajaxFilter("'.$ajax_url.'")');

    echo $js->writeBuffer();
?>

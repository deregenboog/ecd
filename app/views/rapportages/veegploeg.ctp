<div class="form">
    <fieldset id="fldVeegploegRapportages">
        <legend>ECD veegploegrapportages</legend>
        <div id="divAjaxLoading" class="centeredContentWrap" style="display:none;">
            <?= $this->Html->image('ajax-loader.gif') ?>
            <br/>
            <?php __('Uw rapportage wordt aangemaakt. Dit kan 1 minuut duren.') ?>

        </div>
        <div id="divVeegploegReport">
            <?php __('Selecteer filteropties.') ?>
        </div>
    </fieldset>
</div>

<iframe name="iframeExcel" style="display: none;"></iframe>

<div class="actions">
    <?=$this->element('report_filter', array('disableGender' => true, 'disableLocation' => true, 'enableExcel' => true, 'overrideAction' => 'ajaxVeegploeg'));?>
</div>
<script type="text/javascript">
    $(startVeegploegReports);
</script>

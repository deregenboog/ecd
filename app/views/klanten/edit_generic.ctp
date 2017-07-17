<script type="text/javascript">
    var amocCountries = <?= json_encode($amocCountries) ?>;
</script>

<div class="klanten ">
    <?= $this->element('persoon_add_edit', array('name' => 'klanten')) ?>
</div>

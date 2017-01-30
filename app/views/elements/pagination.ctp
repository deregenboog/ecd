<p>
	<?= $this->Paginator->counter(array(
		'format' => __('Page %page% of %pages% (total: %count% volunteers)', true),
	)) ?>
</p>

<div class="paging">
	<?= $this->Paginator->prev('<< '.__('previous', true)) ?>
	| <?= $this->Paginator->numbers() ?>
	| <?= $this->Paginator->next(__('next', true).' >>') ?>
</div>

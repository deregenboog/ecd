<fieldset class="document_manager_box">
	<legend>Documentbeheer</legend>

	<?php if (!empty($documents)) {
	?>
			<?php
				foreach ($documents as $doc) {
					$link = $this->Html->link($doc['title'], array(
						'controller' => 'attachments',
						'action' => 'download',
						$doc['id'],
					)); ?>
			<div class="attachment">
				<?php 
					if ($logged_in_user_id == $doc['user_id']) {
						$deleteLink = $html->link($html->image('x.png'), array(
							'controller' => 'attachments',
							'action' => 'delete',
							$doc['id'],
						), array('escape' => false),
						__('Do you really want to delete the document?', true)); ?>
				<div class="delete_link"><?= $deleteLink ?></div>
				<?php 
					} ?>
				<div class="icon"><?= $html->image('page.png'); ?></div>
				<div><?= $link ?></div>
				<div><?= $this->Date->show($doc['created']) ?></div>
			</div>
			<?php 
				} ?>
	<?php 
} else {
	__('No documents.');
} ?>

	<div class="editWrench">
		<?php
			$wrench = $html->image('page_attach.png');
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $upload_url, $opts);
		?>
	</div>
</fieldset>

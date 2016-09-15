<?php

$logged_in_user_id = $this->Session->read('Auth.Medewerker.id');
?>
<fieldset class="document_manager_box">
	<legend><?php __('Documentbeheer') ?></legend>
<?php if (!empty($klant['Document'])) {
	?>
		<?php
			foreach ($klant['Document'] as $doc) {
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
}
?>
	<div class="editWrench">
		<?php 
			$attach = $html->image('page_attach.png');
			$url = array(
				'controller' => 'back_on_track',
				'action' => 'upload',
				$data['Klant']['id'],
				$group,
			);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($attach.' '.__('upload', true), $url, $opts);
		?>
	</div>
</fieldset>

<?php 
$model = Inflector::camelize(Inflector::singularize($name));
$controller = strtolower(Inflector::pluralize($name));
?>
<fieldset>
	<legend>Basisgegevens</legend>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>Voornaam</td>
			<td><?php echo $data[$model]['voornaam']; ?></td>
		</tr>
		<tr>
			<td>Tussenvoegsel</td>
			<td><?php echo $data[$model]['tussenvoegsel']; ?></td>
		</tr>
		<tr>
			<td>Achternaam</td>
			<td><?php echo $data[$model]['achternaam']; ?></td>
		</tr>
		<tr>
			<td>Roepnaam</td>
			<td><?php echo $data[$model]['roepnaam']; ?></td>
		</tr>
		<tr>
			<td>Geslacht</td>
			<td><?php echo $data['Geslacht']['volledig']; ?></td>
		</tr>
		<tr>
			<td>Geboortedatum</td>
			<td><?php echo $date->show($data[$model]['geboortedatum'], array('short'=>true)); ?></td>
		</tr>
		<tr>
			<td>Geboorteland</td>
			<td><?php echo $data['Geboorteland']['land']; ?></td>
		</tr>
		<tr>
			<td>Nationaliteit</td>
			<td><?php echo $data['Nationaliteit']['naam']; ?></td>
		</tr>
		<tr>
			<td><?php __('bsn')?></td>
			<td><?php echo $data[$model]['BSN']; ?></td>
		</tr>
		<?php if (isset($data[$model]['laatste_TBC_controle'])) {
	?>
		<tr>
			<td>Laatste TBC controle</td>
			<td><?php echo $date->show($data[$model]['laatste_TBC_controle'], array('short'=>true)); ?></td>
		</tr>
		<?php 
} ?>
		<tr>
			<td>Medewerker</td>
			<td><?php echo $data['Medewerker']['voornaam'].' '.
				$data['Medewerker']['tussenvoegsel'].' '.
				$data['Medewerker']['achternaam']; ?></td>
		</tr>

		<?php if ($name === 'vrijwilligers'): ?>
		    <tr>
		        <td>VOG aangevraagd</td>
		        <td><?= $data[$model]['vog_aangevraagd'] ? 'Ja' : 'Nee' ?></td>
		    </tr>
		    <tr>
		        <td>VOG aanwezig</td>
		        <td><?= $data[$model]['vog_aanwezig'] ? 'Ja' : 'Nee' ?></td>
		    </tr>
		    <tr>
		        <td>Vrijwilligersovereenkomst aanwezig</td>
		        <td><?= $data[$model]['overeenkomst_aanwezig'] ? 'Ja' : 'Nee' ?></td>
		    </tr>
		<?php endif; ?>
	</table>
	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => $controller, 'action' => 'edit', $data[$model]['id'], 'generic' => true );
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
		?>
	</div>
</fieldset>

<fieldset>
	<legend>Contactgegevens</legend>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td>Adres</td>
			<td><?php echo $data[$model]['adres']; ?></td>
		</tr>
		<tr>
			<td>Postcode</td>
			<td><?php echo $data[$model]['postcode']; ?></td>
		</tr>
		<tr>
			<td>Woonplaats</td>
			<td><?php echo $data[$model]['plaats']; ?></td>
		</tr>
		<tr>
			<td>Werkgebied</td>
			<td><?php echo $data[$model]['werkgebied']; ?></td>
		</tr>
		<tr>
			<td>Postcodegebied</td>
			<td><?php echo $data[$model]['postcodegebied']; ?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><?php echo $data[$model]['email']; ?></td>
		</tr>
		<tr>
			<td>Mobiel</td>
			<td><?php echo $data[$model]['mobiel']; ?></td>
		</tr>
		<tr>
			<td>Telefoon</td>
			<td><?php echo $data[$model]['telefoon']; ?></td>
		</tr>
		<tr>
			<td>Opmerking</td>
			<td><?php echo $data[$model]['opmerking']; ?></td>
		</tr>
		<tr>
		<td><?php 
				 if (! empty($data[$model]['geen_email'])) {
					 echo __('Geen email');
				 } else {
					 echo __('Wel email');
				 }
			?></td>

			<td></td>
		</tr>
		<tr>
		<td><?php 
				 if (! empty($data[$model]['geen_post'])) {
					 echo __('Geen post');
				 } else {
					 echo __('Wel post');
				 }
			?></td>

			<td></td>
		</tr>
	</table>

	<div class="editWrench">
		<?php 
			$wrench = $html->image('wrench.png');
			$url = array('controller' => $controller, 'action' => 'edit', $data[$model]['id'], 'generic' => true );
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts);
		?>
	</div>
</fieldset>

<?php if (isset($show_documents) && $show_documents) {
			?>
<fieldset class="document_manager_box">
	<legend>Documentbeheer</legend>

	<?php if (!empty($data['GroepsactiviteitenDocument'])) {
				?>
			<?php
				foreach ($data['GroepsactiviteitenDocument'] as $doc) {
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
			$url = array(
				'controller' => 'groepsactiviteiten',
				'action' => 'upload',
				$name,
				$data[$name]['id'],
			);
			$opts = array('escape' => false, 'title' => __('edit', true));
			echo $html->link($wrench, $url, $opts); ?>
	</div>
</fieldset>
<?php 
		} ?>

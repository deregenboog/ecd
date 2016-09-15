<?php
	$afsluitdatum_header = "";

	if ($active) {
		$afsluitdatum_header = "Afsluitdatum";
	}

	$paginator->options(array(
			   'update' => '#contentForIndex',
			'evalScripts' => true,
	));
?>
	
	<table id="clientList" class="index filtered">
	<tr>
			<th class="pfovoornaamCol"><?php echo $this->Paginator->sort('Roepnaam', 'roepnaam', $filter_options);?></th>
			<th class="pfoachternaamCol"><?php echo $this->Paginator->sort('Achternaam', 'achternaam', $filter_options);?></th>
			<th class="pfogroepCol"><?php echo $this->Paginator->sort('Groep', 'groep', $filter_options);?></th>
			<th class="medewerkerCol"><?php echo $this->Paginator->sort('Medewerker', 'medewerker_id', $filter_options);?></th>
			<th class="medewerkerCol"><?= $afsluitdatum_header; ?></th>
			</tr>
	<?php

	$i = 0;

	foreach ($pfoclienten as $pfoclient):
		$afsluitdatum = "";
		if ($active) {
			$afsluitdatum = $pfoclient['PfoClient']['datum_afgesloten'];
		}
		if ($i++ % 2 == 0) {
			$altrow = ' altrow';
		} else {
			$altrow = null;
		}
	?>
	<?php if (!isset($add_to_list)):?> 
		<?php
			if (!isset($rowOnclickUrl)) {
				$url = $html->url(array('controller' => 'PfoClienten', 'action' => 'view', $pfoclient['PfoClient']['id']));
			} else {
				$urlArray = $rowOnclickUrl;
				$urlArray[] = $pfoclient['PfoClient']['id'];
				$url = $this->Html->url($urlArray);
			}
		?>
		<tr class="pfoclientenlijst-row<?php echo $altrow;?>" 
					onMouseOver="this.style.cursor='pointer'" 
					onClick="location.href='<?php echo $url; ?>';"
					id='pfoclienten_<?php echo $pfoclient['PfoClient']['id']?>'>		
	<?php else:?>
		<tr class="pfoclientenlijst-row<?php echo $altrow;?>" onMouseOver="this.style.cursor='pointer'" id='pfoclienten_<?php echo $pfoclient['PfoClient']['id']?>'>
	<?php endif;?>
			<td class="pfovoornaamCol"><?= h($pfoclient['PfoClient']['roepnaam']);?>&nbsp;</td>
			<td class="pfoachternaamCol"><?php
				if (!empty($pfoclient['PfoClient']['tussenvoegsel'])) {
					echo  h($pfoclient['PfoClient']['tussenvoegsel'])." ";
				}
				echo h($pfoclient['PfoClient']['achternaam']);
			?>&nbsp;</td>
			<td class="pfogroepCol"><?php
				if (isset($groepen[$pfoclient['PfoClient']['groep']])) {
					echo $groepen[$pfoclient['PfoClient']['groep']];
				} else {
					echo $pfoclient['PfoClient']['groep'];
				}
			?>&nbsp;</td>
			<td class="medwerkerCol"><?php echo $viewmedewerkers[$pfoclient['Medewerker']['id']]; ?>&nbsp;</td>
			<td class="medwerkerCol"><?= $afsluitdatum; ?>&nbsp;</td>
			</tr>
	<?php
			endforeach;
	?>
	</table>
	<?php
	if (isset($add_to_list)) {

		foreach ($pfoclienten as $pfoclient) {
			$this->Js->get('#pfoclienten_'.$pfoclient['PfoClient']['id'])->event('click',
				$this->Js->request('/registraties/ajaxAddRegistratie/'.$pfoclient['PfoClient']['id'].'/'.$locatie_id,
					array('update' => '#registratielijst',
						'dataExpression' => true,
						'evalScripts' => true,
						'method' => 'post',
						'before' => '$("#loading").css("display","block")',
						'complete' => '$("#loading").css("display","none");',
						)
					)
				);
		}
	}
	?>
	<p>
	
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true),
	));
	?>	</p>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< '.__('previous', true), $filter_options, null, array('class'=>'disabled'));?>
	 |	<?php echo $this->Paginator->numbers();?>
	 |
		<?php echo $this->Paginator->next(__('next', true).' >>', $filter_options, null, array('class' => 'disabled'));?>
	</div>
<?php

	if ($this->name === 'Registraties' && $this->action === 'index') {

		$this->Js->get('.pfoclientenlijst-row')->event('click',
			$this->Js->request('/registraties/index/'.$locatie_id,
				array(
					'update' => '#contentForIndex',
					'before' => '$("#filters :input[type=\'text\']").val("");',
				)

			)
		);
	}

	echo $js->writeBuffer();
?>

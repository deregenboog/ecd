<ul id="groepen">

<?php 
	foreach ($groepsactiviteiten_list as $groep_id => $groep) {
		$url = $this->Html->url(array('action' => $action, $groep_id)); 
?>
	<li ><a href="<?=$url?>"><?php echo $groep ?></a></li>

<?php 
} ?>

</ul>


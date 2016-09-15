<?php

if (! empty($data)) {
	$zrmReport = $data;
}

$title = $zrmReport['ZrmReport']['request_module'];

if (isset($zrm_data['zrm_names'][$zrmReport['ZrmReport']['request_module']])) {
	$title = $zrm_data['zrm_names'][$zrmReport['ZrmReport']['request_module']];
}

 echo "<table class=\"zrmreport\"><caption>\n";
 echo $title
 .", ".$date->show($zrmReport['ZrmReport']['created'], array('short'=>true));
 echo "</caption><thead><tr><th>Domein</th><th>Score</th></tr></thead<tbody>";

 foreach ($zrm_data['zrm_items'] as $k => $v) {
	
	 if (empty($zrmReport['ZrmReport'][$k])) {
		 continue;
	 }
	 
	 $w=$zrmReport['ZrmReport'][$k];
	 
	 if ($w > 10) {
		 $w=10;
	 }
	 
	 echo "<tr	><td>".$v."</td>";
	 echo "<td><p style=\"width: ". 50 * $w."px;\">&nbsp; ".$zrmReport['ZrmReport'][$k]."</p> </td>";
	 echo "</tr>";
 }
 echo "</tbody></table><br />";

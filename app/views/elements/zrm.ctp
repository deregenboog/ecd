<?php 

if (empty($model) || empty($zrm_data)) {
	echo "The zrm_widget can not be displayed, please supply all parameters";
	return;
}
$zrm_required_fields = $zrm_data['zrm_required_fields'];
$zrm_items = $zrm_data['zrm_items'];
$zrm_models = $zrm_data['zrm_models'];
$zrm_names = $zrm_data['zrm_names'];

switch ($model) {
	case 'IzIntake' :
		$request_module = 'IzIntake';
		break;
	case 'Hi5Intake' :
		$request_module = 'Hi5';
		break;
	case 'AwbzIntake' :
		$request_module = 'Awbz';
		break;
	case 'Klant' :
		$request_module = 'Klant';
		break;
	case 'GroepsactiviteitenIntake' :
		$request_module = 'GroepsactiviteitenIntake';
		break;
	default:
		$request_module = 'Intake';
}

$options = null;


if ($request_module == 'Klant' || $request_module == 'Intake') {
	$options = array();
	
	foreach ($zrm_models as $m => $groups) {
		
		foreach ($groups as $group) {
			
			if (in_array($group, $_SESSION['Auth']['Medewerker']['Group'])) {
				$options[$m] = $m;
				if (! empty($zrm_names[$m])) {
					$options[$m] = $zrm_names[$m];
				}
			}
			
		}
		
	}

	if (empty($options)) {
		
		$request_module = 'Klant';
		$options = null;
		
	} else {
		
		if (count($options) == 1) {
			
			reset($options);
			$request_module = key($options);

			$options = null;
		} else {
			
			$options = array('' => '') + $options;
			
		}
		
	}
}

if (!empty($this->data['ZrmReport']['request_module'])) {
	$request_module = $this->data['ZrmReport']['request_module'];
}

?>

	<h2>Zelfredzaamheidsmatrix 
	
	<?php 
	if (!empty($request_module)) {
		if (isset($zrm_names[$request_module])) {
			echo $zrm_names[$request_module];
		} else {
			echo $request_module;
		}
	}
	?>
	
	</h2>
	<table class="zrm">
		<thead>
			<tr>
				<th>
				
				<?php 

					if (empty($this->data["ZrmReport"]['id'])) {

						if (is_array($options)) {
							echo $this->Form->input('ZrmReport.request_module', array('type' => 'select', 'options' => $options, 'label' => ''));
						} else {
							echo $this->Form->input('ZrmReport.request_module', array('type' => 'hidden', 'value' => $request_module));
						}
					} else {
						echo $this->Form->input('ZrmReport.request_module', array('type' => 'hidden', 'value' => $request_module));
						echo $this->Form->input('ZrmReport.id', array('type' => 'hidden', 'value' => $this->data["ZrmReport"]['id']));
					}

				?>
				
				Domein
				</th>
				<th>1 – acute<br />problematiek</th> 
				<th>2 – niet<br />zelfredzaam</th>
				<th>3 – beperkt<br />zelfredzaam</th>
				<th>4 – voldoende<br />zelfredzaam</th>
				<th>5 – volledig<br />zelfredzaam</th>
				<th>99 –onbesproken</th>
			</tr>
		</thead>
		<tbody>

<?php 

	foreach ($zrm_items as $k => $v) {
		
		echo "<tr id=\"tr_{$k}\" >\n";
		echo "<td><b>{$v}</b></td><td>\n";
		
		echo $this->Form->input('ZrmReport.'.$k, array(
						'type' => 'radio',
						'legend' => '',
						'required' => false, // this is printed out
						'before' => "",
						'separator' => '</td><td>',
						'after' => "</td>",
						'options' => array(1 => '', 2 => '', 3 => '', 4 => '', 5 => '', 99 => '' ),
					)
		);
		
		echo "</td>\n";
		echo "</tr>\n";
	}

?>
		</tbody>
	</table>

<?php 

	$this->Js->buffer(
			'Ecd.zrm_widget.options = '.json_encode($zrm_required_fields)
	);

	$this->Js->buffer('Ecd.zrm_widget("'.$request_module.'");');

?>


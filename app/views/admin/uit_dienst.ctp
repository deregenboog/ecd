<?php

echo "<table>\n";
echo "<tr><th>Username</th></tr>\n";
if (!empty($medewerkers)) {
	foreach ($medewerkers as $medewerker) {
		$class = 'entrydisabled';
		if ($medewerker['Medewerker']['active']) {
			$class = 'entryenabled';
		}
		echo "<tr class=\"{$class}\"><td>{$medewerker['Medewerker']['username']}</td></tr>\n";
	}
}
echo "</table>\n";

<h1>Welkom bij Stichting De Regenboog Groep</h1>

<p style="width: 60%">
Dagelijks zet De Regenboog Groep zich in voor mensen met sociale problemen,
voor dak- en thuislozen en verslaafden en voor mensen met psychiatrische
klachten. Onze vrijwilligers bieden met ondersteuning van onze professionals
opvang, hulpverlening, werkervaringsplaatsen en activiteiten. Via sociale
contacten voorkomen we eenzaamheid en isolement. Gezamenlijk werken wij er aan
mensen in staat te stellen zelf vorm en inhoud te geven aan hun bestaan en
actief deel te nemen aan de maatschappij. <b>Als mensen onder elkaar.</b>
</p>
<br/>
<br/>

<h2>
<ul>
<?php

foreach ($menu_allowed as $controller => $text) {
	echo "<li>".
		$this->Html->link(__($text, true), '/'.$controller).
		"</li>";
}
if (empty($menu_allowed)) {
	echo "<li>".
		$this->Html->link(__('Login', true), '/medewerkers/login').
		"</li>";
}
?>
</ul>
</h2>

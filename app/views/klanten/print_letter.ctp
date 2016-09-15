<div class="only_on_screen message">
	<h1>Print de verwijsbrief uit, en stuur deze persoon door naar AMOC</h1>
	<?= $this->Html->link(__('Terug naar klantenlijst', true), array(
		'action' => 'index',
	)); ?>
</div>

<h1>Amsterdam, <?= $this->date->show(date('Y-m-d')) ?></h1>

<p>Dear <?= $klant['Klant']['voornaam'] ?> 
	<?= $klant['Klant']['tussenvoegsel'] ?>
	<?= $klant['Klant']['achternaam'] ?>
	,
</p>

<p>
	<b>Date of birth:</b> <?= $this->date->show($klant['Klant']['geboortedatum']) ?><br/>
	<b>Country of origin:</b> <?= $klant['Geboorteland']['land'] ?>
</p>

<h1>Notice:</h1>
<p>
	With this letter you will be send to another location where you will get an intake with a social worker.
	During the intake the social worker will talk with you and depending on your individual situation the decision will be made if help can be offered and you’ll be assessed.
	You can go to AMOC, Stadhouderskade 159, for an intake on weekdays between 10:00-16:00 hrs.
</p>

<p>
	Con questa lettera vieni inviato in uno centro dove dovrai parlare con un assistente sociale.
	L'assistente sociale dopo aver parlato con te decidera', a seconda della tua situazione se e' possibile aiutarti nel modo migliore.
	Puoi andare ad AMOC, Stadhouderskade 159, per la tua registrazione, dal lunedi' al venerdi' dalle 10.00 alle 16.00.
</p>
<p>
	Razem z tym formularzem zostaniesz wyslany/a do innej placowki z naszej organizacji , gdzie bedziesz mial/a spotkanie z pracownikiem socjalnym.
	Podczas tej rozmowy, w zaleznosci od twojej indywidualnej sytuacji , zostanie podjeta decyzja czy nasza organizacja jest w stanie oferowac jakas pomoc.
	Na rozmowe z pracownikiem socjalnym mozesz isc do AMOC, Stadhouderskade 159, od poniedzialku do piatku pomiedzy 10.00 – 16.00.
</p>
<p>
	Cu aceasta scrisoare vei fi trimis catre o alta locatie ce apartine de noi, unde vei avea o intalnire cu un asistent social.
	In timpul acestei intalniri asistentul social va discuta cu tine si va evalua, in functie de situatia in care te afli, daca, si in ce fel, am putea sa te ajutam.
	Poti merge la AMOC, Stadhouderskade 159, pentru a discuta cu un asistent social de luni pana vineri intre orele 10:00-16:00.
</p>

<p>
Kind regards,<br/>
De Regenboog Groep
</p>


<p>
Check out the map below to find your way to AMOC<br/>
AMOC, Stadhouderskade 159
</p>

<p>
(Makom, van Ostadestraat 153)<br/>
(De Kloof, Kloveniersburgwal 95)<br/>
(Oud West, Bilderdijkstraat 182 hs)
</p>

<p>
<?= $this->Html->image('amoc_map'); ?>
</p>

<script type="text/javascript">
	window.print();
</script>

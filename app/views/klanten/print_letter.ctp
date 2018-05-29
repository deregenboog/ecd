<p>Amsterdam, <?= $this->date->show(date('Y-m-d')); ?></p>
<p>Dear visitor,</p>
<p>
    <b>Name:</b> <?= $klant['Klant']['voornaam']; ?> <?= $klant['Klant']['tussenvoegsel']; ?> <?= $klant['Klant']['achternaam']; ?><br/>
    <b>Date of birth:</b> <?= $this->date->show($klant['Klant']['geboortedatum']); ?><br/>
    <b>Country of origin:</b> <?= $klant['Geboorteland']['land']; ?>
</p>
<p>Notice:</p>
<p>With this letter you can go to AMOC, Stadhouderskade 159, for support, on weekdays between 10:00-17:00 hrs.</p>
<p>Con questa lettera puoi andare ad AMOC, Stadhouderskade 159, per parlare con un assistente sociale e la tua registrazione, dal lunedi' al venerdi' dalle 10.00 alle 17.00.</p>
<p>Razem z tym formularzem mozesz isc do AMOC-u ,  Stadhouderskade 159 na rozmowe  z pracownikiem socjalnym, , od poniedzialku do piatku pomiedzy 10.00 – 17.00.</p>
<p>Cu aceasta scrisoare poti merge la AMOC, Stadhouderskade 159, pentru a folosi serviciile noastre, de luni pana vinery de la 10:00 la 17:00.</p>
<p>Kind regards,<br/>De Regenboog Groep</p>

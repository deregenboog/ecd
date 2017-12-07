<style>
    html, body {
        height: 297mm;
        width: 210mm;
    }
</style>
<div class="only_on_screen message">
    <h1>Print de verwijsbrief uit, en stuur deze persoon door naar AMOC</h1>
    <?= $this->Html->link(__('Terug naar klantenlijst', true), ['action' => 'index']); ?>
</div>
<h1>
    Amsterdam, <?= $this->date->show(date('Y-m-d')); ?>
</h1>
<p>
    Dear <?= $klant['Klant']['voornaam']; ?> <?= $klant['Klant']['tussenvoegsel']; ?> <?= $klant['Klant']['achternaam']; ?>,
</p>
<p>
    <b>Date of birth:</b> <?= $this->date->show($klant['Klant']['geboortedatum']); ?><br/>
    <b>Country of origin:</b> <?= $klant['Geboorteland']['land']; ?>
</p>
<h1>Notice:</h1>
<p>
    With this letter you will be send to another location where you will get an intake with a social worker.
    During the intake the social worker will talk with you and depending on your individual situation the decision will be made if help can be offered and you’ll be assessed.
    You can go to Ondro Bong, Zeeburgerdijk 53, for an intake on Monday and Thursday between 09:00 - 11:00 hrs.
</p>
<p>
    Con questa lettera vieni inviato in uno centro dove dovrai parlare con un assistente sociale.
    L'assistente sociale dopo aver parlato con te decidera', a seconda della tua situazione se e' possibile aiutarti nel modo migliore.
    Puoi andare ad Ondro Bong, Zeeburgerdijk 53, per la tua registrazione, dal lunedi e giovedi dalle 09.00 alle 11.00.
</p>
<p>
    Con esta carta serás enviado a otra ubicación donde obtendrás un ingreso con un trabajador social.
    Durante la admisión, el trabajador social hablará con usted y, dependiendo de su situación individual, se tomará la decisión de ofrecerle ayuda y se lo evaluará.
    Puede ir a Ondro Bong, Zeeburgerdijk 53, para una recepción los lunes y jueves de 09:00 a 11:00.
</p>
<p>
    Razem z tym formularzem zostaniesz wyslany/a do innej placowki z naszej organizacji , gdzie bedziesz mial/a spotkanie z pracownikiem socjalnym.
    Podczas tej rozmowy, w zaleznosci od twojej indywidualnej sytuacji , zostanie podjeta decyzja czy nasza organizacja jest w stanie oferowac jakas pomoc.
    Na rozmowe z pracownikiem socjalnym mozesz isc do Ondro Bong, Zeeburgerdijk 53, w poniedzialek i czwartek pomiedzy 09.00 – 11.00.
</p>
<p>
    Cu aceasta scrisoare vei fi trimis catre o alta locatie ce apartine de noi, unde vei avea o intalnire cu un asistent social.
    In timpul acestei intalniri asistentul social va discuta cu tine si va evalua, in functie de situatia in care te afli, daca, si in ce fel, am putea sa te ajutam.
    Poti merge la Ondro Bong, Zeeburgerdijk 53, pentru a discuta cu un asistent social luni și joi intre orele 09:00-11:00.
</p>
<p>
    С этим письмом вы будете отправлены в другое место, где вы получите прием с социальным работником.
    Во время приема социальный работник будет разговаривать с вами, и в зависимости от вашей индивидуальной ситуации решение будет принято, если помощь может быть предложена, и вы будете оценены.
    Вы можете пойти в Ondro Bong, Zeeburgerdijk 53, для приема в понедельник и четверг с 09:00 до 11:00.
</p>
<p>
    &nbsp;
</p>
<p>
    Kind regards,<br/>
    De Regenboog Groep
</p>
<p>
    &nbsp;
</p>
<p>
    Check out the map below to find your way to Ondro Bong; Zeeburgerdijk 53
</p>
<p>
    <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d19488.14140234573!2d4.896115284674638!3d52.370099506296455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e2!4m5!1s0x47c609c73b4b14ef%3A0x7e86dfc7e2ced272!2sDam!3m2!1d52.3730701!2d4.8926473!4m5!1s0x47c60912a1c64c97%3A0x401ae004a5a7ab5e!2sZeeburgerdijk+53%2C+1094+AA+Amsterdam%2C+Nederland!3m2!1d52.366292599999994!2d4.9345994!5e0!3m2!1sen!2snl!4v1512030696878" width="800" height="500" frameborder="0" style="border: 0;"></iframe>
</p>
<script type="text/javascript">
    window.print();
</script>

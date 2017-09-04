<fieldset>
    <h2>Vrijwilliger Aanmelding</h2>
    <br/>
    <?= $html->link('bewerken', array('action' => 'aanmelding', 'Vrijwilliger', $this->data['Vrijwilliger']['id'])) ?>
    <br/>
    <br/>
    <?php
        $datum_aanmelding = "";
        if (! empty($this->data['IzDeelnemer']['datum_aanmelding'])) {
            if (is_array($this->data['IzDeelnemer']['datum_aanmelding'])) {
                $datum_aanmelding = $this->data['IzDeelnemer']['datum_aanmelding']['year']."-".$this->data['IzDeelnemer']['datum_aanmelding']['month']."-".$this->data['IzDeelnemer']['datum_aanmelding']['day'];
            } else {
                $datum_aanmelding = $this->data['IzDeelnemer']['datum_aanmelding'];
            }
        }
    ?>
    Datum aanmelding: <?= $datum_aanmelding ?>
    <br/><br/>
    Binnengekomen Via: <?= $viaPersoon[$this->data['IzDeelnemer']['binnengekomen_via']] ?>
    <br/><br/>
    <h4>Interesse in projecten</h4>
    <br/>
    <?php foreach ($this->data['IzDeelnemersIzProject'] as $project): ?>
        <?= $projectlists[$project['iz_project_id']] ?>
        <br/>
    <?php endforeach; ?>
    <br/><h4>Notitie</h4><br/>
    <?= preg_replace("/\n/", "<br/>\n", $this->data['IzDeelnemer']['notitie']) ?>
    <br/>
    <br/>

<h2>Intake deelnemer</h2>
<br/>

<?php
    $id = !empty($persoon['IzIntake']['id']) ? $persoon['IzIntake']['id'] : '';
    echo $html->link('bewerken', array('action' => 'intakes', $this->data['IzIntake']['iz_deelnemer_id']));
?>

<br/>
<br/>


<?php

    $intake_datum = date('Y-m-d');

    if (! empty($this->data['IzIntake']['intake_datum'])) {

        if (is_array($this->data['IzIntake']['intake_datum'])) {
            $intake_datum =$this->data['IzIntake']['intake_datum']['year']."-".$this->data['IzIntake']['intake_datum']['month']."-".$this->data['IzIntake']['intake_datum']['day'];
        } else {
            $intake_datum =$this->data['IzIntake']['intake_datum'];
        }

    }

    echo "Intake datum: ".$intake_datum."<br/><br/>";
    echo "Coodinator : ".$viewmedewerkers[$this->data['IzIntake']['medewerker_id']]."<br/><br/>";

    $antwoord = ! empty($this->data['IzIntake']['gezin_met_kinderen']) ? 'Ja' : 'Nee';

    echo "Gezin met kinderen : ".$antwoord."<br/><br/>";
?>
    <div >
<?php
    echo $this->element('zrm_view', array(
            'zrmReport' => $this->data,
            'model' => 'IzIntake',
            'zrm_data' => $zrm_data,

    ));

?>
</div>

    <h4>Ondersteuning</h4><br/>

    <?php

       echo 'Zou je het leuk vinden om iedere week met iemand samen iets te ondernemen? : ';

       echo ! empty($this->data['IzIntake']['ondernemen']) ? "Ja" : "Nee";

       echo "<br/><br/>";

       echo 'Zou je het leuk vinden om overdag iets te doen te hebben? : ';

       echo ! empty($this->data['IzIntake']['overdag']) ? "Ja" : "Nee";

       echo "<br/><br/>";

       echo 'Zou je een plek in de buurt willen hebben<br/> waar je iedere dag koffie kan drinken en mensen kan ontmoeten? : ';

       echo ! empty($this->data['IzIntake']['ontmoeten']) ? "Ja" : "Nee";

       echo "<br/><br/>";

       echo 'Heeft u hulp nodig met regelzaken? : ';

       echo ! empty($this->data['IzIntake']['regelzaken']) ? "Ja" : "Nee";

       echo "<br/><br/>";
    ?>

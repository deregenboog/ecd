<h1>Aanstaande her-intakes per locatie</h1>

<p>Locaties:</p>
<ul>
    <?php foreach (array_keys($locaties) as $i => $locatie): ?>
        <li>
            <a href="#locatie_<?= $i ?>">
                <?= $locatie?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<?php foreach (array_keys($locaties) as $i => $locatie): ?>
    <p>&nbsp;</p>
    <h2 id="locatie_<?= $i ?>"><?= $locatie?></h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Naam</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locaties[$locatie] as $klant): ?>
                <tr>
                    <td><?= $this->html->link($klant->getId(), ['controller' => 'klanten', 'action' => 'view', $klant->getId()]) ?></td>
                    <td><?= $klant->getNaam() ?></td>
                    <td><?= $this->html->link('Intake toevoegen', ['controller' => 'intakes', 'action' => 'add', $klant->getId()]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endforeach; ?>

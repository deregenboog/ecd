<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180913143953 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE VIEW registraties_recent AS
            SELECT klant_id, locatie_id, MAX(buiten) AS max_buiten
                FROM registraties
                WHERE closed = 1
                AND binnen > (NOW() + INTERVAL -15 day)
                GROUP BY klant_id, locatie_id
        ');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}

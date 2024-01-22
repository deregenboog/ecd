<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190107144904 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_hulpvraag_succesindicator (hulpvraag_id INT NOT NULL, succesindicator_id INT NOT NULL, INDEX IDX_BDDCA8FA8450D8C (hulpvraag_id), INDEX IDX_BDDCA8FA7B2005C (succesindicator_id), PRIMARY KEY(hulpvraag_id, succesindicator_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicator ADD CONSTRAINT FK_BDDCA8FA8450D8C FOREIGN KEY (hulpvraag_id) REFERENCES iz_koppelingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicator ADD CONSTRAINT FK_BDDCA8FA7B2005C FOREIGN KEY (succesindicator_id) REFERENCES iz_succesindicatoren (id) ON DELETE CASCADE');

        $this->addSql('INSERT INTO iz_hulpvraag_succesindicator SELECT * FROM iz_hulpvraag_succesindicatorfinancieel');
        $this->addSql('INSERT INTO iz_hulpvraag_succesindicator SELECT * FROM iz_hulpvraag_succesindicatorparticipatie');
        $this->addSql('INSERT INTO iz_hulpvraag_succesindicator SELECT * FROM iz_hulpvraag_succesindicatorpersoonlijk');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_178943F7FC4DB938 ON iz_succesindicatoren (naam)');
//         $this->addSql('DROP INDEX UNIQ_178943F7FC4DB9384AD26064 ON iz_succesindicatoren');
//         $this->addSql('ALTER TABLE iz_succesindicatoren DROP discr');

//         $this->addSql('DROP TABLE iz_hulpvraag_succesindicatorfinancieel');
//         $this->addSql('DROP TABLE iz_hulpvraag_succesindicatorparticipatie');
//         $this->addSql('DROP TABLE iz_hulpvraag_succesindicatorpersoonlijk');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}

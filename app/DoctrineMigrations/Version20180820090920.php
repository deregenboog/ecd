<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180820090920 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_hulpvraag_succesindicatorpersoonlijk (hulpvraag_id INT NOT NULL, succesindicatorpersoonlijk_id INT NOT NULL, INDEX IDX_BC9D7F44A8450D8C (hulpvraag_id), INDEX IDX_BC9D7F44F9892974 (succesindicatorpersoonlijk_id), PRIMARY KEY(hulpvraag_id, succesindicatorpersoonlijk_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_hulpvraag_succesindicatorfinancieel (hulpvraag_id INT NOT NULL, succesindicatorfinancieel_id INT NOT NULL, INDEX IDX_3A3B526FA8450D8C (hulpvraag_id), INDEX IDX_3A3B526F3FEB2492 (succesindicatorfinancieel_id), PRIMARY KEY(hulpvraag_id, succesindicatorfinancieel_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_hulpvraag_succesindicatorparticipatie (hulpvraag_id INT NOT NULL, succesindicatorparticipatie_id INT NOT NULL, INDEX IDX_128F9138A8450D8C (hulpvraag_id), INDEX IDX_128F913865A9F272 (succesindicatorparticipatie_id), PRIMARY KEY(hulpvraag_id, succesindicatorparticipatie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_succesindicatoren (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_178943F7FC4DB9384AD26064 (naam, discr), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicatorpersoonlijk ADD CONSTRAINT FK_BC9D7F44A8450D8C FOREIGN KEY (hulpvraag_id) REFERENCES iz_koppelingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicatorpersoonlijk ADD CONSTRAINT FK_BC9D7F44F9892974 FOREIGN KEY (succesindicatorpersoonlijk_id) REFERENCES iz_succesindicatoren (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicatorfinancieel ADD CONSTRAINT FK_3A3B526FA8450D8C FOREIGN KEY (hulpvraag_id) REFERENCES iz_koppelingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicatorfinancieel ADD CONSTRAINT FK_3A3B526F3FEB2492 FOREIGN KEY (succesindicatorfinancieel_id) REFERENCES iz_succesindicatoren (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicatorparticipatie ADD CONSTRAINT FK_128F9138A8450D8C FOREIGN KEY (hulpvraag_id) REFERENCES iz_koppelingen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_hulpvraag_succesindicatorparticipatie ADD CONSTRAINT FK_128F913865A9F272 FOREIGN KEY (succesindicatorparticipatie_id) REFERENCES iz_succesindicatoren (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
     public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}

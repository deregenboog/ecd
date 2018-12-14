<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171207154511 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_matching_vrijwilligers (id INT AUTO_INCREMENT NOT NULL, iz_vrijwilliger_id INT DEFAULT NULL, info VARCHAR(255) NOT NULL, voorkeur_voor_nederlands TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1CA45FA7C99F99BF (iz_vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_matchingvrijwilliger_doelgroep (matchingvrijwilliger_id INT NOT NULL, doelgroep_id INT NOT NULL, INDEX IDX_AA83F9B42B829AB5 (matchingvrijwilliger_id), INDEX IDX_AA83F9B4E5A2DFCE (doelgroep_id), PRIMARY KEY(matchingvrijwilliger_id, doelgroep_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_matchingvrijwilliger_hulpvraagsoort (matchingvrijwilliger_id INT NOT NULL, hulpvraagsoort_id INT NOT NULL, INDEX IDX_11DF7DC02B829AB5 (matchingvrijwilliger_id), INDEX IDX_11DF7DC0950213F (hulpvraagsoort_id), PRIMARY KEY(matchingvrijwilliger_id, hulpvraagsoort_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_matching_klanten (id INT AUTO_INCREMENT NOT NULL, iz_klant_id INT DEFAULT NULL, hulpvraagsoort_id INT DEFAULT NULL, info VARCHAR(255) NOT NULL, spreekt_nederlands TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A5321A4355FE26E1 (iz_klant_id), INDEX IDX_A5321A43950213F (hulpvraagsoort_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_matchingklant_doelgroep (matchingklant_id INT NOT NULL, doelgroep_id INT NOT NULL, INDEX IDX_9A957F94CC045EED (matchingklant_id), INDEX IDX_9A957F94E5A2DFCE (doelgroep_id), PRIMARY KEY(matchingklant_id, doelgroep_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_hulpvraagsoorten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX unique_naam_idx (naam), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_doelgroepen (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX unique_naam_idx (naam), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_matching_vrijwilligers ADD CONSTRAINT FK_1CA45FA7C99F99BF FOREIGN KEY (iz_vrijwilliger_id) REFERENCES iz_deelnemers (id)');
        $this->addSql('ALTER TABLE iz_matchingvrijwilliger_doelgroep ADD CONSTRAINT FK_AA83F9B42B829AB5 FOREIGN KEY (matchingvrijwilliger_id) REFERENCES iz_matching_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_matchingvrijwilliger_doelgroep ADD CONSTRAINT FK_AA83F9B4E5A2DFCE FOREIGN KEY (doelgroep_id) REFERENCES iz_doelgroepen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_matchingvrijwilliger_hulpvraagsoort ADD CONSTRAINT FK_11DF7DC02B829AB5 FOREIGN KEY (matchingvrijwilliger_id) REFERENCES iz_matching_vrijwilligers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_matchingvrijwilliger_hulpvraagsoort ADD CONSTRAINT FK_11DF7DC0950213F FOREIGN KEY (hulpvraagsoort_id) REFERENCES iz_hulpvraagsoorten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_matching_klanten ADD CONSTRAINT FK_A5321A4355FE26E1 FOREIGN KEY (iz_klant_id) REFERENCES iz_deelnemers (id)');
        $this->addSql('ALTER TABLE iz_matching_klanten ADD CONSTRAINT FK_A5321A43950213F FOREIGN KEY (hulpvraagsoort_id) REFERENCES iz_hulpvraagsoorten (id)');
        $this->addSql('ALTER TABLE iz_matchingklant_doelgroep ADD CONSTRAINT FK_9A957F94CC045EED FOREIGN KEY (matchingklant_id) REFERENCES iz_matching_klanten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_matchingklant_doelgroep ADD CONSTRAINT FK_9A957F94E5A2DFCE FOREIGN KEY (doelgroep_id) REFERENCES iz_doelgroepen (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}

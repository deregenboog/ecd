<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200714085755 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mw_afsluiting_resultaat (afsluiting_id INT NOT NULL, resultaat_id INT NOT NULL, INDEX IDX_EBA6C1A2ECDAD1A9 (afsluiting_id), INDEX IDX_EBA6C1A2B0A9B358 (resultaat_id), PRIMARY KEY(afsluiting_id, resultaat_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mw_resultaten (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('INSERT INTO mw_resultaten (naam,active) VALUES ("Overdracht naar buurteam",1),
                            ("Postadres geregeld",1),
                            ("Inkomen op orde",1),
                            ("Administratie op orde",1),
                            ("Toeleiding schuldhulpverlening",1),
                            ("Werk gevonden/behouden",1),
                            ("Herstel woningnet",1),
                            ("Huisvesting eigen netwerk binnen Amsterdam",1),
                            ("Huisvesting eigen netwerk buiten Amsterdam",1),
                            ("huisvesting zelfstandig binnen Amsterdam",1),
                            ("Huisvesting zelfstandig buiten Amsterdam",1),
                            ("Huisvesting via Onder de pannen",1),
                            ("Plaatsing Passantenpenion",1),
                            ("Plaatsing MO/BW",1),
                            ("Verwijzing GGD",1),
                            ("Verwijzing GGZ",1),
                            ("Organiseren somatische zorg",1),
                            ("Jippie, het gaat weer helemaal top!",1)
');

        $this->addSql('ALTER TABLE mw_afsluiting_resultaat ADD CONSTRAINT FK_EBA6C1A2ECDAD1A9 FOREIGN KEY (afsluiting_id) REFERENCES mw_dossier_statussen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mw_afsluiting_resultaat ADD CONSTRAINT FK_EBA6C1A2B0A9B358 FOREIGN KEY (resultaat_id) REFERENCES mw_resultaten (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mw_afsluiting_resultaat DROP FOREIGN KEY FK_EBA6C1A2B0A9B358');

        $this->addSql('DROP TABLE mw_afsluiting_resultaat');
        $this->addSql('DROP TABLE mw_resultaten');
    }
}

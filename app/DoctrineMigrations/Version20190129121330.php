<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129121330 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX uq_klant ON verslaginfos');
        $this->addSql('ALTER TABLE verslaginfos
            CHANGE advocaat advocaat VARCHAR(255) DEFAULT NULL,
            CHANGE contact contact LONGTEXT DEFAULT NULL,
            CHANGE casemanager_email casemanager_email VARCHAR(255) DEFAULT NULL,
            CHANGE casemanager_telefoonnummer casemanager_telefoonnummer VARCHAR(255) DEFAULT NULL,
            CHANGE trajectbegeleider_email trajectbegeleider_email VARCHAR(255) DEFAULT NULL,
            CHANGE trajectbegeleider_telefoonnummer trajectbegeleider_telefoonnummer VARCHAR(255) DEFAULT NULL,
            CHANGE trajecthouder_extern_organisatie trajecthouder_extern_organisatie VARCHAR(255) DEFAULT NULL,
            CHANGE trajecthouder_extern_naam trajecthouder_extern_naam VARCHAR(255) DEFAULT NULL,
            CHANGE trajecthouder_extern_email trajecthouder_extern_email VARCHAR(255) DEFAULT NULL,
            CHANGE trajecthouder_extern_telefoonnummer trajecthouder_extern_telefoonnummer VARCHAR(255) DEFAULT NULL,
            CHANGE overige_contactpersonen_extern overige_contactpersonen_extern LONGTEXT DEFAULT NULL,
            CHANGE instantie instantie VARCHAR(255) DEFAULT NULL,
            CHANGE registratienummer registratienummer VARCHAR(255) DEFAULT NULL,
            CHANGE budgettering budgettering VARCHAR(255) DEFAULT NULL,
            CHANGE contactpersoon contactpersoon VARCHAR(255) DEFAULT NULL,
            CHANGE klantmanager_naam klantmanager_naam VARCHAR(255) DEFAULT NULL,
            CHANGE klantmanager_email klantmanager_email VARCHAR(255) DEFAULT NULL,
            CHANGE klantmanager_telefoonnummer klantmanager_telefoonnummer VARCHAR(255) DEFAULT NULL,
            CHANGE sociaal_netwerk sociaal_netwerk LONGTEXT DEFAULT NULL,
            CHANGE bankrekeningnummer bankrekeningnummer VARCHAR(255) DEFAULT NULL,
            CHANGE polisnummer_ziektekostenverzekering polisnummer_ziektekostenverzekering VARCHAR(255) DEFAULT NULL,
            CHANGE inschrijfnummer inschrijfnummer VARCHAR(255) DEFAULT NULL,
            CHANGE wachtwoord wachtwoord VARCHAR(255) DEFAULT NULL,
            CHANGE telefoonnummer telefoonnummer VARCHAR(255) DEFAULT NULL,
            CHANGE adres adres LONGTEXT DEFAULT NULL,
            CHANGE overigen overigen LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}

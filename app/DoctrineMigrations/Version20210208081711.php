<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210208081711 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE klanten ADD mwBinnenViaOptieKlant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klanten ADD CONSTRAINT FK_F538C5BC21E96F13 FOREIGN KEY (mwBinnenViaOptieKlant_id) REFERENCES mw_binnen_via (id)');


        $this->addSql('CREATE UNIQUE INDEX UNIQ_F538C5BC21E96F13 ON klanten (mwBinnenViaOptieKlant_id)');



        $this->addSql('ALTER TABLE mw_binnen_via ADD class VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE mw_binnen_via SET class = "BinnenViaOptieVW"');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


    }
}

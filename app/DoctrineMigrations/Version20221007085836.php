<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007085836 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oekraine_verslagen (id INT AUTO_INCREMENT NOT NULL, bezoeker_id INT DEFAULT NULL, locatie_id INT DEFAULT NULL, medewerker_id INT DEFAULT NULL, contactsoort_id INT DEFAULT NULL, datum DATE NOT NULL, opmerking LONGTEXT NOT NULL, medewerker VARCHAR(255) DEFAULT NULL, aanpassing_verslag INT NULL DEFAULT NULL, verslagType INT DEFAULT 1 NOT NULL, accessType INT DEFAULT 1 NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_C15C9D6F8AEEBAAE (bezoeker_id), INDEX IDX_C15C9D6F3D707F64 (medewerker_id), INDEX IDX_C15C9D6FD3899023 (contactsoort_id), INDEX idx_datum (datum), INDEX idx_locatie_id (locatie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE oekraine_verslagen ADD CONSTRAINT FK_C15C9D6F8AEEBAAE FOREIGN KEY (bezoeker_id) REFERENCES oekraine_bezoekers (id)');
        $this->addSql('ALTER TABLE oekraine_verslagen ADD CONSTRAINT FK_C15C9D6F4947630C FOREIGN KEY (locatie_id) REFERENCES oekraine_locaties (id)');
        $this->addSql('ALTER TABLE oekraine_verslagen ADD CONSTRAINT FK_C15C9D6F3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('ALTER TABLE oekraine_verslagen ADD CONSTRAINT FK_C15C9D6FD3899023 FOREIGN KEY (contactsoort_id) REFERENCES contactsoorts (id)');

    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('DROP TABLE oekraine_verslagen');

    }
}

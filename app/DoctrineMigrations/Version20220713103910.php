<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713103910 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekraine_registraties_recent DROP FOREIGN KEY FK_8C35B27E3C427B2F');
        $this->addSql('DROP INDEX IDX_8C35B27E3C427B2F ON oekraine_registraties_recent');
        $this->addSql('ALTER TABLE oekraine_registraties_recent CHANGE klant_id bezoeker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE oekraine_registraties_recent ADD CONSTRAINT FK_8C35B27E8AEEBAAE FOREIGN KEY (bezoeker_id) REFERENCES oekraine_bezoekers (id)');
        $this->addSql('CREATE INDEX IDX_8C35B27E8AEEBAAE ON oekraine_registraties_recent (bezoeker_id)');
        $this->addSql('ALTER TABLE oekraine_incidenten CHANGE politie politie TINYINT(1) NOT NULL, CHANGE ambulance ambulance TINYINT(1) NOT NULL, CHANGE crisisdienst crisisdienst TINYINT(1) NOT NULL');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oekraine_incidenten CHANGE politie politie TINYINT(1) NOT NULL, CHANGE ambulance ambulance TINYINT(1) NOT NULL, CHANGE crisisdienst crisisdienst TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE oekraine_registraties_recent DROP FOREIGN KEY FK_8C35B27E8AEEBAAE');
        $this->addSql('DROP INDEX IDX_8C35B27E8AEEBAAE ON oekraine_registraties_recent');
        $this->addSql('ALTER TABLE oekraine_registraties_recent CHANGE bezoeker_id klant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE oekraine_registraties_recent ADD CONSTRAINT FK_8C35B27E3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('CREATE INDEX IDX_8C35B27E3C427B2F ON oekraine_registraties_recent (klant_id)');

    }
}

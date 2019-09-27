<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190916093536 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_documenten ADD class VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE odp_documenten RENAME TO odp_superdocumenten');
        $this->addSql('ALTER TABLE odp_verslagen ADD class VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE odp_verslagen RENAME TO odp_superverslagen');
        $this->addSql('UPDATE odp_superdocumenten SET class = "document"');
        $this->addSql('UPDATE odp_superverslagen SET class = "verslag"');

        $this->addSql('CREATE TABLE odp_huurovereenkomst_finverslag LIKE odp_huurovereenkomst_verslag');
        $this->addSql('CREATE TABLE odp_huurovereenkomst_findocument LIKE odp_huurovereenkomst_document');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE odp_superdocumenten DROP class');
        $this->addSql('ALTER TABLE odp_superverslagen DROP class');

        $this->addSql('ALTER TABLE odp_superverslagen RENAME TO odp_verslagen');
        $this->addSql('ALTER TABLE odp_superdocumenten RENAME TO odp_documenten');

    }
}

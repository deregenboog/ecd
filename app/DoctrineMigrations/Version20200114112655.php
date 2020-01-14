<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200114112655 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clip_documenten CHANGE behandelaar_id behandelaar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clip_vragen CHANGE behandelaar_id behandelaar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clip_contactmomenten CHANGE behandelaar_id behandelaar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clip_clienten CHANGE behandelaar_id behandelaar_id INT DEFAULT NULL');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('ALTER TABLE clip_clienten CHANGE behandelaar_id behandelaar_id INT NOT NULL');
        $this->addSql('ALTER TABLE clip_contactmomenten CHANGE behandelaar_id behandelaar_id INT NOT NULL');
        $this->addSql('ALTER TABLE clip_documenten CHANGE behandelaar_id behandelaar_id INT NOT NULL');
        $this->addSql('ALTER TABLE clip_vragen CHANGE behandelaar_id behandelaar_id INT NOT NULL');

    }
}

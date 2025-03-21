<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321144755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE uhk_deelnemers_uhk_projecten (uhk_deelnemer_id INT NOT NULL, uhk_project_id INT NOT NULL, INDEX IDX_A99BE5B4F036B70 (uhk_deelnemer_id), INDEX IDX_A99BE5B4ACE63BD2 (uhk_project_id), PRIMARY KEY(uhk_deelnemer_id, uhk_project_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uhk_projecten (id INT AUTO_INCREMENT NOT NULL, color VARCHAR(10) DEFAULT NULL, naam VARCHAR(255) NOT NULL, `active` TINYINT(1) NOT NULL, created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE uhk_deelnemers_uhk_projecten ADD CONSTRAINT FK_A99BE5B4F036B70 FOREIGN KEY (uhk_deelnemer_id) REFERENCES uhk_deelnemers (id)');
        $this->addSql('ALTER TABLE uhk_deelnemers_uhk_projecten ADD CONSTRAINT FK_A99BE5B4ACE63BD2 FOREIGN KEY (uhk_project_id) REFERENCES uhk_projecten (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE uhk_deelnemers_uhk_projecten DROP FOREIGN KEY FK_A99BE5B4F036B70');
        $this->addSql('ALTER TABLE uhk_deelnemers_uhk_projecten DROP FOREIGN KEY FK_A99BE5B4ACE63BD2');
        $this->addSql('DROP TABLE uhk_deelnemers_uhk_projecten');
        $this->addSql('DROP TABLE uhk_projecten');
    }
}

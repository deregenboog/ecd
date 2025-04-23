<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325154516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '#1865 - adding trainingen functionality to IZ ';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('CREATE TABLE iz_deelnames (id INT AUTO_INCREMENT NOT NULL, iz_vrijwilliger_id INT NOT NULL, overig VARCHAR(255) DEFAULT NULL, datum DATE DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, izTraining_id INT DEFAULT NULL, INDEX IDX_91912B0D1B89594B (izTraining_id), INDEX IDX_91912B0DC99F99BF (iz_vrijwilliger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE iz_training (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, `active` TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_deelnames ADD CONSTRAINT FK_91912B0D1B89594B FOREIGN KEY (izTraining_id) REFERENCES iz_training (id)');
        $this->addSql('ALTER TABLE iz_deelnames ADD CONSTRAINT FK_91912B0DC99F99BF FOREIGN KEY (iz_vrijwilliger_id) REFERENCES iz_deelnemers (id)');
    }

    public function down(Schema $schema): void
    {
        
        $this->addSql('ALTER TABLE iz_deelnames DROP FOREIGN KEY FK_91912B0D1B89594B');
        $this->addSql('ALTER TABLE iz_deelnames DROP FOREIGN KEY FK_91912B0DC99F99BF');
        $this->addSql('DROP TABLE iz_deelnames');
        $this->addSql('DROP TABLE iz_training');
        
    }
}

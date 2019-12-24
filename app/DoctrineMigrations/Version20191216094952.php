<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216094952 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        $this->addSql('CREATE TABLE uhk_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_3DDA892B3D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE uhk_documenten ADD CONSTRAINT FK_3DDA892B3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE TABLE uhk_deelnemer_document (deelnemer_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_40FD84945DFA57A1 (deelnemer_id), UNIQUE INDEX UNIQ_40FD8494C33F7837 (document_id), PRIMARY KEY(deelnemer_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE uhk_deelnemer_document ADD CONSTRAINT FK_40FD84945DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES uhk_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uhk_deelnemer_document ADD CONSTRAINT FK_40FD8494C33F7837 FOREIGN KEY (document_id) REFERENCES uhk_documenten (id) ON DELETE CASCADE');



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE uhk_documenten');
        $this->addSql('DROP TABLE uhk_deelnemer_document');

    }
}

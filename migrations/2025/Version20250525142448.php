<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519142448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inloop_access_fields (
            id INT AUTO_INCREMENT NOT NULL,
            klant_id INT DEFAULT NULL,
            verblijfstatus_id INT DEFAULT NULL, 
            intakelocatie_id INT DEFAULT NULL, 
            gebruikersruimte_id INT DEFAULT NULL, 
            intake_datum DATE DEFAULT NULL, 
            toegang_inloophuis TINYINT(1) DEFAULT NULL, 
            overigen_toegang_van DATE DEFAULT NULL, 
            INDEX IDX_2ABBA00D48D0634A (verblijfstatus_id), 
            INDEX IDX_2ABBA00D55E45319 (intakelocatie_id), 
            INDEX IDX_2ABBA00DFB34A317 (gebruikersruimte_id), 
            INDEX IDX_2ABBA00D3C427B2F (klant_id),
            PRIMARY KEY(id)) DEFAULT 
            CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB')
        ;
        $this->addSql('CREATE TABLE inloop_access_fields_locaties (accessfields_id INT NOT NULL, locatie_id INT NOT NULL, INDEX IDX_36F075296F81BDFF (accessfields_id), INDEX IDX_36F075294947630C (locatie_id), PRIMARY KEY(accessfields_id, locatie_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_2ABBA00D3C427B2F FOREIGN KEY (klant_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_2ABBA00D48D0634A FOREIGN KEY (verblijfstatus_id) REFERENCES verblijfstatussen (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_2ABBA00D55E45319 FOREIGN KEY (intakelocatie_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE inloop_access_fields ADD CONSTRAINT FK_2ABBA00DFB34A317 FOREIGN KEY (gebruikersruimte_id) REFERENCES locaties (id)');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties ADD CONSTRAINT FK_36F075296F81BDFF FOREIGN KEY (accessfields_id) REFERENCES inloop_access_fields (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties ADD CONSTRAINT FK_36F075294947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');

        // Update intakes table to add accessFields_id column
        $this->addSql('ALTER TABLE intakes ADD accessFields_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AEED703F5C FOREIGN KEY (accessFields_id) REFERENCES inloop_access_fields (id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AEED703F5C ON intakes (accessFields_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inloop_access_fields DROP FOREIGN KEY FK_2ABBA00D48D0634A');
        $this->addSql('ALTER TABLE inloop_access_fields DROP FOREIGN KEY FK_2ABBA00D55E45319');
        $this->addSql('ALTER TABLE inloop_access_fields DROP FOREIGN KEY FK_2ABBA00DFB34A317');
        $this->addSql('ALTER TABLE inloop_access_fields DROP FOREIGN KEY FK_2ABBA00D3C427B2F');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties DROP FOREIGN KEY FK_36F075296F81BDFF');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties DROP FOREIGN KEY FK_36F075294947630C');
        $this->addSql('DROP TABLE inloop_access_fields');
        $this->addSql('DROP TABLE inloop_access_fields_locaties');
        $this->addSql('ALTER TABLE inloop_access_fields_locaties ADD CONSTRAINT FK_36F075296F81BDFF FOREIGN KEY (accessfields_id) REFERENCES inloop_access_fields (intake_id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210922145733 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_20283999C0B11400');
        $this->addSql('CREATE TABLE tw_inkomen (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, `order` INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_pandeigenaartype (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tw_pandeigenaar (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) DEFAULT NULL, `pandeigenaartype_id` INT NOT NULL, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');

        $this->addSql('INSERT INTO tw_pandeigenaartype (`id`,`naam`,`active`, created, modified) VALUES (1,"Woco",1, NOW(),NOW())');
        $this->addSql('INSERT INTO tw_pandeigenaar (`naam`, `pandeigenaartype_id`,`active`, created, modified) SELECT `naam`,1,1, created, modified FROM tw_woningbouwcorporaties');
        $this->addSql('DROP TABLE tw_woningbouwcorporaties');

        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256D00EBD32');
        $this->addSql('DROP INDEX IDX_E4317256D00EBD32 ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E4317256C0B11400 ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers ADD inkomen_id INT DEFAULT NULL,ADD huurtoeslag TINYINT(1) NULL DEFAULT NULL, CHANGE woningbouwcorporatie_id pandeigenaar_id INT DEFAULT NULL, ADD kwijtschelding TINYINT(1) DEFAULT NULL, ADD verhuurprijs INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_deelnemers CHANGE woningbouwcorporatie_toelichting pandeigenaar_toelichting VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256DE7E5B0 FOREIGN KEY (inkomen_id) REFERENCES tw_inkomen (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E43172565721A070 FOREIGN KEY (pandeigenaar_id) REFERENCES tw_pandeigenaar (id)');
        $this->addSql('ALTER TABLE tw_pandeigenaar ADD CONSTRAINT FK_E43172565721A654 FOREIGN KEY (pandeigenaartype_id) REFERENCES tw_pandeigenaartype (id)');
        $this->addSql('CREATE INDEX IDX_E4317256DE7E5B0 ON tw_deelnemers (inkomen_id)');
        $this->addSql('CREATE INDEX IDX_E43172565721A070 ON tw_deelnemers (pandeigenaar_id)');
        $this->addSql('CREATE INDEX IDX_E43172565721A654 ON tw_pandeigenaar (pandeigenaartype_id)');

        $this->addSql('INSERT INTO tw_pandeigenaartype (`naam`,`active`, created, modified) VALUES ("Eigendom verhuurder",1, NOW(),NOW()),("Hotel",1, NOW(),NOW()),("Vastgoedbeheerder",1, NOW(),NOW()),("Leegstandbeheerder",1, NOW(),NOW())');

        $this->addSql('INSERT INTO tw_inkomen (`label`,`order`) VALUES 
                                    ("Werk",10),
                                    ("Geen inkomen",20),
                                    ("Uitkering: bijstand/WPI (volledig)",30),
                                    ("Uitkering: bijstand/WPI (daklozenuitkering)",40),
                                    ("Uitkering: WW",50),
                                    ("Uitkering: Ziektewet/WIA/WAO",60),
                                    ("Uitkering: AOW",70),
                                    ("Uitkering: Overig",80),
                                    ("Studiefinanciering",90),
                                    ("Overig",100)                                    
                            ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E4317256DE7E5B0');
        $this->addSql('ALTER TABLE tw_deelnemers DROP FOREIGN KEY FK_E43172565721A070');
        $this->addSql('CREATE TABLE tw_woningbouwcorporaties (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, active TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE tw_inkomen');
        $this->addSql('DROP TABLE tw_pandeigenaartype');
        $this->addSql('DROP TABLE tw_pandeigenaar');


        $this->addSql('DROP INDEX IDX_E4317256DE7E5B0 ON tw_deelnemers');
        $this->addSql('DROP INDEX IDX_E43172565721A070 ON tw_deelnemers');
        $this->addSql('ALTER TABLE tw_deelnemers ADD woningbouwcorporatie_id INT DEFAULT NULL, ADD inschrijvingWoningnet_id INT DEFAULT NULL, DROP inkomen_id, DROP pandeigenaar_id, DROP kwijtschelding, DROP verhuurprijs, CHANGE project_id project_id INT DEFAULT NULL, CHANGE pandeigenaar_toelichting woningbouwcorporatie_toelichting VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_20283999C0B11400 FOREIGN KEY (woningbouwcorporatie_id) REFERENCES tw_woningbouwcorporaties (id)');
        $this->addSql('ALTER TABLE tw_deelnemers ADD CONSTRAINT FK_E4317256D00EBD32 FOREIGN KEY (inschrijvingWoningnet_id) REFERENCES tw_inschrijvingwoningnet (id)');
        $this->addSql('CREATE INDEX IDX_E4317256D00EBD32 ON tw_deelnemers (inschrijvingWoningnet_id)');
        $this->addSql('CREATE INDEX IDX_E4317256C0B11400 ON tw_deelnemers (woningbouwcorporatie_id)');


    }
}

<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310110734 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intakes DROP FOREIGN KEY FK_AB70F5AEDC542336');
        $this->addSql('ALTER TABLE intakes DROP FOREIGN KEY FK_AB70F5AE6DF0864');
        $this->addSql('ALTER TABLE intakes DROP FOREIGN KEY FK_AB70F5AE694ADD79');
        $this->addSql('DROP INDEX IDX_AB70F5AE694ADD79 ON intakes');
        $this->addSql('DROP INDEX IDX_AB70F5AE6DF0864 ON intakes');
        $this->addSql('DROP INDEX IDX_AB70F5AEDC542336 ON intakes');
        $this->addSql('ALTER TABLE intakes DROP primaireproblematieksfrequentie_id, DROP primaireproblematieksperiode_id, DROP primaireProblematiek_id, CHANGE opmerking_andere_instanties opmerking_andere_instanties TEXT DEFAULT NULL, CHANGE medische_achtergrond medische_achtergrond TEXT DEFAULT NULL, CHANGE verwachting_dienstaanbod verwachting_dienstaanbod TEXT DEFAULT NULL, CHANGE toekomstplannen toekomstplannen TEXT DEFAULT NULL, CHANGE indruk indruk TEXT DEFAULT NULL, CHANGE informele_zorg informele_zorg TINYINT(1) DEFAULT \'0\', CHANGE dagbesteding dagbesteding TINYINT(1) DEFAULT \'0\', CHANGE inloophuis inloophuis TINYINT(1) DEFAULT \'0\', CHANGE hulpverlening hulpverlening TINYINT(1) DEFAULT \'0\', CHANGE doelgroep doelgroep TINYINT(1) DEFAULT NULL, CHANGE created created DATETIME DEFAULT NULL, CHANGE modified modified DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intakes ADD primaireproblematieksfrequentie_id INT DEFAULT NULL, ADD primaireproblematieksperiode_id INT DEFAULT NULL, ADD primaireProblematiek_id INT DEFAULT NULL, CHANGE opmerking_andere_instanties opmerking_andere_instanties VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, CHANGE medische_achtergrond medische_achtergrond VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, CHANGE verwachting_dienstaanbod verwachting_dienstaanbod VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, CHANGE toekomstplannen toekomstplannen VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, CHANGE indruk indruk VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, CHANGE informele_zorg informele_zorg VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_general_ci`, CHANGE dagbesteding dagbesteding VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE inloophuis inloophuis VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE hulpverlening hulpverlening VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE doelgroep doelgroep VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_general_ci`, CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE modified modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AEDC542336 FOREIGN KEY (primaireproblematieksperiode_id) REFERENCES verslavingsperiodes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE6DF0864 FOREIGN KEY (primaireProblematiek_id) REFERENCES verslavingen (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE intakes ADD CONSTRAINT FK_AB70F5AE694ADD79 FOREIGN KEY (primaireproblematieksfrequentie_id) REFERENCES verslavingsfrequenties (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AB70F5AE694ADD79 ON intakes (primaireproblematieksfrequentie_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AE6DF0864 ON intakes (primaireProblematiek_id)');
        $this->addSql('CREATE INDEX IDX_AB70F5AEDC542336 ON intakes (primaireproblematieksperiode_id)');
    }
}

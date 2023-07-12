<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712103851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Move trajecten documenten and verslagen to deelnemer. Let op: dit is deels niet terug te draaien vanwege het verdwijnen van de koppeling met het traject.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("START TRANSACTION");
        $this->addSql("UPDATE dagbesteding_verslagen AS v INNER JOIN dagbesteding_deelnemer_verslag AS dv ON v.id = dv.verslag_id SET v.deelnemer_id = dv.deelnemer_id");
        $this->addSql("UPDATE dagbesteding_documenten AS doc INNER JOIN dagbesteding_deelnemer_document AS ddoc ON doc.id = ddoc.document_id SET doc.deelnemer_id = ddoc.deelnemer_id");
        $this->addSql("UPDATE dagbesteding_verslagen AS v INNER JOIN dagbesteding_traject_verslag AS tv ON v.id = tv.verslag_id INNER JOIN dagbesteding_trajecten AS t ON t.id = tv.traject_id SET v.deelnemer_id = t.deelnemer_id");
        $this->addSql("UPDATE dagbesteding_documenten AS doc INNER JOIN dagbesteding_traject_document AS tdoc ON doc.id = tdoc.document_id INNER JOIN dagbesteding_trajecten AS t ON t.id = tdoc.traject_id SET doc.deelnemer_id = t.deelnemer_id");


        $this->addSql('DROP TABLE dagbesteding_deelnemer_document');
        $this->addSql('DROP TABLE dagbesteding_deelnemer_verslag');
        $this->addSql('DROP TABLE dagbesteding_traject_document');
        $this->addSql('DROP TABLE dagbesteding_traject_verslag');

        $this->addSql("COMMIT");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("START TRANSACTION");
        $this->addSql('CREATE TABLE dagbesteding_deelnemer_document (deelnemer_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_89539E645DFA57A1 (deelnemer_id), INDEX IDX_89539E64C33F7837 (document_id), PRIMARY KEY(deelnemer_id, document_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dagbesteding_deelnemer_verslag (deelnemer_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_BA9CAC335DFA57A1 (deelnemer_id), INDEX IDX_BA9CAC33D949475D (verslag_id), PRIMARY KEY(deelnemer_id, verslag_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dagbesteding_traject_document (traject_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_5408B1ADA0CADD4 (traject_id), INDEX IDX_5408B1ADC33F7837 (document_id), PRIMARY KEY(traject_id, document_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dagbesteding_traject_verslag (traject_id INT NOT NULL, verslag_id INT NOT NULL, INDEX IDX_ECCFAC5CA0CADD4 (traject_id), INDEX IDX_ECCFAC5CD949475D (verslag_id), PRIMARY KEY(traject_id, verslag_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = \'\' ');


        $this->addSql('ALTER TABLE dagbesteding_deelnemer_document ADD CONSTRAINT FK_89539E645DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_document ADD CONSTRAINT FK_89539E64C33F7837 FOREIGN KEY (document_id) REFERENCES dagbesteding_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_verslag ADD CONSTRAINT FK_BA9CAC335DFA57A1 FOREIGN KEY (deelnemer_id) REFERENCES dagbesteding_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_deelnemer_verslag ADD CONSTRAINT FK_BA9CAC33D949475D FOREIGN KEY (verslag_id) REFERENCES dagbesteding_verslagen (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_document ADD CONSTRAINT FK_5408B1ADA0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_document ADD CONSTRAINT FK_5408B1ADC33F7837 FOREIGN KEY (document_id) REFERENCES dagbesteding_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_verslag ADD CONSTRAINT FK_ECCFAC5CA0CADD4 FOREIGN KEY (traject_id) REFERENCES dagbesteding_trajecten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dagbesteding_traject_verslag ADD CONSTRAINT FK_ECCFAC5CD949475D FOREIGN KEY (verslag_id) REFERENCES dagbesteding_verslagen (id) ON DELETE CASCADE');

        $this->addSql("UPDATE dagbesteding_deelnemer_verslag AS dv INNER JOIN dagbesteding_verslagen AS v ON dv.verslag_id = v.id SET dv.deelnemer_id = v.deelnemer_id");
        $this->addSql("UPDATE dagbesteding_deelnemer_document AS ddoc INNER JOIN dagbesteding_documenten AS doc ON ddoc.document_id = doc.id SET ddoc.deelnemer_id = doc.deelnemer_id");

        $this->addSql("UPDATE dagbesteding_traject_verslag AS tv INNER JOIN dagbesteding_verslagen AS v ON tv.verslag_id = v.id SET tv.traject_id = v.traject_id");
        $this->addSql("UPDATE dagbesteding_traject_document AS tdoc INNER JOIN dagbesteding_documenten AS doc ON tdoc.document_id = doc.id SET tdoc.traject_id = doc.traject_id");

        $this->addSql("COMMIT");


    }
}

<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115145622 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pfo_clienten CHANGE id id INT(11) NOT NULL AUTO_INCREMENT');

        $this->addSql('CREATE TABLE pfo_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_4099D0893D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pfo_documenten ADD CONSTRAINT FK_4099D0893D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE TABLE pfo_clienten_documenten (document_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_A14FB5DEC33F7837 (document_id), INDEX IDX_A14FB5DE19EB6921 (client_id), PRIMARY KEY(document_id, client_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pfo_clienten_documenten ADD CONSTRAINT FK_A14FB5DEC33F7837 FOREIGN KEY (document_id) REFERENCES pfo_documenten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pfo_clienten_documenten ADD CONSTRAINT FK_A14FB5DE19EB6921 FOREIGN KEY (client_id) REFERENCES pfo_clienten (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pfo_clienten_documenten DROP INDEX IDX_A14FB5DEC33F7837, ADD UNIQUE INDEX UNIQ_A14FB5DEC33F7837 (document_id)');
        $this->addSql('ALTER TABLE pfo_clienten_documenten DROP FOREIGN KEY FK_A14FB5DEC33F7837');
        $this->addSql('ALTER TABLE pfo_clienten_documenten DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pfo_clienten_documenten ADD CONSTRAINT FK_A14FB5DEC33F7837 FOREIGN KEY (document_id) REFERENCES pfo_documenten (id)');
        $this->addSql('ALTER TABLE pfo_clienten_documenten ADD PRIMARY KEY (client_id, document_id)');

        $this->addSql('ALTER TABLE pfo_clienten ADD CONSTRAINT FK_3C237EDD1C729A47 FOREIGN KEY (geslacht_id) REFERENCES geslachten (id)');
        $this->addSql('ALTER TABLE pfo_clienten ADD CONSTRAINT FK_3C237EDD27025694 FOREIGN KEY (groep) REFERENCES pfo_groepen (id)');
        $this->addSql('ALTER TABLE pfo_clienten ADD CONSTRAINT FK_3C237EDDC41BE3 FOREIGN KEY (aard_relatie) REFERENCES pfo_aard_relaties (id)');
        $this->addSql('ALTER TABLE pfo_clienten ADD CONSTRAINT FK_3C237EDD3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_3C237EDD1C729A47 ON pfo_clienten (geslacht_id)');
        $this->addSql('CREATE INDEX IDX_3C237EDDC41BE3 ON pfo_clienten (aard_relatie)');
        $this->addSql('CREATE INDEX IDX_3C237EDD27025694 ON pfo_clienten (groep)');
        $this->addSql('CREATE INDEX IDX_3C237EDD3D707F64 ON pfo_clienten (medewerker_id)');
        $this->addSql('ALTER TABLE pfo_clienten DROP INDEX idx_pfo_clienten_medewerker_id');
        $this->addSql('ALTER TABLE pfo_clienten DROP INDEX idx_pfo_clienten_groep');

        $this->addSql('ALTER TABLE pfo_verslagen ADD CONSTRAINT FK_346FE20A3D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE INDEX IDX_346FE20A3D707F64 ON pfo_verslagen (medewerker_id)');

        $this->addSql('ALTER TABLE pfo_clienten_verslagen ADD CONSTRAINT FK_EC92AD411E813AB1 FOREIGN KEY (pfo_verslag_id) REFERENCES pfo_verslagen (id)');
        $this->addSql('ALTER TABLE pfo_clienten_verslagen ADD CONSTRAINT FK_EC92AD4163E315A FOREIGN KEY (pfo_client_id) REFERENCES pfo_clienten (id)');
        $this->addSql('CREATE INDEX IDX_EC92AD411E813AB1 ON pfo_clienten_verslagen (pfo_verslag_id)');
        $this->addSql('CREATE INDEX IDX_EC92AD4163E315A ON pfo_clienten_verslagen (pfo_client_id)');

        $this->addSql('ALTER TABLE pfo_clienten_supportgroups ADD CONSTRAINT FK_39F077D963E315A FOREIGN KEY (pfo_client_id) REFERENCES pfo_clienten (id)');
        $this->addSql('ALTER TABLE pfo_clienten_supportgroups ADD CONSTRAINT FK_73EA8C843926A77 FOREIGN KEY (pfo_supportgroup_client_id) REFERENCES pfo_clienten (id)');
        $this->addSql('CREATE INDEX IDX_39F077D963E315A ON pfo_clienten_supportgroups (pfo_client_id)');
        $this->addSql('CREATE INDEX IDX_39F077D93926A77 ON pfo_clienten_supportgroups (pfo_supportgroup_client_id)');
        $this->addSql('DROP INDEX pfo_cl_s_pfo_client_id ON pfo_clienten_supportgroups');
        $this->addSql('DROP INDEX pfo_cl_s_pfo_supportgroup_client_id ON pfo_clienten_supportgroups');

        $statement = $this->connection->executeQuery("SELECT * FROM attachments WHERE model = 'PfoClient' AND is_active = 1");
        foreach ($statement->fetchAll() as $row) {
            $source = sprintf('%s/../../archive/media/%s/%s', __DIR__, $row['dirname'], $row['basename']);
            $destination = sprintf('%s/../../archive/pfo/%s', __DIR__, $row['basename']);
            copy($source, $destination);
            $this->addSql(sprintf(
                "INSERT INTO pfo_documenten (medewerker_id, naam, filename, created, modified) VALUES (%d, '%s', '%s', '%s', '%s')",
                $row['user_id'],
                $row['title'],
                $row['basename'],
                $row['created'],
                $row['modified']
            ));
            $this->addSql(sprintf('INSERT INTO pfo_clienten_documenten (client_id, document_id) VALUES (%d, LAST_INSERT_ID())', $row['foreign_key']));
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}

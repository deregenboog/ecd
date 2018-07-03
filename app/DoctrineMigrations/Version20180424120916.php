<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180424120916 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE iz_documenten (id INT AUTO_INCREMENT NOT NULL, medewerker_id INT NOT NULL, naam VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, created DATETIME NOT NULL, modified DATETIME NOT NULL, INDEX IDX_C7F213503D707F64 (medewerker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_documenten ADD CONSTRAINT FK_C7F213503D707F64 FOREIGN KEY (medewerker_id) REFERENCES medewerkers (id)');
        $this->addSql('CREATE TABLE iz_deelnemers_documenten (izdeelnemer_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_66AE504F55B482C2 (izdeelnemer_id), UNIQUE INDEX UNIQ_66AE504FC33F7837 (document_id), PRIMARY KEY(izdeelnemer_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iz_deelnemers_documenten ADD CONSTRAINT FK_66AE504F55B482C2 FOREIGN KEY (izdeelnemer_id) REFERENCES iz_deelnemers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE iz_deelnemers_documenten ADD CONSTRAINT FK_66AE504FC33F7837 FOREIGN KEY (document_id) REFERENCES iz_documenten (id)');

        $statement = $this->connection->executeQuery("SELECT attachments.* FROM attachments
            INNER JOIN iz_deelnemers ON attachments.foreign_key = iz_deelnemers.id AND attachments.model = 'IzDeelnemer'
            WHERE is_active = 1");
        foreach ($statement->fetchAll() as $row) {
            $source = sprintf('%s/../../archive/media/%s/%s', __DIR__, $row['dirname'], $row['basename']);
            $destination = sprintf('%s/../../archive/iz/%s', __DIR__, $row['basename']);
            copy($source, $destination);
            $this->addSql(sprintf(
                "INSERT INTO iz_documenten (medewerker_id, naam, filename, created, modified) VALUES (%d, '%s', '%s', '%s', '%s')",
                $row['user_id'],
                addslashes($row['title']),
                $row['basename'],
                $row['created'],
                $row['modified']
                ));
            $this->addSql(sprintf('INSERT INTO iz_deelnemers_documenten (izdeelnemer_id, document_id) VALUES (%d, LAST_INSERT_ID())', $row['foreign_key']));
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

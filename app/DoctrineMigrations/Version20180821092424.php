<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180821092424 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE schorsingen CHANGE locatie_id locatie_id INT DEFAULT NULL');
        $this->addSql('UPDATE schorsingen SET locatie_id = NULL WHERE locatie_id = 0');
        $this->addSql('ALTER TABLE schorsingen ADD CONSTRAINT FK_9E658EBF4947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id)');
        $this->addSql('CREATE INDEX IDX_9E658EBF4947630C ON schorsingen (locatie_id)');
        $this->addSql('ALTER TABLE schorsing_locatie DROP FOREIGN KEY FK_52DA67664947630C');
        $this->addSql('ALTER TABLE schorsing_locatie DROP FOREIGN KEY FK_52DA6766A52077DE');
        $this->addSql('ALTER TABLE schorsing_locatie ADD CONSTRAINT FK_52DA67664947630C FOREIGN KEY (locatie_id) REFERENCES locaties (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE schorsing_locatie ADD CONSTRAINT FK_52DA6766A52077DE FOREIGN KEY (schorsing_id) REFERENCES schorsingen (id) ON DELETE CASCADE');

        $this->addSql('DELETE schorsingen_redenen
            FROM schorsingen_redenen
            LEFT JOIN schorsingen ON schorsingen.id = schorsingen_redenen.schorsing_id
            WHERE schorsingen.id IS NULL');
        $this->addSql('ALTER TABLE schorsingen_redenen ADD CONSTRAINT FK_BB99D0FFA52077DE FOREIGN KEY (schorsing_id) REFERENCES schorsingen (id)');
        $this->addSql('ALTER TABLE schorsingen_redenen ADD CONSTRAINT FK_BB99D0FFD29703A5 FOREIGN KEY (reden_id) REFERENCES redenen (id)');
        $this->addSql('CREATE INDEX IDX_BB99D0FFD29703A5 ON schorsingen_redenen (reden_id)');
        $this->addSql('CREATE INDEX IDX_BB99D0FFA52077DE ON schorsingen_redenen (schorsing_id)');
        $this->addSql('DROP INDEX idx_schorsingen_redenen_schorsing_id ON schorsingen_redenen');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}

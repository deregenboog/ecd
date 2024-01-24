<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530115222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding two indexes to iz';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE INDEX idx_id_afsluiting_deleted_model ON iz_deelnemers (id, iz_afsluiting_id, deleted, model)');
        $this->addSql('CREATE INDEX idx_deelnemer_discr_deleted_einddatum_koppeling ON iz_koppelingen (iz_deelnemer_id,discr,deleted,einddatum,iz_koppeling_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('DROP INDEX idx_id_afsluiting_deleted_model ON iz_deelnemers');
        $this->addSql('DROP INDEX idx_deelnemer_discr_deleted_einddatum_koppeling ON iz_koppelingen');
    }
}

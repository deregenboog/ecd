<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202120734 extends AbstractMigration
{
      public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dagbesteding_resultaatgebieden ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('ALTER TABLE dagbesteding_trajecten ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');
        $this->addSql('ALTER TABLE hs_betalingen ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');
        $this->addSql('ALTER TABLE hs_arbeiders ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');
        $this->addSql('ALTER TABLE hs_facturen ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('ALTER TABLE hs_registraties ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');
        $this->addSql('ALTER TABLE hs_klussen ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');
        $this->addSql('ALTER TABLE hs_declaraties ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');


        $this->addSql('UPDATE woonsituaties SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE woonsituaties SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE woonsituaties CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE verslavingsperiodes SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE verslavingsperiodes SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE verslavingsperiodes CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE locaties SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE locaties SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE locaties CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('ALTER TABLE locatie_tijden ADD created DATETIME NOT NULL DEFAULT NOW(), ADD modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE iz_koppelingen SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE iz_koppelingen SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE iz_koppelingen CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE iz_projecten SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE iz_projecten SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE iz_projecten CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_inkomen SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_inkomen SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_inkomen CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_huisdieren SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_huisdieren SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_huisdieren CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_traplopen SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_traplopen SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_traplopen CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_moscreening SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_moscreening SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_moscreening CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_softdrugs SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_softdrugs SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_softdrugs CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_regio SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_regio SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_regio CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_alcohol SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_alcohol SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_alcohol CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_roken SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_roken SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_roken CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_projecten SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_projecten SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_projecten CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_ritme SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_ritme SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_ritme CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_dagbesteding SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_dagbesteding SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_dagbesteding CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE tw_werk SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE tw_werk SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE tw_werk CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE pfo_aard_relaties SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE pfo_aard_relaties SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE pfo_aard_relaties CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE pfo_groepen SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE pfo_groepen SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE pfo_groepen  CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');

        $this->addSql('UPDATE pfo_clienten SET created = NOW() WHERE created IS NULL');
        $this->addSql('UPDATE pfo_clienten SET modified = NOW() WHERE modified IS NULL');
        $this->addSql('ALTER TABLE pfo_clienten CHANGE created created DATETIME NOT NULL DEFAULT NOW(), CHANGE modified modified DATETIME NOT NULL DEFAULT NOW()');


    }

     public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}

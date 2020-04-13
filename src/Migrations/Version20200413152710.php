<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413152710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE short_list ADD short_list_charge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE short_list ADD CONSTRAINT FK_240F368DB4B6DCC3 FOREIGN KEY (short_list_charge_id) REFERENCES charge (id)');
        $this->addSql('CREATE INDEX IDX_240F368DB4B6DCC3 ON short_list (short_list_charge_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE short_list DROP FOREIGN KEY FK_240F368DB4B6DCC3');
        $this->addSql('DROP INDEX IDX_240F368DB4B6DCC3 ON short_list');
        $this->addSql('ALTER TABLE short_list DROP short_list_charge_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413153317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE charge ADD short_list_id INT NOT NULL');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA434960FC123 FOREIGN KEY (short_list_id) REFERENCES short_list (id)');
        $this->addSql('CREATE INDEX IDX_556BA434960FC123 ON charge (short_list_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA434960FC123');
        $this->addSql('DROP INDEX IDX_556BA434960FC123 ON charge');
        $this->addSql('ALTER TABLE charge DROP short_list_id');
    }
}

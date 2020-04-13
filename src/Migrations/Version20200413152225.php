<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413152225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA434166D1F9C');
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA434960FC123');
        $this->addSql('DROP INDEX UNIQ_556BA434166D1F9C ON charge');
        $this->addSql('DROP INDEX UNIQ_556BA434960FC123 ON charge');
        $this->addSql('ALTER TABLE charge DROP short_list_id, DROP project_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE charge ADD short_list_id INT NOT NULL, ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA434166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA434960FC123 FOREIGN KEY (short_list_id) REFERENCES short_list (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_556BA434166D1F9C ON charge (project_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_556BA434960FC123 ON charge (short_list_id)');
    }
}

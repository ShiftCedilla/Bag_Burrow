<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002074535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bag ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B2268416BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_1B2268416BF700BD ON bag (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bag DROP FOREIGN KEY FK_1B2268416BF700BD');
        $this->addSql('DROP INDEX IDX_1B2268416BF700BD ON bag');
        $this->addSql('ALTER TABLE bag DROP status_id');
    }
}

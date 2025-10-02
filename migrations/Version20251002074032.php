<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002074032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bag (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, bag_condition_id INT NOT NULL, owner_id INT NOT NULL, borrower_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, img VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1B226841C54C8C93 (type_id), INDEX IDX_1B2268419DD7EFCE (bag_condition_id), INDEX IDX_1B2268417E3C61F9 (owner_id), INDEX IDX_1B22684111CE312B (borrower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B226841C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B2268419DD7EFCE FOREIGN KEY (bag_condition_id) REFERENCES `condition` (id)');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B2268417E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B22684111CE312B FOREIGN KEY (borrower_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bag DROP FOREIGN KEY FK_1B226841C54C8C93');
        $this->addSql('ALTER TABLE bag DROP FOREIGN KEY FK_1B2268419DD7EFCE');
        $this->addSql('ALTER TABLE bag DROP FOREIGN KEY FK_1B2268417E3C61F9');
        $this->addSql('ALTER TABLE bag DROP FOREIGN KEY FK_1B22684111CE312B');
        $this->addSql('DROP TABLE bag');
    }
}

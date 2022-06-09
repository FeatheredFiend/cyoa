<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609131336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adventure_merchant_inventory (id INT AUTO_INCREMENT NOT NULL, merchantinventory_id INT NOT NULL, INDEX IDX_839262C7D1E2A8B4 (merchantinventory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adventure_merchant_inventory ADD CONSTRAINT FK_839262C7D1E2A8B4 FOREIGN KEY (merchantinventory_id) REFERENCES merchant_inventory (id)');
        $this->addSql('ALTER TABLE merchant_inventory ADD quantity INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adventure_merchant_inventory');
        $this->addSql('ALTER TABLE merchant_inventory DROP quantity');
    }
}

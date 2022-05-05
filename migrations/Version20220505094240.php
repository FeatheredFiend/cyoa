<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505094240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enemy (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, name VARCHAR(255) NOT NULL, skill INT NOT NULL, stamina INT NOT NULL, INDEX IDX_FB9F5AA98B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT FK_FB9F5AA98B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE enemy');
    }
}

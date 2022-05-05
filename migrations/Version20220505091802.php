<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505091802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adventure_paragraph (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gamebook_paragraph (id INT AUTO_INCREMENT NOT NULL, gamebook_id INT NOT NULL, paragraph_id INT NOT NULL, INDEX IDX_6AC9526192A4F9CA (gamebook_id), INDEX IDX_6AC952618B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gamebook_paragraph ADD CONSTRAINT FK_6AC9526192A4F9CA FOREIGN KEY (gamebook_id) REFERENCES gamebook (id)');
        $this->addSql('ALTER TABLE gamebook_paragraph ADD CONSTRAINT FK_6AC952618B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adventure_paragraph');
        $this->addSql('DROP TABLE gamebook_paragraph');
    }
}

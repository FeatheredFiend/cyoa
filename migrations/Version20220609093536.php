<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609093536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gamebook_permission (id INT AUTO_INCREMENT NOT NULL, gamebook_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7957D9DA92A4F9CA (gamebook_id), INDEX IDX_7957D9DAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gamebook_permission ADD CONSTRAINT FK_7957D9DA92A4F9CA FOREIGN KEY (gamebook_id) REFERENCES gamebook (id)');
        $this->addSql('ALTER TABLE gamebook_permission ADD CONSTRAINT FK_7957D9DAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE gamebook CHANGE license license VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gamebook_permission');
        $this->addSql('ALTER TABLE gamebook CHANGE license license VARCHAR(255) NOT NULL');
    }
}

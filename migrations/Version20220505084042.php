<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505084042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adventure (id INT AUTO_INCREMENT NOT NULL, gamebook_id INT NOT NULL, hero_id INT NOT NULL, timeelapsed INT NOT NULL, INDEX IDX_9E858E0F92A4F9CA (gamebook_id), UNIQUE INDEX UNIQ_9E858E0F45B0BCD (hero_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, skill INT NOT NULL, stamina INT NOT NULL, luck INT NOT NULL, honour INT NOT NULL, startingskill INT NOT NULL, startingstamina INT NOT NULL, startingluck INT NOT NULL, startingprovision INT NOT NULL, provision INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT FK_9E858E0F92A4F9CA FOREIGN KEY (gamebook_id) REFERENCES gamebook (id)');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT FK_9E858E0F45B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adventure DROP FOREIGN KEY FK_9E858E0F45B0BCD');
        $this->addSql('DROP TABLE adventure');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE hero');
    }
}

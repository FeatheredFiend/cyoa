<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505094829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE battle (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, enemy_id INT NOT NULL, round INT NOT NULL, playerstamina INT NOT NULL, playerskill INT NOT NULL, enemystamina INT NOT NULL, enemyskill INT NOT NULL, INDEX IDX_139917348B50597F (paragraph_id), INDEX IDX_13991734900C982F (enemy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE battle_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_139917348B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734900C982F FOREIGN KEY (enemy_id) REFERENCES enemy (id)');
        $this->addSql('ALTER TABLE enemy ADD battlecategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE enemy ADD CONSTRAINT FK_FB9F5AA9DB85FD40 FOREIGN KEY (battlecategory_id) REFERENCES battle_category (id)');
        $this->addSql('CREATE INDEX IDX_FB9F5AA9DB85FD40 ON enemy (battlecategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enemy DROP FOREIGN KEY FK_FB9F5AA9DB85FD40');
        $this->addSql('DROP TABLE battle');
        $this->addSql('DROP TABLE battle_category');
        $this->addSql('DROP INDEX IDX_FB9F5AA9DB85FD40 ON enemy');
        $this->addSql('ALTER TABLE enemy DROP battlecategory_id');
    }
}

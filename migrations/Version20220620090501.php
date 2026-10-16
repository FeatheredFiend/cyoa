<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620090501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paragraph_action_spell (id INT AUTO_INCREMENT NOT NULL, paragraphaction_id INT NOT NULL, spell_id INT NOT NULL, INDEX IDX_FCDC78FD3E55F194 (paragraphaction_id), INDEX IDX_FCDC78FD479EC90D (spell_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_direction_spell (id INT AUTO_INCREMENT NOT NULL, paragraphdirection_id INT NOT NULL, spell_id INT NOT NULL, INDEX IDX_8B0797C5BD042BD9 (paragraphdirection_id), INDEX IDX_8B0797C5479EC90D (spell_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_spell (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, spell_id INT NOT NULL, paragraphspellcategory_id INT NOT NULL, INDEX IDX_96850EE58B50597F (paragraph_id), INDEX IDX_96850EE5479EC90D (spell_id), INDEX IDX_96850EE58AAB0FBD (paragraphspellcategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_spell_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraph_action_spell ADD CONSTRAINT FK_FCDC78FD3E55F194 FOREIGN KEY (paragraphaction_id) REFERENCES paragraph_action (id)');
        $this->addSql('ALTER TABLE paragraph_action_spell ADD CONSTRAINT FK_FCDC78FD479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id)');
        $this->addSql('ALTER TABLE paragraph_direction_spell ADD CONSTRAINT FK_8B0797C5BD042BD9 FOREIGN KEY (paragraphdirection_id) REFERENCES paragraph_direction (id)');
        $this->addSql('ALTER TABLE paragraph_direction_spell ADD CONSTRAINT FK_8B0797C5479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id)');
        $this->addSql('ALTER TABLE paragraph_spell ADD CONSTRAINT FK_96850EE58B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_spell ADD CONSTRAINT FK_96850EE5479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id)');
        $this->addSql('ALTER TABLE paragraph_spell ADD CONSTRAINT FK_96850EE58AAB0FBD FOREIGN KEY (paragraphspellcategory_id) REFERENCES paragraph_spell_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_spell DROP FOREIGN KEY FK_96850EE58AAB0FBD');
        $this->addSql('DROP TABLE paragraph_action_spell');
        $this->addSql('DROP TABLE paragraph_direction_spell');
        $this->addSql('DROP TABLE paragraph_spell');
        $this->addSql('DROP TABLE paragraph_spell_category');
    }
}

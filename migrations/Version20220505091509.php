<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505091509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hero_attribute (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, text VARCHAR(5000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_action (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, paragraphactiontype_id INT NOT NULL, paragraphactionoperator_id INT NOT NULL, paragraphactionattribute_id INT NOT NULL, text VARCHAR(5000) NOT NULL, actionvalue INT NOT NULL, INDEX IDX_4E3DF3B8B50597F (paragraph_id), INDEX IDX_4E3DF3B6F39411E (paragraphactiontype_id), INDEX IDX_4E3DF3BBA4A3413 (paragraphactionoperator_id), INDEX IDX_4E3DF3B7D6592DA (paragraphactionattribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_action_operator (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_action_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraph_action ADD CONSTRAINT FK_4E3DF3B8B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_action ADD CONSTRAINT FK_4E3DF3B6F39411E FOREIGN KEY (paragraphactiontype_id) REFERENCES paragraph_action_type (id)');
        $this->addSql('ALTER TABLE paragraph_action ADD CONSTRAINT FK_4E3DF3BBA4A3413 FOREIGN KEY (paragraphactionoperator_id) REFERENCES paragraph_action_operator (id)');
        $this->addSql('ALTER TABLE paragraph_action ADD CONSTRAINT FK_4E3DF3B7D6592DA FOREIGN KEY (paragraphactionattribute_id) REFERENCES hero_attribute (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_action DROP FOREIGN KEY FK_4E3DF3B7D6592DA');
        $this->addSql('ALTER TABLE paragraph_action DROP FOREIGN KEY FK_4E3DF3B8B50597F');
        $this->addSql('ALTER TABLE paragraph_action DROP FOREIGN KEY FK_4E3DF3BBA4A3413');
        $this->addSql('ALTER TABLE paragraph_action DROP FOREIGN KEY FK_4E3DF3B6F39411E');
        $this->addSql('DROP TABLE hero_attribute');
        $this->addSql('DROP TABLE paragraph');
        $this->addSql('DROP TABLE paragraph_action');
        $this->addSql('DROP TABLE paragraph_action_operator');
        $this->addSql('DROP TABLE paragraph_action_type');
    }
}

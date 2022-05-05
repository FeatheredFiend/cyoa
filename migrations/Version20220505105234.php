<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505105234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_action DROP FOREIGN KEY FK_4E3DF3B6F39411E');
        $this->addSql('CREATE TABLE paragraph_action_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE paragraph_action_type');
        $this->addSql('DROP INDEX IDX_4E3DF3B6F39411E ON paragraph_action');
        $this->addSql('ALTER TABLE paragraph_action CHANGE paragraphactiontype_id paragraphactioncategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE paragraph_action ADD CONSTRAINT FK_4E3DF3BF0493152 FOREIGN KEY (paragraphactioncategory_id) REFERENCES paragraph_action_category (id)');
        $this->addSql('CREATE INDEX IDX_4E3DF3BF0493152 ON paragraph_action (paragraphactioncategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_action DROP FOREIGN KEY FK_4E3DF3BF0493152');
        $this->addSql('CREATE TABLE paragraph_action_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE paragraph_action_category');
        $this->addSql('DROP INDEX IDX_4E3DF3BF0493152 ON paragraph_action');
        $this->addSql('ALTER TABLE paragraph_action CHANGE paragraphactioncategory_id paragraphactiontype_id INT NOT NULL');
        $this->addSql('ALTER TABLE paragraph_action ADD CONSTRAINT FK_4E3DF3B6F39411E FOREIGN KEY (paragraphactiontype_id) REFERENCES paragraph_action_type (id)');
        $this->addSql('CREATE INDEX IDX_4E3DF3B6F39411E ON paragraph_action (paragraphactiontype_id)');
    }
}

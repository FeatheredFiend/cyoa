<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220512085538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enemy ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE paragraph_action ADD CONSTRAINT FK_4E3DF3B7E76F918 FOREIGN KEY (paragraphactiontarget_id) REFERENCES paragraph_action_target (id)');
        $this->addSql('CREATE INDEX IDX_4E3DF3B7E76F918 ON paragraph_action (paragraphactiontarget_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enemy DROP image');
        $this->addSql('ALTER TABLE paragraph_action DROP FOREIGN KEY FK_4E3DF3B7E76F918');
        $this->addSql('DROP INDEX IDX_4E3DF3B7E76F918 ON paragraph_action');
    }
}

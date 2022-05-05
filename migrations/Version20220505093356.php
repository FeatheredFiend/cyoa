<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505093356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_direction DROP FOREIGN KEY FK_D2A484D1F2E423D8');
        $this->addSql('DROP INDEX IDX_D2A484D1F2E423D8 ON paragraph_direction');
        $this->addSql('ALTER TABLE paragraph_direction DROP redirectparagraph_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_direction ADD redirectparagraph_id INT NOT NULL');
        $this->addSql('ALTER TABLE paragraph_direction ADD CONSTRAINT FK_D2A484D1F2E423D8 FOREIGN KEY (redirectparagraph_id) REFERENCES paragraph (id)');
        $this->addSql('CREATE INDEX IDX_D2A484D1F2E423D8 ON paragraph_direction (redirectparagraph_id)');
    }
}

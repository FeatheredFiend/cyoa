<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506102702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD3986292A4F9CA FOREIGN KEY (gamebook_id) REFERENCES gamebook (id)');
        $this->addSql('CREATE INDEX IDX_7DD3986292A4F9CA ON paragraph (gamebook_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD3986292A4F9CA');
        $this->addSql('DROP INDEX IDX_7DD3986292A4F9CA ON paragraph');
    }
}

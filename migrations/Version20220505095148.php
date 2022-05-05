<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505095148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE battle ADD adventureparagraph_id INT NOT NULL');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734DE28837E FOREIGN KEY (adventureparagraph_id) REFERENCES adventure_paragraph (id)');
        $this->addSql('CREATE INDEX IDX_13991734DE28837E ON battle (adventureparagraph_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_13991734DE28837E');
        $this->addSql('DROP INDEX IDX_13991734DE28837E ON battle');
        $this->addSql('ALTER TABLE battle DROP adventureparagraph_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505092146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adventure_paragraph ADD adventure_id INT NOT NULL');
        $this->addSql('ALTER TABLE adventure_paragraph ADD CONSTRAINT FK_CBFF3FF055CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id)');
        $this->addSql('CREATE INDEX IDX_CBFF3FF055CF40F9 ON adventure_paragraph (adventure_id)');
        $this->addSql('ALTER TABLE equipment ADD paragraph_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D5838B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('CREATE INDEX IDX_D338D5838B50597F ON equipment (paragraph_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adventure_paragraph DROP FOREIGN KEY FK_CBFF3FF055CF40F9');
        $this->addSql('DROP INDEX IDX_CBFF3FF055CF40F9 ON adventure_paragraph');
        $this->addSql('ALTER TABLE adventure_paragraph DROP adventure_id');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D5838B50597F');
        $this->addSql('DROP INDEX IDX_D338D5838B50597F ON equipment');
        $this->addSql('ALTER TABLE equipment DROP paragraph_id');
    }
}

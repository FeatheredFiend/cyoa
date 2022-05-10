<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510102632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment_required (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, paragraph_id INT NOT NULL, INDEX IDX_D058212F517FE9FE (equipment_id), INDEX IDX_D058212F8B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment_required ADD CONSTRAINT FK_D058212F517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE equipment_required ADD CONSTRAINT FK_D058212F8B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D5838B50597F');
        $this->addSql('DROP INDEX IDX_D338D5838B50597F ON equipment');
        $this->addSql('ALTER TABLE equipment DROP paragraph_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE equipment_required');
        $this->addSql('ALTER TABLE equipment ADD paragraph_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D5838B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('CREATE INDEX IDX_D338D5838B50597F ON equipment (paragraph_id)');
    }
}

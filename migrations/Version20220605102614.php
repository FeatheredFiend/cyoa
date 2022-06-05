<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605102614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paragraph_action_equipment_required (id INT AUTO_INCREMENT NOT NULL, paragraphaction_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_6D5755A73E55F194 (paragraphaction_id), INDEX IDX_6D5755A7517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_direction_equipment_required (id INT AUTO_INCREMENT NOT NULL, paragraphdirection_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_32C70662BD042BD9 (paragraphdirection_id), INDEX IDX_32C70662517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_equipment_required (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_8D12731C8B50597F (paragraph_id), INDEX IDX_8D12731C517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraph_action_equipment_required ADD CONSTRAINT FK_6D5755A73E55F194 FOREIGN KEY (paragraphaction_id) REFERENCES paragraph_action (id)');
        $this->addSql('ALTER TABLE paragraph_action_equipment_required ADD CONSTRAINT FK_6D5755A7517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE paragraph_direction_equipment_required ADD CONSTRAINT FK_32C70662BD042BD9 FOREIGN KEY (paragraphdirection_id) REFERENCES paragraph_direction (id)');
        $this->addSql('ALTER TABLE paragraph_direction_equipment_required ADD CONSTRAINT FK_32C70662517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE paragraph_equipment_required ADD CONSTRAINT FK_8D12731C8B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_equipment_required ADD CONSTRAINT FK_8D12731C517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('DROP TABLE equipment_required');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment_required (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, paragraph_id INT NOT NULL, INDEX IDX_D058212F517FE9FE (equipment_id), INDEX IDX_D058212F8B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE equipment_required ADD CONSTRAINT FK_D058212F8B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE equipment_required ADD CONSTRAINT FK_D058212F517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('DROP TABLE paragraph_action_equipment_required');
        $this->addSql('DROP TABLE paragraph_direction_equipment_required');
        $this->addSql('DROP TABLE paragraph_equipment_required');
    }
}

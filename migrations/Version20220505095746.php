<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505095746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment_effect (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, equipmenteffectoperator_id INT NOT NULL, equipmenteffectattribute_id INT NOT NULL, equipmenteffectvalue INT NOT NULL, INDEX IDX_11EEEEB2517FE9FE (equipment_id), INDEX IDX_11EEEEB237A5FF45 (equipmenteffectoperator_id), INDEX IDX_11EEEEB2FFE089D0 (equipmenteffectattribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_effect_attribute (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_effect_operator (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment_effect ADD CONSTRAINT FK_11EEEEB2517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE equipment_effect ADD CONSTRAINT FK_11EEEEB237A5FF45 FOREIGN KEY (equipmenteffectoperator_id) REFERENCES equipment_effect_operator (id)');
        $this->addSql('ALTER TABLE equipment_effect ADD CONSTRAINT FK_11EEEEB2FFE089D0 FOREIGN KEY (equipmenteffectattribute_id) REFERENCES equipment_effect_attribute (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipment_effect DROP FOREIGN KEY FK_11EEEEB2FFE089D0');
        $this->addSql('ALTER TABLE equipment_effect DROP FOREIGN KEY FK_11EEEEB237A5FF45');
        $this->addSql('DROP TABLE equipment_effect');
        $this->addSql('DROP TABLE equipment_effect_attribute');
        $this->addSql('DROP TABLE equipment_effect_operator');
    }
}

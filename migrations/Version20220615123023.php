<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615123023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hero_spell (id INT AUTO_INCREMENT NOT NULL, hero_id INT NOT NULL, spell_id INT NOT NULL, INDEX IDX_4F00C24A45B0BCD (hero_id), INDEX IDX_4F00C24A479EC90D (spell_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magic_effect (id INT AUTO_INCREMENT NOT NULL, magic_id INT NOT NULL, magiceffectoperator_id INT NOT NULL, magiceffectattribute_id INT NOT NULL, magiceffectvalue INT NOT NULL, INDEX IDX_13D2C4D0324D4343 (magic_id), INDEX IDX_13D2C4D0B095742E (magiceffectoperator_id), INDEX IDX_13D2C4D02507018B (magiceffectattribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magic_effect_attribute (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magic_effect_operator (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magic_equipment (id INT AUTO_INCREMENT NOT NULL, magic_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_A523C19324D4343 (magic_id), INDEX IDX_A523C19517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spell (id INT AUTO_INCREMENT NOT NULL, magic_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D03FCD8D324D4343 (magic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hero_spell ADD CONSTRAINT FK_4F00C24A45B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id)');
        $this->addSql('ALTER TABLE hero_spell ADD CONSTRAINT FK_4F00C24A479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id)');
        $this->addSql('ALTER TABLE magic_effect ADD CONSTRAINT FK_13D2C4D0324D4343 FOREIGN KEY (magic_id) REFERENCES magic (id)');
        $this->addSql('ALTER TABLE magic_effect ADD CONSTRAINT FK_13D2C4D0B095742E FOREIGN KEY (magiceffectoperator_id) REFERENCES magic_effect_operator (id)');
        $this->addSql('ALTER TABLE magic_effect ADD CONSTRAINT FK_13D2C4D02507018B FOREIGN KEY (magiceffectattribute_id) REFERENCES magic_effect_attribute (id)');
        $this->addSql('ALTER TABLE magic_equipment ADD CONSTRAINT FK_A523C19324D4343 FOREIGN KEY (magic_id) REFERENCES magic (id)');
        $this->addSql('ALTER TABLE magic_equipment ADD CONSTRAINT FK_A523C19517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE spell ADD CONSTRAINT FK_D03FCD8D324D4343 FOREIGN KEY (magic_id) REFERENCES magic (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magic_effect DROP FOREIGN KEY FK_13D2C4D0324D4343');
        $this->addSql('ALTER TABLE magic_equipment DROP FOREIGN KEY FK_A523C19324D4343');
        $this->addSql('ALTER TABLE spell DROP FOREIGN KEY FK_D03FCD8D324D4343');
        $this->addSql('ALTER TABLE magic_effect DROP FOREIGN KEY FK_13D2C4D02507018B');
        $this->addSql('ALTER TABLE magic_effect DROP FOREIGN KEY FK_13D2C4D0B095742E');
        $this->addSql('ALTER TABLE hero_spell DROP FOREIGN KEY FK_4F00C24A479EC90D');
        $this->addSql('DROP TABLE hero_spell');
        $this->addSql('DROP TABLE magic');
        $this->addSql('DROP TABLE magic_effect');
        $this->addSql('DROP TABLE magic_effect_attribute');
        $this->addSql('DROP TABLE magic_effect_operator');
        $this->addSql('DROP TABLE magic_equipment');
        $this->addSql('DROP TABLE spell');
    }
}

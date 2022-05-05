<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505092928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hero_equipment (id INT AUTO_INCREMENT NOT NULL, hero_id INT NOT NULL, equipment_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_B90E0E845B0BCD (hero_id), INDEX IDX_B90E0E8517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_direction (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, redirectparagraph_id INT NOT NULL, text VARCHAR(500) NOT NULL, maxaccess INT NOT NULL, INDEX IDX_D2A484D18B50597F (paragraph_id), INDEX IDX_D2A484D1F2E423D8 (redirectparagraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hero_equipment ADD CONSTRAINT FK_B90E0E845B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id)');
        $this->addSql('ALTER TABLE hero_equipment ADD CONSTRAINT FK_B90E0E8517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE paragraph_direction ADD CONSTRAINT FK_D2A484D18B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_direction ADD CONSTRAINT FK_D2A484D1F2E423D8 FOREIGN KEY (redirectparagraph_id) REFERENCES paragraph (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hero_equipment');
        $this->addSql('DROP TABLE paragraph_direction');
    }
}

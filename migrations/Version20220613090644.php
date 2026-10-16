<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613090644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paragraph_equipment (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_3FD680E18B50597F (paragraph_id), INDEX IDX_3FD680E1517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraph_equipment ADD CONSTRAINT FK_3FD680E18B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_equipment ADD CONSTRAINT FK_3FD680E1517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE paragraph_equipment');
    }
}

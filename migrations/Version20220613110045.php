<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613110045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_equipment ADD CONSTRAINT FK_3FD680E12CA0AA6B FOREIGN KEY (pragraphequipmentcategory_id) REFERENCES paragraph_equipment_category (id)');
        $this->addSql('CREATE INDEX IDX_3FD680E12CA0AA6B ON paragraph_equipment (pragraphequipmentcategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_equipment DROP FOREIGN KEY FK_3FD680E12CA0AA6B');
        $this->addSql('DROP INDEX IDX_3FD680E12CA0AA6B ON paragraph_equipment');
    }
}

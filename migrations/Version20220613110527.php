<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613110527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_equipment DROP FOREIGN KEY FK_3FD680E12CA0AA6B');
        $this->addSql('DROP INDEX IDX_3FD680E12CA0AA6B ON paragraph_equipment');
        $this->addSql('ALTER TABLE paragraph_equipment CHANGE pragraphequipmentcategory_id paragraphequipmentcategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE paragraph_equipment ADD CONSTRAINT FK_3FD680E19BF79BF0 FOREIGN KEY (paragraphequipmentcategory_id) REFERENCES paragraph_equipment_category (id)');
        $this->addSql('CREATE INDEX IDX_3FD680E19BF79BF0 ON paragraph_equipment (paragraphequipmentcategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_equipment DROP FOREIGN KEY FK_3FD680E19BF79BF0');
        $this->addSql('DROP INDEX IDX_3FD680E19BF79BF0 ON paragraph_equipment');
        $this->addSql('ALTER TABLE paragraph_equipment CHANGE paragraphequipmentcategory_id pragraphequipmentcategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE paragraph_equipment ADD CONSTRAINT FK_3FD680E12CA0AA6B FOREIGN KEY (pragraphequipmentcategory_id) REFERENCES paragraph_equipment_category (id)');
        $this->addSql('CREATE INDEX IDX_3FD680E12CA0AA6B ON paragraph_equipment (pragraphequipmentcategory_id)');
    }
}

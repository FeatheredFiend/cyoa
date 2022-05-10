<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510113328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE merchant (id INT AUTO_INCREMENT NOT NULL, paragraph_id INT NOT NULL, name VARCHAR(25) NOT NULL, INDEX IDX_74AB25E18B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merchant_inventory (id INT AUTO_INCREMENT NOT NULL, merchant_id INT NOT NULL, equipment_id INT NOT NULL, cost INT NOT NULL, INDEX IDX_3F7456A6796D554 (merchant_id), INDEX IDX_3F7456A517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE merchant ADD CONSTRAINT FK_74AB25E18B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE merchant_inventory ADD CONSTRAINT FK_3F7456A6796D554 FOREIGN KEY (merchant_id) REFERENCES merchant (id)');
        $this->addSql('ALTER TABLE merchant_inventory ADD CONSTRAINT FK_3F7456A517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merchant_inventory DROP FOREIGN KEY FK_3F7456A6796D554');
        $this->addSql('DROP TABLE merchant');
        $this->addSql('DROP TABLE merchant_inventory');
    }
}

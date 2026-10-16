<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609135105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adventure_merchant_inventory ADD adventure_id INT NOT NULL, ADD merchant_id INT NOT NULL');
        $this->addSql('ALTER TABLE adventure_merchant_inventory ADD CONSTRAINT FK_839262C755CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id)');
        $this->addSql('ALTER TABLE adventure_merchant_inventory ADD CONSTRAINT FK_839262C76796D554 FOREIGN KEY (merchant_id) REFERENCES merchant (id)');
        $this->addSql('CREATE INDEX IDX_839262C755CF40F9 ON adventure_merchant_inventory (adventure_id)');
        $this->addSql('CREATE INDEX IDX_839262C76796D554 ON adventure_merchant_inventory (merchant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adventure_merchant_inventory DROP FOREIGN KEY FK_839262C755CF40F9');
        $this->addSql('ALTER TABLE adventure_merchant_inventory DROP FOREIGN KEY FK_839262C76796D554');
        $this->addSql('DROP INDEX IDX_839262C755CF40F9 ON adventure_merchant_inventory');
        $this->addSql('DROP INDEX IDX_839262C76796D554 ON adventure_merchant_inventory');
        $this->addSql('ALTER TABLE adventure_merchant_inventory DROP adventure_id, DROP merchant_id');
    }
}

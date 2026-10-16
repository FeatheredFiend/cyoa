<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220612135844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paragraph_action_enemy (id INT AUTO_INCREMENT NOT NULL, paragraphaction_id INT NOT NULL, enemy_id INT NOT NULL, INDEX IDX_D77CEFD93E55F194 (paragraphaction_id), INDEX IDX_D77CEFD9900C982F (enemy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraph_action_enemy ADD CONSTRAINT FK_D77CEFD93E55F194 FOREIGN KEY (paragraphaction_id) REFERENCES paragraph_action (id)');
        $this->addSql('ALTER TABLE paragraph_action_enemy ADD CONSTRAINT FK_D77CEFD9900C982F FOREIGN KEY (enemy_id) REFERENCES enemy (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE paragraph_action_enemy');
    }
}

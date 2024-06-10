<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610173926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capture CHANGE jeu jeu VARCHAR(50) DEFAULT NULL, CHANGE lieu lieu VARCHAR(50) DEFAULT NULL, CHANGE sexe sexe VARCHAR(10) DEFAULT NULL, CHANGE ball ball VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capture CHANGE jeu jeu VARCHAR(50) NOT NULL, CHANGE lieu lieu VARCHAR(50) NOT NULL, CHANGE sexe sexe VARCHAR(10) NOT NULL, CHANGE ball ball INT NOT NULL');
    }
}

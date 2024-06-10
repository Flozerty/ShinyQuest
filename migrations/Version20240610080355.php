<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610080355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE capture (id INT AUTO_INCREMENT NOT NULL, surnom VARCHAR(12) DEFAULT NULL, nb_rencontres INT DEFAULT NULL, date_capture DATETIME DEFAULT NULL, charme_chroma TINYINT(1) NOT NULL, favori TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, date_message DATETIME NOT NULL, contenu_message LONGTEXT NOT NULL, piece_jointe VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE methode_capture (id INT AUTO_INCREMENT NOT NULL, nom_methode VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user CHANGE date_inscription date_inscription DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE capture');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE methode_capture');
        $this->addSql('ALTER TABLE user CHANGE date_inscription date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}

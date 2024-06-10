<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610082540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capture ADD user_id INT NOT NULL, ADD methode_capture_id INT NOT NULL');
        $this->addSql('ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5B5DEC791 FOREIGN KEY (methode_capture_id) REFERENCES methode_capture (id)');
        $this->addSql('CREATE INDEX IDX_8BFEA6E5A76ED395 ON capture (user_id)');
        $this->addSql('CREATE INDEX IDX_8BFEA6E5B5DEC791 ON capture (methode_capture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5A76ED395');
        $this->addSql('ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5B5DEC791');
        $this->addSql('DROP INDEX IDX_8BFEA6E5A76ED395 ON capture');
        $this->addSql('DROP INDEX IDX_8BFEA6E5B5DEC791 ON capture');
        $this->addSql('ALTER TABLE capture DROP user_id, DROP methode_capture_id');
    }
}

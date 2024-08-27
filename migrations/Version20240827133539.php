<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827133539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE pj pj_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF26B1C97 FOREIGN KEY (pj_id) REFERENCES capture (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF26B1C97 ON message (pj_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF26B1C97');
        $this->addSql('DROP INDEX IDX_B6BD307FF26B1C97 ON message');
        $this->addSql('ALTER TABLE message CHANGE pj_id pj INT DEFAULT NULL');
    }
}

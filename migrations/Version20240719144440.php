<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719144440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD user_envoi_id INT NOT NULL, ADD user_recoit_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FDF1A08E5 FOREIGN KEY (user_envoi_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA3409E4 FOREIGN KEY (user_recoit_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FDF1A08E5 ON message (user_envoi_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA3409E4 ON message (user_recoit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FDF1A08E5');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA3409E4');
        $this->addSql('DROP INDEX IDX_B6BD307FDF1A08E5 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FA3409E4 ON message');
        $this->addSql('ALTER TABLE message DROP user_envoi_id, DROP user_recoit_id');
    }
}

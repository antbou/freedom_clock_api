<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315093254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD created_by_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN image.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C53D045FB03A8386 ON image (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045FB03A8386');
        $this->addSql('DROP INDEX IDX_C53D045FB03A8386');
        $this->addSql('ALTER TABLE image DROP created_by_id');
    }
}

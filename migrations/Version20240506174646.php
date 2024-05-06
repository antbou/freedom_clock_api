<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506174646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id UUID NOT NULL, participant_id UUID DEFAULT NULL, selected_option_id UUID DEFAULT NULL, question_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A259D1C3019 ON answer (participant_id)');
        $this->addSql('CREATE INDEX IDX_DADD4A25FFBB0E84 ON answer (selected_option_id)');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('COMMENT ON COLUMN answer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN answer.participant_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN answer.selected_option_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN answer.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE image (id UUID NOT NULL, created_by_id UUID NOT NULL, filename VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, size INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045FB03A8386 ON image (created_by_id)');
        $this->addSql('COMMENT ON COLUMN image.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN image.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN image.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE option (id UUID NOT NULL, image_id UUID DEFAULT NULL, question_id UUID NOT NULL, text VARCHAR(255) DEFAULT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8600B03DA5256D ON option (image_id)');
        $this->addSql('CREATE INDEX IDX_5A8600B01E27F6BF ON option (question_id)');
        $this->addSql('COMMENT ON COLUMN option.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN option.image_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN option.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE participant (id UUID NOT NULL, user_id UUID DEFAULT NULL, quiz_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D79F6B11A76ED395 ON participant (user_id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11853CD175 ON participant (quiz_id)');
        $this->addSql('COMMENT ON COLUMN participant.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN participant.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN participant.quiz_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE question (id UUID NOT NULL, image_id UUID DEFAULT NULL, quiz_id UUID NOT NULL, text VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6F7494E3DA5256D ON question (image_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E853CD175 ON question (quiz_id)');
        $this->addSql('COMMENT ON COLUMN question.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN question.image_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN question.quiz_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE quiz (id UUID NOT NULL, image_id UUID DEFAULT NULL, created_by_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, introduction VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A412FA923DA5256D ON quiz (image_id)');
        $this->addSql('CREATE INDEX IDX_A412FA92B03A8386 ON quiz (created_by_id)');
        $this->addSql('COMMENT ON COLUMN quiz.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quiz.image_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quiz.created_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN quiz.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN quiz.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, provider VARCHAR(255) DEFAULT NULL, provider_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A259D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25FFBB0E84 FOREIGN KEY (selected_option_id) REFERENCES option (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE option ADD CONSTRAINT FK_5A8600B03DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE option ADD CONSTRAINT FK_5A8600B01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA923DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A259D1C3019');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A25FFBB0E84');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045FB03A8386');
        $this->addSql('ALTER TABLE option DROP CONSTRAINT FK_5A8600B03DA5256D');
        $this->addSql('ALTER TABLE option DROP CONSTRAINT FK_5A8600B01E27F6BF');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B11A76ED395');
        $this->addSql('ALTER TABLE participant DROP CONSTRAINT FK_D79F6B11853CD175');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E3DA5256D');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE quiz DROP CONSTRAINT FK_A412FA923DA5256D');
        $this->addSql('ALTER TABLE quiz DROP CONSTRAINT FK_A412FA92B03A8386');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE option');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE "user"');
    }
}

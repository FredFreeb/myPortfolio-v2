<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260412202000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add reply tracking fields to contact_message for admin answer workflow.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_message ADD is_answered BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE contact_message ADD reply_subject VARCHAR(180) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact_message ADD reply_message TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact_message ADD replied_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_message DROP is_answered');
        $this->addSql('ALTER TABLE contact_message DROP reply_subject');
        $this->addSql('ALTER TABLE contact_message DROP reply_message');
        $this->addSql('ALTER TABLE contact_message DROP replied_at');
    }
}

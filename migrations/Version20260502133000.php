<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260502133000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add media fields to Civitalisme editorial blocks.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_update ADD media_type VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_update ADD media_url VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE project_update ADD media_alt VARCHAR(180) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_update DROP media_type');
        $this->addSql('ALTER TABLE project_update DROP media_url');
        $this->addSql('ALTER TABLE project_update DROP media_alt');
    }
}

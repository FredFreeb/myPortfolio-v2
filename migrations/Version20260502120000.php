<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260502120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow long impact text on Civitalisme blocks.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_update ALTER outcome TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_update ALTER outcome TYPE VARCHAR(255)');
    }
}

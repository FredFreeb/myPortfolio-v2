<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260501162500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Increase training period length to support longer labels in admin.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training ALTER COLUMN period TYPE VARCHAR(80)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE training ALTER COLUMN period TYPE VARCHAR(20)');
    }
}

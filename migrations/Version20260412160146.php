<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260412160146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make profile links more flexible with optional URL and subtitle.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile_link ADD subtitle VARCHAR(140) DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_link ALTER url DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile_link DROP subtitle');
        $this->addSql('ALTER TABLE profile_link ALTER url SET NOT NULL');
    }
}

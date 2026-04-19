<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Crée la table `translation` pour le système de i18n multilingue.
 */
final class Version20260419120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create translation table for database-backed i18n (24 EU languages).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE translation (
    id SERIAL NOT NULL,
    translation_key VARCHAR(180) NOT NULL,
    domain VARCHAR(64) NOT NULL,
    section VARCHAR(120) DEFAULT NULL,
    contents JSON NOT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    PRIMARY KEY(id)
)
SQL);
        $this->addSql('CREATE UNIQUE INDEX uniq_translation_key_domain ON translation (translation_key, domain)');
        $this->addSql('CREATE INDEX idx_translation_section ON translation (section)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE translation');
    }
}

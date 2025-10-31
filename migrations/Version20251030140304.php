<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251030140304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE task CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX unique_task_per_project ON task (project_id, title)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project CHANGE title title TINYTEXT DEFAULT NULL');
        $this->addSql('DROP INDEX unique_task_per_project ON task');
        $this->addSql('ALTER TABLE task CHANGE title title TINYTEXT NOT NULL');
    }
}

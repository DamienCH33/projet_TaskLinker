<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251030101630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee CHANGE firstname firstname TINYTEXT NOT NULL, CHANGE lastname lastname TINYTEXT NOT NULL, CHANGE email email TINYTEXT NOT NULL, CHANGE password password LONGTEXT NOT NULL, CHANGE status status TINYTEXT NOT NULL, CHANGE start_date start_date DATE NOT NULL');
        $this->addSql('ALTER TABLE project CHANGE title title TINYTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee CHANGE email email VARCHAR(180) NOT NULL, CHANGE firstname firstname VARCHAR(100) DEFAULT NULL, CHANGE lastname lastname VARCHAR(100) DEFAULT NULL, CHANGE status status VARCHAR(100) DEFAULT NULL, CHANGE start_date start_date DATE DEFAULT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project CHANGE title title TINYTEXT NOT NULL');
    }
}

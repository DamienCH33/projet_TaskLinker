<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250916162800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_employee (task_id INT NOT NULL, employee_id INT NOT NULL, INDEX IDX_1D5F9FED8DB60186 (task_id), INDEX IDX_1D5F9FED8C03F15C (employee_id), PRIMARY KEY(task_id, employee_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_employee ADD CONSTRAINT FK_1D5F9FED8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_employee ADD CONSTRAINT FK_1D5F9FED8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task DROP employee_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_employee DROP FOREIGN KEY FK_1D5F9FED8DB60186');
        $this->addSql('ALTER TABLE task_employee DROP FOREIGN KEY FK_1D5F9FED8C03F15C');
        $this->addSql('DROP TABLE task_employee');
        $this->addSql('ALTER TABLE task ADD employee_id INT NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027140053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project CHANGE title title TINYTEXT NOT NULL, CHANGE start_date start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE project_employee DROP FOREIGN KEY FK_60D1FE7A8C03F15C');
        $this->addSql('ALTER TABLE project_employee ADD CONSTRAINT FK_60D1FE7A8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task CHANGE title title TINYTEXT NOT NULL, CHANGE status status TINYTEXT NOT NULL');
        $this->addSql('ALTER TABLE task_employee ADD CONSTRAINT FK_1D5F9FED8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_employee ADD CONSTRAINT FK_1D5F9FED8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project CHANGE title title VARCHAR(255) NOT NULL, CHANGE start_date start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE project_employee DROP FOREIGN KEY FK_60D1FE7A8C03F15C');
        $this->addSql('ALTER TABLE project_employee ADD CONSTRAINT FK_60D1FE7A8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE task CHANGE title title VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE task_employee DROP FOREIGN KEY FK_1D5F9FED8DB60186');
        $this->addSql('ALTER TABLE task_employee DROP FOREIGN KEY FK_1D5F9FED8C03F15C');
    }
}

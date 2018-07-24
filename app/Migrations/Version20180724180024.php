<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724180024 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file ADD filetype_id INT DEFAULT NULL, DROP mime_type');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361084684DAF FOREIGN KEY (filetype_id) REFERENCES filetype (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C9F361084684DAF ON file (filetype_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361084684DAF');
        $this->addSql('DROP INDEX UNIQ_8C9F361084684DAF ON file');
        $this->addSql('ALTER TABLE file ADD mime_type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP filetype_id');
    }
}

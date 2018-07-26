<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726184019 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media ADD thumbnail_file_id INT DEFAULT NULL, DROP thumbnail');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CB16F1215 FOREIGN KEY (thumbnail_file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2CA10CB16F1215 ON media (thumbnail_file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CB16F1215');
        $this->addSql('DROP INDEX UNIQ_6A2CA10CB16F1215 ON media');
        $this->addSql('ALTER TABLE media ADD thumbnail VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP thumbnail_file_id');
    }
}

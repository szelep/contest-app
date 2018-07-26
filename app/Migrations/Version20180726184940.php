<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726184940 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post ADD thubmnail_media_id INT DEFAULT NULL, DROP thumbnail');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DC178C7ED FOREIGN KEY (thubmnail_media_id) REFERENCES media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8DC178C7ED ON post (thubmnail_media_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DC178C7ED');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8DC178C7ED ON post');
        $this->addSql('ALTER TABLE post ADD thumbnail VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP thubmnail_media_id');
    }
}

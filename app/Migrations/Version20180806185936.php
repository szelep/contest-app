<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180806185936 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, contest_id INT DEFAULT NULL, author VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, thumbnail VARCHAR(255) NOT NULL, published TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_5A8A6C8D1CD0F0DE (contest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CB16F1215');
        $this->addSql('DROP INDEX UNIQ_6A2CA10CB16F1215 ON media');
        $this->addSql('ALTER TABLE media ADD thumbnail VARCHAR(255) NOT NULL, DROP thumbnail_file_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C4B89032C');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085644B89032C');
        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE media ADD thumbnail_file_id INT DEFAULT NULL, DROP thumbnail');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CB16F1215 FOREIGN KEY (thumbnail_file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2CA10CB16F1215 ON media (thumbnail_file_id)');
    }
}

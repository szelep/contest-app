<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180710172302 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, author VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, votes INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_9474526C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contest (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, finish_date DATETIME NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_published TINYINT(1) DEFAULT \'0\' NOT NULL, thumbnail VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, need_to_accept TINYINT(1) DEFAULT \'0\' NOT NULL, comments_allowed TINYINT(1) DEFAULT \'0\' NOT NULL, auto_publish_new_post TINYINT(1) DEFAULT \'0\' NOT NULL, votes_allowed TINYINT(1) DEFAULT \'0\' NOT NULL, post_limit INT DEFAULT 1, allow_remove TINYINT(1) DEFAULT \'0\' NOT NULL, allow_comment_vote TINYINT(1) DEFAULT \'0\' NOT NULL, send_notifications TINYINT(1) DEFAULT \'0\' NOT NULL, allow_report TINYINT(1) DEFAULT \'0\' NOT NULL, max_votes_per_user INT DEFAULT 1, UNIQUE INDEX UNIQ_1A95CB52B36786B (title), UNIQUE INDEX UNIQ_1A95CB5989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allowed_filetypes (contest_id INT NOT NULL, filetype_id INT NOT NULL, INDEX IDX_CAC938371CD0F0DE (contest_id), INDEX IDX_CAC9383784684DAF (filetype_id), PRIMARY KEY(contest_id, filetype_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contest_notifications (contest_id INT NOT NULL, notification_id INT NOT NULL, INDEX IDX_D4B7EA051CD0F0DE (contest_id), INDEX IDX_D4B7EA05EF1A9D84 (notification_id), PRIMARY KEY(contest_id, notification_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, original_name VARCHAR(255) NOT NULL, temp_name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, post VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8C9F3610F72AF14C (temp_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filetype (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, real_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_EEF6C04A1D775834 (value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, media_type VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BF5476CA1D775834 (value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, contest_id INT DEFAULT NULL, author VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, thumbnail VARCHAR(255) NOT NULL, votes INT DEFAULT NULL, INDEX IDX_5A8A6C8D1CD0F0DE (contest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE allowed_filetypes ADD CONSTRAINT FK_CAC938371CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
        $this->addSql('ALTER TABLE allowed_filetypes ADD CONSTRAINT FK_CAC9383784684DAF FOREIGN KEY (filetype_id) REFERENCES filetype (id)');
        $this->addSql('ALTER TABLE contest_notifications ADD CONSTRAINT FK_D4B7EA051CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
        $this->addSql('ALTER TABLE contest_notifications ADD CONSTRAINT FK_D4B7EA05EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE allowed_filetypes DROP FOREIGN KEY FK_CAC938371CD0F0DE');
        $this->addSql('ALTER TABLE contest_notifications DROP FOREIGN KEY FK_D4B7EA051CD0F0DE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D1CD0F0DE');
        $this->addSql('ALTER TABLE allowed_filetypes DROP FOREIGN KEY FK_CAC9383784684DAF');
        $this->addSql('ALTER TABLE contest_notifications DROP FOREIGN KEY FK_D4B7EA05EF1A9D84');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C4B89032C');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE contest');
        $this->addSql('DROP TABLE allowed_filetypes');
        $this->addSql('DROP TABLE contest_notifications');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE filetype');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE post');
    }
}

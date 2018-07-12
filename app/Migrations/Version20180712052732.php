<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180712052732 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, contest_id INT DEFAULT NULL, thumbnail VARCHAR(255) NOT NULL, header_image VARCHAR(255) NOT NULL, background_image VARCHAR(255) NOT NULL, footer_image VARCHAR(255) NOT NULL, font_size INT DEFAULT NULL, background_color VARCHAR(20) DEFAULT NULL, font_color VARCHAR(20) DEFAULT NULL, post_per_line SMALLINT DEFAULT NULL, post_thumbnail_width INT DEFAULT NULL, space_between_post INT DEFAULT NULL, UNIQUE INDEX UNIQ_97601F831CD0F0DE (contest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F831CD0F0DE FOREIGN KEY (contest_id) REFERENCES contest (id)');
        $this->addSql('ALTER TABLE contest ADD template_id INT DEFAULT NULL, DROP thumbnail');
        $this->addSql('ALTER TABLE contest ADD CONSTRAINT FK_1A95CB55DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1A95CB55DA0FB8 ON contest (template_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contest DROP FOREIGN KEY FK_1A95CB55DA0FB8');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP INDEX UNIQ_1A95CB55DA0FB8 ON contest');
        $this->addSql('ALTER TABLE contest ADD thumbnail VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP template_id');
    }
}

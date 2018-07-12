<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180712060010 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE template ADD thumbnail_id INT DEFAULT NULL, ADD header_image_id INT DEFAULT NULL, ADD background_image_id INT DEFAULT NULL, ADD footer_image_id INT DEFAULT NULL, DROP thumbnail, DROP header_image, DROP background_image, DROP footer_image');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F83FDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F838C782417 FOREIGN KEY (header_image_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F83E6DA28AA FOREIGN KEY (background_image_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F8345C207B5 FOREIGN KEY (footer_image_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97601F83FDFF2E92 ON template (thumbnail_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97601F838C782417 ON template (header_image_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97601F83E6DA28AA ON template (background_image_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97601F8345C207B5 ON template (footer_image_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F83FDFF2E92');
        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F838C782417');
        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F83E6DA28AA');
        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F8345C207B5');
        $this->addSql('DROP INDEX UNIQ_97601F83FDFF2E92 ON template');
        $this->addSql('DROP INDEX UNIQ_97601F838C782417 ON template');
        $this->addSql('DROP INDEX UNIQ_97601F83E6DA28AA ON template');
        $this->addSql('DROP INDEX UNIQ_97601F8345C207B5 ON template');
        $this->addSql('ALTER TABLE template ADD thumbnail VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD header_image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD background_image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD footer_image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP thumbnail_id, DROP header_image_id, DROP background_image_id, DROP footer_image_id');
    }
}

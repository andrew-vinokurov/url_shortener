<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129005545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE short_url (id INT AUTO_INCREMENT NOT NULL, url LONGTEXT NOT NULL, hash_url CHAR(32) NOT NULL, expire_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_83360531F740E39D (hash_url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE short_url_stats (id INT AUTO_INCREMENT NOT NULL, url_id INT DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, client_ip BIGINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_EC8AD2EA81CFDAE7 (url_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE short_url_stats ADD CONSTRAINT FK_EC8AD2EA81CFDAE7 FOREIGN KEY (url_id) REFERENCES short_url (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE short_url_stats DROP FOREIGN KEY FK_EC8AD2EA81CFDAE7');
        $this->addSql('DROP TABLE short_url');
        $this->addSql('DROP TABLE short_url_stats');
    }
}

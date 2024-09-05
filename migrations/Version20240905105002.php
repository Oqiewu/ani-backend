<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905105002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anime_title (id INT AUTO_INCREMENT NOT NULL, `rank` INT DEFAULT NULL, name VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, small_image_url VARCHAR(255) NOT NULL, large_image_url VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, genres JSON DEFAULT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, release_date DATETIME NOT NULL, age_rating VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE anime_title');
    }
}

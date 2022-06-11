<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220611231036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD user_id INT NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD total_price DOUBLE PRECISION NOT NULL, ADD product LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD city VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP user_id, DROP address, DROP city, DROP total_price, DROP product, DROP status');
        $this->addSql('ALTER TABLE user DROP city');
    }
}

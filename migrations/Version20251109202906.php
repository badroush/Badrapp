<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251109202906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article CHANGE prixachat prixachat DOUBLE PRECISION DEFAULT NULL, CHANGE prixvente prixvente DOUBLE PRECISION DEFAULT NULL, CHANGE stock stock INT NOT NULL, CHANGE seuilalerte seuilalerte INT NOT NULL, CHANGE datecreation datecreation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE mouvement_stock CHANGE date_mouvement date_mouvement DATETIME NOT NULL');
        $this->addSql('ALTER TABLE mouvement_stock RENAME INDEX idx_61e2c8eb29b98b8d TO IDX_61E2C8EB5AF81F68');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE article CHANGE prixachat prixachat DOUBLE PRECISION DEFAULT \'0\', CHANGE prixvente prixvente DOUBLE PRECISION DEFAULT \'0\', CHANGE stock stock INT DEFAULT 0 NOT NULL, CHANGE seuilalerte seuilalerte INT DEFAULT 5 NOT NULL, CHANGE datecreation datecreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE mouvement_stock CHANGE date_mouvement date_mouvement DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement_stock RENAME INDEX idx_61e2c8eb5af81f68 TO IDX_61E2C8EB29B98B8D');
    }
}

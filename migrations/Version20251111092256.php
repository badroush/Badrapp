<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251111092256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT NOT NULL, sous_admin_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, reference VARCHAR(20) NOT NULL, date_acquisition DATE NOT NULL, etat VARCHAR(50) NOT NULL, INDEX IDX_18D2B091FF631228 (etablissement_id), INDEX IDX_18D2B0916C65B645 (sous_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0916C65B645 FOREIGN KEY (sous_admin_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091FF631228');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0916C65B645');
        $this->addSql('DROP TABLE materiel');
    }
}

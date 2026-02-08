<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206233314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action_controle (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, roles JSON NOT NULL, active TINYINT(1) NOT NULL, masque TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adhesion (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) NOT NULL, etat_civil INT NOT NULL, annee_adhesion INT NOT NULL, telephone INT NOT NULL, date_adhesion DATE NOT NULL, photo VARCHAR(255) NOT NULL, valider TINYINT(1) DEFAULT 0 NOT NULL, imprimer DATETIME DEFAULT NULL, INDEX IDX_C50CA65AFF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appel (id INT AUTO_INCREMENT NOT NULL, contact_emetteur_id INT NOT NULL, contact_recepteur_id INT NOT NULL, type VARCHAR(10) NOT NULL, date_appel DATE NOT NULL, heure_appel TIME NOT NULL, INDEX IDX_130D3BD17459132 (contact_emetteur_id), INDEX IDX_130D3BD17FE4A19 (contact_recepteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, fournisseur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, reference VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, prixachat DOUBLE PRECISION DEFAULT NULL, prixvente DOUBLE PRECISION DEFAULT NULL, stock INT NOT NULL, seuilalerte INT NOT NULL, datecreation DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E66AEA34913 (reference), INDEX IDX_23A0E66BCF5E72D (categorie_id), INDEX IDX_23A0E66670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association_sportif (id INT AUTO_INCREMENT NOT NULL, delegation_id INT DEFAULT NULL, classe_sportif_id INT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, specialites JSON NOT NULL, date_construction DATE NOT NULL, telephone VARCHAR(20) NOT NULL, fax VARCHAR(20) DEFAULT NULL, directeur VARCHAR(255) NOT NULL, tel_directeur VARCHAR(20) NOT NULL, directeur_adjoint VARCHAR(255) DEFAULT NULL, tel_dir_adj VARCHAR(20) DEFAULT NULL, secretariat_general VARCHAR(255) NOT NULL, tel_sec_gen VARCHAR(20) DEFAULT NULL, tresorier VARCHAR(255) NOT NULL, tel_tres VARCHAR(20) DEFAULT NULL, nombre_males INT DEFAULT NULL, nombre_femelles INT DEFAULT NULL, INDEX IDX_1CDED42756CBBCF5 (delegation_id), INDEX IDX_1CDED4275A8CE910 (classe_sportif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beneficiaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, id_chapitre_id INT NOT NULL, id_etablissement_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, annee INT NOT NULL, created_by VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_73F2F77B7AC228C (id_chapitre_id), INDEX IDX_73F2F77B1CE947F9 (id_etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre_achats_collectif (id INT AUTO_INCREMENT NOT NULL, chapitre_budget_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_B5E6A2D0475F28EC (chapitre_budget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapitre_budget (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe_sportif (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connexion_internet (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT NOT NULL, type_connexion VARCHAR(50) NOT NULL, reference_modem VARCHAR(100) DEFAULT NULL, numero_ligne VARCHAR(50) DEFAULT NULL, debit VARCHAR(20) DEFAULT NULL, date_mise_en_marche DATE DEFAULT NULL, fournisseur VARCHAR(100) DEFAULT NULL, etat_connexion VARCHAR(20) DEFAULT \'active\' NOT NULL, INDEX IDX_8CF5C788FF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(20) NOT NULL, fax VARCHAR(20) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delegation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_amicale (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, beneficiaire_id INT NOT NULL, statut VARCHAR(50) NOT NULL, date_demande DATETIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_340462BE4CC8505A (offre_id), INDEX IDX_340462BE5AF81F68 (beneficiaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_maintenance (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT NOT NULL, responsable_demande_id INT NOT NULL, technicien_assigne_id INT DEFAULT NULL, description LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, priorite VARCHAR(20) NOT NULL, statut VARCHAR(20) DEFAULT \'envoyee\' NOT NULL, date_traitement DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, message_resolution LONGTEXT DEFAULT NULL, date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_8EA3A79DFF631228 (etablissement_id), INDEX IDX_8EA3A79D74C0AD18 (responsable_demande_id), INDEX IDX_8EA3A79DB4C58F73 (technicien_assigne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_achats_collectif (id INT AUTO_INCREMENT NOT NULL, chapitre_achats_collectif_id INT NOT NULL, article_id INT NOT NULL, prix_vente NUMERIC(10, 3) NOT NULL, INDEX IDX_AFE7A943E906993 (chapitre_achats_collectif_id), INDEX IDX_AFE7A947294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_seance (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, fichier VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse LONGTEXT DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_panier_achat_collectif (id INT AUTO_INCREMENT NOT NULL, panier_id INT NOT NULL, detail_achat_collectif_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_497A77EAF77D927C (panier_id), INDEX IDX_497A77EA4AEB9B95 (detail_achat_collectif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE libelle_materiel (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT NOT NULL, sous_admin_id INT DEFAULT NULL, libelle_materiel_id INT DEFAULT NULL, date_acquisition DATE NOT NULL, etat VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, nbr DOUBLE PRECISION DEFAULT NULL, emplacement VARCHAR(255) DEFAULT NULL, serie VARCHAR(255) DEFAULT NULL, INDEX IDX_18D2B091FF631228 (etablissement_id), INDEX IDX_18D2B0916C65B645 (sous_admin_id), INDEX IDX_18D2B0912B9BE1A1 (libelle_materiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvement_stock (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, fournisseur_id INT DEFAULT NULL, beneficiaire_id INT DEFAULT NULL, type VARCHAR(20) NOT NULL, quantite INT NOT NULL, date_mouvement DATETIME NOT NULL, INDEX IDX_61E2C8EB7294869C (article_id), INDEX IDX_61E2C8EB670C757F (fournisseur_id), INDEX IDX_61E2C8EB5AF81F68 (beneficiaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_amicale (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, etat VARCHAR(50) NOT NULL, duree_en_mois INT DEFAULT NULL, montant_par_mois NUMERIC(10, 3) DEFAULT NULL, frais_inscription NUMERIC(10, 3) DEFAULT NULL, annee INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_achat_collectif (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT NOT NULL, chapitre_achats_collectif_id INT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', annee_achats INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', etat VARCHAR(20) DEFAULT \'en_cours\' NOT NULL, INDEX IDX_470D36D4FF631228 (etablissement_id), INDEX IDX_470D36D43E906993 (chapitre_achats_collectif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_technique (id INT AUTO_INCREMENT NOT NULL, demande_id INT NOT NULL, technicien_id INT NOT NULL, contenu LONGTEXT NOT NULL, date_reponse DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_1905F05180E95E18 (demande_id), INDEX IDX_1905F05113457256 (technicien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource_affectation (id INT AUTO_INCREMENT NOT NULL, id_ressource_id INT DEFAULT NULL, id_etablissement_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, annee INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FDA24790DFADA058 (id_ressource_id), INDEX IDX_FDA247901CE947F9 (id_etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource_budget (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance_plenaire (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, date DATE NOT NULL, type VARCHAR(100) NOT NULL, INDEX IDX_F3A951BFEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance_plenaire_document_seance (seance_plenaire_id INT NOT NULL, document_seance_id INT NOT NULL, INDEX IDX_4FBC0654A4E90BB7 (seance_plenaire_id), INDEX IDX_4FBC0654F1415235 (document_seance_id), PRIMARY KEY(seance_plenaire_id, document_seance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT DEFAULT NULL, grade_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, nomp VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, is_sous_admin TINYINT(1) DEFAULT 0 NOT NULL, matricule VARCHAR(10) NOT NULL, cin VARCHAR(8) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, passcode VARCHAR(255) DEFAULT NULL, passcode_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_passcode_verified TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_8D93D64912B2DC9C (matricule), UNIQUE INDEX UNIQ_8D93D649ABE530DA (cin), INDEX IDX_8D93D649FF631228 (etablissement_id), INDEX IDX_8D93D649FE19A1A8 (grade_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT FK_C50CA65AFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE appel ADD CONSTRAINT FK_130D3BD17459132 FOREIGN KEY (contact_emetteur_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE appel ADD CONSTRAINT FK_130D3BD17FE4A19 FOREIGN KEY (contact_recepteur_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE association_sportif ADD CONSTRAINT FK_1CDED42756CBBCF5 FOREIGN KEY (delegation_id) REFERENCES delegation (id)');
        $this->addSql('ALTER TABLE association_sportif ADD CONSTRAINT FK_1CDED4275A8CE910 FOREIGN KEY (classe_sportif_id) REFERENCES classe_sportif (id)');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77B7AC228C FOREIGN KEY (id_chapitre_id) REFERENCES chapitre_budget (id)');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77B1CE947F9 FOREIGN KEY (id_etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE chapitre_achats_collectif ADD CONSTRAINT FK_B5E6A2D0475F28EC FOREIGN KEY (chapitre_budget_id) REFERENCES chapitre_budget (id)');
        $this->addSql('ALTER TABLE connexion_internet ADD CONSTRAINT FK_8CF5C788FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE demande_amicale ADD CONSTRAINT FK_340462BE4CC8505A FOREIGN KEY (offre_id) REFERENCES offre_amicale (id)');
        $this->addSql('ALTER TABLE demande_amicale ADD CONSTRAINT FK_340462BE5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_maintenance ADD CONSTRAINT FK_8EA3A79DFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE demande_maintenance ADD CONSTRAINT FK_8EA3A79D74C0AD18 FOREIGN KEY (responsable_demande_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_maintenance ADD CONSTRAINT FK_8EA3A79DB4C58F73 FOREIGN KEY (technicien_assigne_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE detail_achats_collectif ADD CONSTRAINT FK_AFE7A943E906993 FOREIGN KEY (chapitre_achats_collectif_id) REFERENCES chapitre_achats_collectif (id)');
        $this->addSql('ALTER TABLE detail_achats_collectif ADD CONSTRAINT FK_AFE7A947294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE item_panier_achat_collectif ADD CONSTRAINT FK_497A77EAF77D927C FOREIGN KEY (panier_id) REFERENCES panier_achat_collectif (id)');
        $this->addSql('ALTER TABLE item_panier_achat_collectif ADD CONSTRAINT FK_497A77EA4AEB9B95 FOREIGN KEY (detail_achat_collectif_id) REFERENCES detail_achats_collectif (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0916C65B645 FOREIGN KEY (sous_admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0912B9BE1A1 FOREIGN KEY (libelle_materiel_id) REFERENCES libelle_materiel (id)');
        $this->addSql('ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE mouvement_stock ADD CONSTRAINT FK_61E2C8EB5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
        $this->addSql('ALTER TABLE panier_achat_collectif ADD CONSTRAINT FK_470D36D4FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE panier_achat_collectif ADD CONSTRAINT FK_470D36D43E906993 FOREIGN KEY (chapitre_achats_collectif_id) REFERENCES chapitre_achats_collectif (id)');
        $this->addSql('ALTER TABLE reponse_technique ADD CONSTRAINT FK_1905F05180E95E18 FOREIGN KEY (demande_id) REFERENCES demande_maintenance (id)');
        $this->addSql('ALTER TABLE reponse_technique ADD CONSTRAINT FK_1905F05113457256 FOREIGN KEY (technicien_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ressource_affectation ADD CONSTRAINT FK_FDA24790DFADA058 FOREIGN KEY (id_ressource_id) REFERENCES ressource_budget (id)');
        $this->addSql('ALTER TABLE ressource_affectation ADD CONSTRAINT FK_FDA247901CE947F9 FOREIGN KEY (id_etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE seance_plenaire ADD CONSTRAINT FK_F3A951BFEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association_sportif (id)');
        $this->addSql('ALTER TABLE seance_plenaire_document_seance ADD CONSTRAINT FK_4FBC0654A4E90BB7 FOREIGN KEY (seance_plenaire_id) REFERENCES seance_plenaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance_plenaire_document_seance ADD CONSTRAINT FK_4FBC0654F1415235 FOREIGN KEY (document_seance_id) REFERENCES document_seance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adhesion DROP FOREIGN KEY FK_C50CA65AFF631228');
        $this->addSql('ALTER TABLE appel DROP FOREIGN KEY FK_130D3BD17459132');
        $this->addSql('ALTER TABLE appel DROP FOREIGN KEY FK_130D3BD17FE4A19');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66670C757F');
        $this->addSql('ALTER TABLE association_sportif DROP FOREIGN KEY FK_1CDED42756CBBCF5');
        $this->addSql('ALTER TABLE association_sportif DROP FOREIGN KEY FK_1CDED4275A8CE910');
        $this->addSql('ALTER TABLE budget DROP FOREIGN KEY FK_73F2F77B7AC228C');
        $this->addSql('ALTER TABLE budget DROP FOREIGN KEY FK_73F2F77B1CE947F9');
        $this->addSql('ALTER TABLE chapitre_achats_collectif DROP FOREIGN KEY FK_B5E6A2D0475F28EC');
        $this->addSql('ALTER TABLE connexion_internet DROP FOREIGN KEY FK_8CF5C788FF631228');
        $this->addSql('ALTER TABLE demande_amicale DROP FOREIGN KEY FK_340462BE4CC8505A');
        $this->addSql('ALTER TABLE demande_amicale DROP FOREIGN KEY FK_340462BE5AF81F68');
        $this->addSql('ALTER TABLE demande_maintenance DROP FOREIGN KEY FK_8EA3A79DFF631228');
        $this->addSql('ALTER TABLE demande_maintenance DROP FOREIGN KEY FK_8EA3A79D74C0AD18');
        $this->addSql('ALTER TABLE demande_maintenance DROP FOREIGN KEY FK_8EA3A79DB4C58F73');
        $this->addSql('ALTER TABLE detail_achats_collectif DROP FOREIGN KEY FK_AFE7A943E906993');
        $this->addSql('ALTER TABLE detail_achats_collectif DROP FOREIGN KEY FK_AFE7A947294869C');
        $this->addSql('ALTER TABLE item_panier_achat_collectif DROP FOREIGN KEY FK_497A77EAF77D927C');
        $this->addSql('ALTER TABLE item_panier_achat_collectif DROP FOREIGN KEY FK_497A77EA4AEB9B95');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091FF631228');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0916C65B645');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0912B9BE1A1');
        $this->addSql('ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EB7294869C');
        $this->addSql('ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EB670C757F');
        $this->addSql('ALTER TABLE mouvement_stock DROP FOREIGN KEY FK_61E2C8EB5AF81F68');
        $this->addSql('ALTER TABLE panier_achat_collectif DROP FOREIGN KEY FK_470D36D4FF631228');
        $this->addSql('ALTER TABLE panier_achat_collectif DROP FOREIGN KEY FK_470D36D43E906993');
        $this->addSql('ALTER TABLE reponse_technique DROP FOREIGN KEY FK_1905F05180E95E18');
        $this->addSql('ALTER TABLE reponse_technique DROP FOREIGN KEY FK_1905F05113457256');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE ressource_affectation DROP FOREIGN KEY FK_FDA24790DFADA058');
        $this->addSql('ALTER TABLE ressource_affectation DROP FOREIGN KEY FK_FDA247901CE947F9');
        $this->addSql('ALTER TABLE seance_plenaire DROP FOREIGN KEY FK_F3A951BFEFB9C8A5');
        $this->addSql('ALTER TABLE seance_plenaire_document_seance DROP FOREIGN KEY FK_4FBC0654A4E90BB7');
        $this->addSql('ALTER TABLE seance_plenaire_document_seance DROP FOREIGN KEY FK_4FBC0654F1415235');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FF631228');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE19A1A8');
        $this->addSql('DROP TABLE action_controle');
        $this->addSql('DROP TABLE adhesion');
        $this->addSql('DROP TABLE appel');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE association_sportif');
        $this->addSql('DROP TABLE beneficiaire');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE chapitre_achats_collectif');
        $this->addSql('DROP TABLE chapitre_budget');
        $this->addSql('DROP TABLE classe_sportif');
        $this->addSql('DROP TABLE connexion_internet');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE delegation');
        $this->addSql('DROP TABLE demande_amicale');
        $this->addSql('DROP TABLE demande_maintenance');
        $this->addSql('DROP TABLE detail_achats_collectif');
        $this->addSql('DROP TABLE document_seance');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE item_panier_achat_collectif');
        $this->addSql('DROP TABLE libelle_materiel');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE mouvement_stock');
        $this->addSql('DROP TABLE offre_amicale');
        $this->addSql('DROP TABLE panier_achat_collectif');
        $this->addSql('DROP TABLE reponse_technique');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE ressource_affectation');
        $this->addSql('DROP TABLE ressource_budget');
        $this->addSql('DROP TABLE seance_plenaire');
        $this->addSql('DROP TABLE seance_plenaire_document_seance');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

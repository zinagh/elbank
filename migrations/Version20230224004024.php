<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224004024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carte (id INT AUTO_INCREMENT NOT NULL, idclient DOUBLE PRECISION NOT NULL, date_ex DATE NOT NULL, mp VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, num_carte INT NOT NULL, picture_name VARCHAR(255) DEFAULT NULL, picture_original_name VARCHAR(255) DEFAULT NULL, picture_mime_type VARCHAR(255) DEFAULT NULL, picture_size INT DEFAULT NULL, picture_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_categorie_carte (carte_id INT NOT NULL, categorie_carte_id INT NOT NULL, INDEX IDX_846D0585C9C7CEB6 (carte_id), INDEX IDX_846D0585F1408FDC (categorie_carte_id), PRIMARY KEY(carte_id, categorie_carte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorieCarte (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, montant_max DOUBLE PRECISION NOT NULL, date_categorie DATETIME DEFAULT NULL, FULLTEXT INDEX IDX_511927B58CDE57296DE44026 (type, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cheques (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT DEFAULT NULL, destinataire_id INT DEFAULT NULL, carnets_cheques_id INT DEFAULT NULL, idchequiers_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_cheque DATETIME NOT NULL, lieu VARCHAR(100) NOT NULL, signature INT NOT NULL, client_tel INT NOT NULL, rib_sender VARCHAR(255) NOT NULL, rib_reciever VARCHAR(255) NOT NULL, INDEX IDX_C2782E2A76C50E4A (proprietaire_id), INDEX IDX_C2782E2AA4F84F6E (destinataire_id), INDEX IDX_C2782E2A46F79EFE (carnets_cheques_id), INDEX IDX_C2782E2A9988C095 (idchequiers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chequier (id INT AUTO_INCREMENT NOT NULL, num_compte_id INT DEFAULT NULL, nom_client_id INT NOT NULL, date_creation DATETIME NOT NULL, motif_chequier VARCHAR(255) NOT NULL, etat_chequier INT NOT NULL, client_tel INT NOT NULL, INDEX IDX_A2F202D6801B12FC (num_compte_id), INDEX IDX_A2F202D68D1A1860 (nom_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, fullname_client_id INT NOT NULL, num_compte BIGINT NOT NULL, rib_compte VARCHAR(255) NOT NULL, solde_compte DOUBLE PRECISION NOT NULL, date_creation DATETIME NOT NULL, type_compte VARCHAR(100) NOT NULL, seuil_compte DOUBLE PRECISION NOT NULL, taux_interet DOUBLE PRECISION NOT NULL, etat_compte INT NOT NULL, UNIQUE INDEX UNIQ_CFF6526076D705D8 (num_compte), UNIQUE INDEX UNIQ_CFF65260D891C777 (rib_compte), INDEX IDX_CFF6526033BE058B (fullname_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, numero_compte_id INT DEFAULT NULL, mont_credit INT NOT NULL, datepe DATE NOT NULL, datede DATE NOT NULL, duree_c INT NOT NULL, echeance DATE NOT NULL, taux_interet INT NOT NULL, decision TINYINT(1) NOT NULL, etat_credit VARCHAR(255) NOT NULL, type_credit VARCHAR(255) NOT NULL, INDEX IDX_1CC16EFEBFD610BF (numero_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, id_operation INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_operation DATE NOT NULL, type_c VARCHAR(100) NOT NULL, depense_hebdomadaire DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation_credit (id INT AUTO_INCREMENT NOT NULL, credit_id INT NOT NULL, date_op DATE NOT NULL, mont_payer INT NOT NULL, echeance DATE NOT NULL, taux_interet INT NOT NULL, solvabilite INT NOT NULL, type_operation VARCHAR(255) NOT NULL, INDEX IDX_6141D79CCE062FF9 (credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, nom_u_id INT DEFAULT NULL, type_rec VARCHAR(100) NOT NULL, date_rec DATETIME NOT NULL, etat_rec VARCHAR(100) NOT NULL, desc_rec VARCHAR(150) NOT NULL, INDEX IDX_CE606404167559FE (nom_u_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, rib_emetteur_id INT DEFAULT NULL, fullname_emetteur_id INT DEFAULT NULL, rib_recepteur VARCHAR(24) NOT NULL, montant_transaction DOUBLE PRECISION NOT NULL, date_transaction DATETIME NOT NULL, description_transaction VARCHAR(255) NOT NULL, fullname_recepteur VARCHAR(150) NOT NULL, type_transaction VARCHAR(100) NOT NULL, etat_transaction INT NOT NULL, INDEX IDX_723705D169AC2DBB (rib_emetteur_id), INDEX IDX_723705D169057DC9 (fullname_emetteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, cin_u INT NOT NULL, nom_u VARCHAR(100) NOT NULL, prenom_u VARCHAR(100) NOT NULL, date_naissance DATE NOT NULL, email_u VARCHAR(255) NOT NULL, num_tel INT NOT NULL, role VARCHAR(100) NOT NULL, mot_de_passe VARCHAR(100) NOT NULL, activation_token VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(50) DEFAULT NULL, bloquer_token VARCHAR(50) DEFAULT NULL, connecte_token VARCHAR(255) DEFAULT NULL, etat VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carte_categorie_carte ADD CONSTRAINT FK_846D0585C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carte_categorie_carte ADD CONSTRAINT FK_846D0585F1408FDC FOREIGN KEY (categorie_carte_id) REFERENCES categorieCarte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cheques ADD CONSTRAINT FK_C2782E2A76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE cheques ADD CONSTRAINT FK_C2782E2AA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE cheques ADD CONSTRAINT FK_C2782E2A46F79EFE FOREIGN KEY (carnets_cheques_id) REFERENCES chequier (id)');
        $this->addSql('ALTER TABLE cheques ADD CONSTRAINT FK_C2782E2A9988C095 FOREIGN KEY (idchequiers_id) REFERENCES chequier (id)');
        $this->addSql('ALTER TABLE chequier ADD CONSTRAINT FK_A2F202D6801B12FC FOREIGN KEY (num_compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE chequier ADD CONSTRAINT FK_A2F202D68D1A1860 FOREIGN KEY (nom_client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF6526033BE058B FOREIGN KEY (fullname_client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFEBFD610BF FOREIGN KEY (numero_compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE operation_credit ADD CONSTRAINT FK_6141D79CCE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404167559FE FOREIGN KEY (nom_u_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D169AC2DBB FOREIGN KEY (rib_emetteur_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D169057DC9 FOREIGN KEY (fullname_emetteur_id) REFERENCES compte (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carte_categorie_carte DROP FOREIGN KEY FK_846D0585C9C7CEB6');
        $this->addSql('ALTER TABLE carte_categorie_carte DROP FOREIGN KEY FK_846D0585F1408FDC');
        $this->addSql('ALTER TABLE cheques DROP FOREIGN KEY FK_C2782E2A76C50E4A');
        $this->addSql('ALTER TABLE cheques DROP FOREIGN KEY FK_C2782E2AA4F84F6E');
        $this->addSql('ALTER TABLE cheques DROP FOREIGN KEY FK_C2782E2A46F79EFE');
        $this->addSql('ALTER TABLE cheques DROP FOREIGN KEY FK_C2782E2A9988C095');
        $this->addSql('ALTER TABLE chequier DROP FOREIGN KEY FK_A2F202D6801B12FC');
        $this->addSql('ALTER TABLE chequier DROP FOREIGN KEY FK_A2F202D68D1A1860');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF6526033BE058B');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFEBFD610BF');
        $this->addSql('ALTER TABLE operation_credit DROP FOREIGN KEY FK_6141D79CCE062FF9');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404167559FE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D169AC2DBB');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D169057DC9');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE carte_categorie_carte');
        $this->addSql('DROP TABLE categorieCarte');
        $this->addSql('DROP TABLE cheques');
        $this->addSql('DROP TABLE chequier');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE operation_credit');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE utilisateur');
    }
}

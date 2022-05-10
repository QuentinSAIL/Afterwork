<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114153827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455DC499668');
        $this->addSql('DROP INDEX id_role ON client');
        $this->addSql('ALTER TABLE client DROP id_role');
        $this->addSql('ALTER TABLE commande CHANGE id_statut id_statut INT DEFAULT NULL, CHANGE id_employe id_employe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE id_client id_client INT DEFAULT NULL, CHANGE id_produit id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE declinaison_produit MODIFY id_declinaison_produit INT NOT NULL');
        $this->addSql('ALTER TABLE declinaison_produit DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE declinaison_produit CHANGE id_declinaison_produit id_declinaison_produit INT NOT NULL');
        $this->addSql('ALTER TABLE declinaison_produit ADD PRIMARY KEY (id_declinaison_produit, id_produit, id_declinaison)');
        $this->addSql('ALTER TABLE employe ADD role_JWT LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', CHANGE id_role id_role INT DEFAULT NULL, CHANGE mail mail VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE id_tva id_tva INT DEFAULT NULL, CHANGE id_categorie id_categorie INT DEFAULT NULL, CHANGE import import TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_commande DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE produit_commande CHANGE id_declinaison_produit id_declinaison_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_commande ADD PRIMARY KEY (id_produit, id_commande)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD id_role INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455DC499668 FOREIGN KEY (id_role) REFERENCES role (id_role)');
        $this->addSql('CREATE INDEX id_role ON client (id_role)');
        $this->addSql('ALTER TABLE commande CHANGE id_statut id_statut INT NOT NULL, CHANGE id_employe id_employe INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire CHANGE id_produit id_produit INT NOT NULL, CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE declinaison_produit DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE declinaison_produit CHANGE id_declinaison_produit id_declinaison_produit INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE declinaison_produit ADD PRIMARY KEY (id_declinaison_produit, id_declinaison, id_produit)');
        $this->addSql('ALTER TABLE employe DROP role_JWT, CHANGE id_role id_role INT NOT NULL, CHANGE mail mail VARCHAR(52) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT NOT NULL, CHANGE id_tva id_tva INT NOT NULL, CHANGE import import TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE produit_commande DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE produit_commande CHANGE id_declinaison_produit id_declinaison_produit INT NOT NULL');
        $this->addSql('ALTER TABLE produit_commande ADD PRIMARY KEY (id_commande, id_produit)');
    }
}

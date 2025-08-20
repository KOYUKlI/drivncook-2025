CREATE TABLE `cache` (
  `clé` varchar(255) PRIMARY KEY NOT NULL,
  `valeur` mediumtext NOT NULL,
  `expiration` int NOT NULL
);

CREATE TABLE `verrous_cache` (
  `clé` varchar(255) PRIMARY KEY NOT NULL,
  `propriétaire` varchar(255) NOT NULL,
  `expiration` int NOT NULL
);

CREATE TABLE `commissions` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `franchisé_id` bigint NOT NULL,
  `année_période` int NOT NULL,
  `mois_période` tinyint NOT NULL,
  `chiffre_affaires` decimal(12,2) NOT NULL,
  `taux` decimal(5,2) NOT NULL DEFAULT '4.00',
  `montant_dû` decimal(12,2),
  `statut` ENUM ('en_attente', 'payée', 'annulée') NOT NULL DEFAULT 'en_attente',
  `calculé_le` timestamp NOT NULL,
  `payé_le` timestamp DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `indicateurs_conformité` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `franchise_id` bigint NOT NULL,
  `année_période` int NOT NULL,
  `mois_période` tinyint NOT NULL,
  `chiffre_affaires_externe` decimal(12,2) NOT NULL DEFAULT '0.00',
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `commandes_clients` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `camion_id` bigint NOT NULL,
  `client_id` bigint DEFAULT null,
  `emplacement_id` bigint DEFAULT null,
  `carte_fidélité_id` bigint DEFAULT null,
  `type_commande` ENUM ('en_ligne', 'sur_place', 'réservation') NOT NULL DEFAULT 'en_ligne',
  `statut` ENUM ('en_attente', 'confirmée', 'préparation', 'prête', 'terminée', 'annulée') NOT NULL DEFAULT 'en_attente',
  `statut_paiement` ENUM ('en_attente', 'payé', 'échoué', 'remboursé') NOT NULL DEFAULT 'en_attente',
  `référence` varchar(30) DEFAULT null,
  `retrait_le` datetime DEFAULT null,
  `prix_total` decimal(12,2) NOT NULL,
  `commandé_le` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `ingrédients_plat` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `plat_id` bigint NOT NULL,
  `produit_id` bigint NOT NULL,
  `qté_par_plat` decimal(12,3) NOT NULL,
  `unité` varchar(20) NOT NULL,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `traductions_plat` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `plat_id` bigint NOT NULL,
  `locale` varchar(10) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `plats` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `prix` decimal(8,2) NOT NULL,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `inscriptions_événements` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `événement_id` bigint NOT NULL,
  `camion_id` bigint NOT NULL,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `événements` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `date` date DEFAULT null,
  `lieu` varchar(255) DEFAULT null,
  `description` text,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `travaux_échoués` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connexion` text NOT NULL,
  `file` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `échoué_le` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `franchises` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `inventaire` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `entrepôt_id` bigint NOT NULL,
  `produit_id` bigint NOT NULL,
  `stock_disponible` decimal(12,3) NOT NULL DEFAULT '0.000',
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `ajustements_inventaire` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `inventaire_id` bigint NOT NULL,
  `écart_qté` decimal(12,3) NOT NULL,
  `raison` ENUM ('gaspillage', 'casse', 'audit') NOT NULL,
  `note` varchar(255) DEFAULT null,
  `créé_le` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `lots_inventaire` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `inventaire_id` bigint NOT NULL,
  `code_lot` varchar(64) NOT NULL,
  `expire_le` date DEFAULT null,
  `qté` decimal(12,3) NOT NULL,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `mouvements_inventaire` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `inventaire_id` bigint NOT NULL,
  `type` ENUM ('entrée', 'sortie', 'transfert') NOT NULL,
  `qté` decimal(12,3) NOT NULL,
  `raison` ENUM ('achat', 'vente', 'préparation', 'gaspillage', 'ajustement', 'transfert') NOT NULL,
  `table_ref` varchar(40) DEFAULT null,
  `ref_id` bigint DEFAULT null,
  `créé_le` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `lots_travaux` (
  `id` varchar(255) PRIMARY KEY NOT NULL,
  `nom` varchar(255) NOT NULL,
  `travaux_total` int NOT NULL,
  `travaux_en_attente` int NOT NULL,
  `travaux_échoués` int NOT NULL,
  `ids_travaux_échoués` longtext NOT NULL,
  `options` mediumtext,
  `annulé_le` int DEFAULT null,
  `créé_le` int NOT NULL,
  `terminé_le` int DEFAULT null
);

CREATE TABLE `travaux` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `tentatives` tinyint NOT NULL,
  `réservé_le` int DEFAULT null,
  `disponible_le` int NOT NULL,
  `créé_le` int NOT NULL
);

CREATE TABLE `emplacements` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `libellé` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT null,
  `ville` varchar(120) DEFAULT null,
  `code_postal` varchar(20) DEFAULT null,
  `lat` decimal(9,6) DEFAULT null,
  `lng` decimal(9,6) DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `cartes_fidélité` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `utilisateur_id` bigint DEFAULT null,
  `code` varchar(255) NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `règles_fidélité` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `points_par_euro` decimal(6,2) NOT NULL DEFAULT '1.00',
  `taux_récompense` decimal(6,2) NOT NULL DEFAULT '100.00',
  `expire_après_mois` int DEFAULT null,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `transactions_fidélité` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `carte_fidélité_id` bigint NOT NULL,
  `type` ENUM ('gain', 'utilisation', 'ajustement') NOT NULL,
  `points` int NOT NULL,
  `commande_client_id` bigint DEFAULT null,
  `note` varchar(255) DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `enregistrements_maintenance` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `camion_id` bigint NOT NULL,
  `date_maintenance` date DEFAULT null,
  `description` text,
  `coût` decimal(8,2) DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `migrations` (
  `id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `lot` int NOT NULL
);

CREATE TABLE `envois_newsletters` (
  `newsletter_id` bigint NOT NULL,
  `utilisateur_id` bigint NOT NULL,
  `envoyé_le` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  PRIMARY KEY (`newsletter_id`, `utilisateur_id`)
);

CREATE TABLE `newsletters` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `sujet` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `planifiée_le` timestamp DEFAULT null,
  `envoyée_le` timestamp DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `lignes_commande` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `commande_client_id` bigint NOT NULL,
  `plat_id` bigint NOT NULL,
  `quantité` int NOT NULL,
  `prix` decimal(8,2) NOT NULL,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `jetons_réinit_motdepasse` (
  `email` varchar(255) PRIMARY KEY NOT NULL,
  `jeton` varchar(255) NOT NULL,
  `créé_le` timestamp DEFAULT null
);

CREATE TABLE `paiements` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `commande_client_id` bigint NOT NULL,
  `montant` decimal(12,2) NOT NULL,
  `méthode` ENUM ('carte', 'espèces', 'bon') NOT NULL DEFAULT 'carte',
  `réf_prestataire` varchar(100) DEFAULT null,
  `statut` ENUM ('en_attente', 'capturé', 'échoué', 'remboursé') NOT NULL DEFAULT 'en_attente',
  `capturé_le` timestamp DEFAULT null,
  `remboursé_le` timestamp DEFAULT null,
  `remboursement_parent_id` bigint DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null
);

CREATE TABLE `sessions` (
  `id` varchar(255) PRIMARY KEY NOT NULL,
  `utilisateur_id` bigint DEFAULT null,
  `adresse_ip` varchar(45) DEFAULT null,
  `agent_utilisateur` text,
  `payload` longtext NOT NULL,
  `dernière_activité` int NOT NULL
);

CREATE TABLE `lignes_commande_stock` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `commande_stock_id` bigint NOT NULL,
  `produit_id` bigint NOT NULL,
  `quantité` int NOT NULL,
  `prix_unitaire` decimal(12,2) DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `commandes_stock` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `camion_id` bigint NOT NULL,
  `entrepôt_id` bigint DEFAULT null,
  `fournisseur_id` bigint DEFAULT null,
  `statut` ENUM ('en_attente', 'validée', 'terminée', 'annulée') NOT NULL DEFAULT 'en_attente',
  `commandé_le` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `fournisseurs` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `siret` varchar(20) DEFAULT null,
  `email_contact` varchar(190) DEFAULT null,
  `phone` varchar(40) DEFAULT null,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `produits` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT null,
  `unité` varchar(255) DEFAULT null,
  `coût` decimal(8,2) DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `déploiements_camion` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `camion_id` bigint NOT NULL,
  `emplacement_id` bigint NOT NULL,
  `début_le` datetime NOT NULL,
  `fin_le` datetime DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE TABLE `camions` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `ulid` char(26) DEFAULT null,
  `franchise_id` bigint NOT NULL,
  `nom` varchar(255) NOT NULL,
  `immatriculation` varchar(255) DEFAULT null,
  `statut` ENUM ('actif', 'maintenance', 'inactif') NOT NULL DEFAULT 'actif',
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `supprimé_le` timestamp DEFAULT null
);

CREATE TABLE `utilisateurs` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_vérifié_le` timestamp DEFAULT null,
  `mot_de_passe` varchar(255) NOT NULL,
  `jeton_mémoire` varchar(100) DEFAULT null,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `rôle` varchar(255) NOT NULL DEFAULT 'franchise',
  `franchise_id` bigint DEFAULT null,
  `langue_préférée` varchar(5) DEFAULT null,
  `newsletter_opt_in` tinyint(1) NOT NULL DEFAULT '0'
);

CREATE TABLE `entrepôts` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `franchise_id` bigint NOT NULL,
  `emplacement` varchar(255) DEFAULT null,
  `nom` varchar(255) NOT NULL,
  `créé_le` timestamp DEFAULT null,
  `mis_à_jour_le` timestamp DEFAULT null,
  `ulid` char(26) DEFAULT null
);

CREATE UNIQUE INDEX `uk_commissions_periode` ON `commissions` (`franchisé_id`, `année_période`, `mois_période`);

CREATE UNIQUE INDEX `uk_kpi_période` ON `indicateurs_conformité` (`franchise_id`, `année_période`, `mois_période`);

CREATE UNIQUE INDEX `uk_commandes_ref` ON `commandes_clients` (`référence`);

CREATE UNIQUE INDEX `commandes_clients_ulid_unique` ON `commandes_clients` (`ulid`);

CREATE INDEX `commandes_clients_carte_fidelite_id_etrangere` ON `commandes_clients` (`carte_fidélité_id`);

CREATE INDEX `commandes_clients_emplacement_id_etrangere` ON `commandes_clients` (`emplacement_id`);

CREATE INDEX `commandes_clients_camion_id_commande_index` ON `commandes_clients` (`camion_id`, `commandé_le`);

CREATE INDEX `commandes_clients_client_id_etrangere` ON `commandes_clients` (`client_id`);

CREATE UNIQUE INDEX `uk_nomenclature` ON `ingrédients_plat` (`plat_id`, `produit_id`);

CREATE UNIQUE INDEX `ingredients_plat_ulid_unique` ON `ingrédients_plat` (`ulid`);

CREATE INDEX `ingredients_plat_produit_id_etrangere` ON `ingrédients_plat` (`produit_id`);

CREATE UNIQUE INDEX `uk_plat_locale` ON `traductions_plat` (`plat_id`, `locale`);

CREATE UNIQUE INDEX `plats_ulid_unique` ON `plats` (`ulid`);

CREATE UNIQUE INDEX `uk_evenement_camion` ON `inscriptions_événements` (`événement_id`, `camion_id`);

CREATE UNIQUE INDEX `inscriptions_evenements_ulid_unique` ON `inscriptions_événements` (`ulid`);

CREATE INDEX `inscriptions_evenements_camion_id_etrangere` ON `inscriptions_événements` (`camion_id`);

CREATE UNIQUE INDEX `travaux_echoues_uuid_unique` ON `travaux_échoués` (`uuid`);

CREATE UNIQUE INDEX `uk_franchises_nom` ON `franchises` (`nom`);

CREATE UNIQUE INDEX `franchises_ulid_unique` ON `franchises` (`ulid`);

CREATE UNIQUE INDEX `uk_inventaire` ON `inventaire` (`entrepôt_id`, `produit_id`);

CREATE UNIQUE INDEX `inventaire_ulid_unique` ON `inventaire` (`ulid`);

CREATE INDEX `inventaire_produit_id_etrangere` ON `inventaire` (`produit_id`);

CREATE INDEX `ix_inv_entrepot_produit` ON `inventaire` (`entrepôt_id`, `produit_id`);

CREATE INDEX `ajustements_inventaire_inventaire_id_etrangere` ON `ajustements_inventaire` (`inventaire_id`);

CREATE UNIQUE INDEX `uk_invlot` ON `lots_inventaire` (`inventaire_id`, `code_lot`);

CREATE UNIQUE INDEX `mouvements_inventaire_ulid_unique` ON `mouvements_inventaire` (`ulid`);

CREATE INDEX `mouvements_inventaire_inventaire_id_etrangere` ON `mouvements_inventaire` (`inventaire_id`);

CREATE INDEX `travaux_file_index` ON `travaux` (`file`);

CREATE UNIQUE INDEX `emplacements_ulid_unique` ON `emplacements` (`ulid`);

CREATE UNIQUE INDEX `cartes_fidelite_code_unique` ON `cartes_fidélité` (`code`);

CREATE INDEX `cartes_fidelite_utilisateur_id_etrangere` ON `cartes_fidélité` (`utilisateur_id`);

CREATE INDEX `transactions_fidelite_carte_fidelite_id_etrangere` ON `transactions_fidélité` (`carte_fidélité_id`);

CREATE INDEX `transactions_fidelite_commande_client_id_etrangere` ON `transactions_fidélité` (`commande_client_id`);

CREATE UNIQUE INDEX `enregistrements_maintenance_ulid_unique` ON `enregistrements_maintenance` (`ulid`);

CREATE INDEX `enregistrements_maintenance_camion_id_etrangere` ON `enregistrements_maintenance` (`camion_id`);

CREATE INDEX `envois_newsletters_utilisateur_id_etrangere` ON `envois_newsletters` (`utilisateur_id`);

CREATE UNIQUE INDEX `lignes_commande_ulid_unique` ON `lignes_commande` (`ulid`);

CREATE INDEX `lignes_commande_plat_id_etrangere` ON `lignes_commande` (`plat_id`);

CREATE INDEX `ix_lignes_commande_commande` ON `lignes_commande` (`commande_client_id`);

CREATE INDEX `paiements_commande_client_id_etrangere` ON `paiements` (`commande_client_id`);

CREATE INDEX `paiements_remboursement_parent_id_etrangere` ON `paiements` (`remboursement_parent_id`);

CREATE INDEX `sessions_utilisateur_id_index` ON `sessions` (`utilisateur_id`);

CREATE INDEX `sessions_derniere_activite_index` ON `sessions` (`dernière_activité`);

CREATE UNIQUE INDEX `lignes_commande_stock_ulid_unique` ON `lignes_commande_stock` (`ulid`);

CREATE INDEX `lignes_commande_stock_commande_stock_id_etrangere` ON `lignes_commande_stock` (`commande_stock_id`);

CREATE INDEX `lignes_commande_stock_produit_id_etrangere` ON `lignes_commande_stock` (`produit_id`);

CREATE UNIQUE INDEX `commandes_stock_ulid_unique` ON `commandes_stock` (`ulid`);

CREATE INDEX `commandes_stock_entrepot_id_etrangere` ON `commandes_stock` (`entrepôt_id`);

CREATE INDEX `commandes_stock_fournisseur_id_etrangere` ON `commandes_stock` (`fournisseur_id`);

CREATE INDEX `ix_cs_camion_cree` ON `commandes_stock` (`camion_id`, `créé_le`);

CREATE UNIQUE INDEX `fournisseurs_ulid_unique` ON `fournisseurs` (`ulid`);

CREATE UNIQUE INDEX `uk_fournisseurs_siret` ON `fournisseurs` (`siret`);

CREATE UNIQUE INDEX `uk_produits_sku` ON `produits` (`sku`);

CREATE UNIQUE INDEX `produits_ulid_unique` ON `produits` (`ulid`);

CREATE UNIQUE INDEX `deploiements_camion_ulid_unique` ON `déploiements_camion` (`ulid`);

CREATE INDEX `deploiements_camion_emplacement_id_etrangere` ON `déploiements_camion` (`emplacement_id`);

CREATE INDEX `ix_dc_plage` ON `déploiements_camion` (`camion_id`, `début_le`, `fin_le`);

CREATE UNIQUE INDEX `uk_camions_immatriculation` ON `camions` (`immatriculation`);

CREATE UNIQUE INDEX `camions_ulid_unique` ON `camions` (`ulid`);

CREATE INDEX `camions_franchise_id_etrangere` ON `camions` (`franchise_id`);

CREATE UNIQUE INDEX `utilisateurs_email_unique` ON `utilisateurs` (`email`);

CREATE INDEX `utilisateurs_franchise_id_etrangere` ON `utilisateurs` (`franchise_id`);

CREATE UNIQUE INDEX `entrepots_ulid_unique` ON `entrepôts` (`ulid`);

CREATE INDEX `entrepots_franchise_id_etrangere` ON `entrepôts` (`franchise_id`);

ALTER TABLE `commissions` ADD CONSTRAINT `commissions_franchisé_id_etrangere` FOREIGN KEY (`franchisé_id`) REFERENCES `utilisateurs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `indicateurs_conformité` ADD CONSTRAINT `indicateurs_conformité_franchise_id_etrangere` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE;

ALTER TABLE `commandes_clients` ADD CONSTRAINT `commandes_clients_client_id_etrangere` FOREIGN KEY (`client_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

ALTER TABLE `commandes_clients` ADD CONSTRAINT `commandes_clients_emplacement_id_etrangere` FOREIGN KEY (`emplacement_id`) REFERENCES `emplacements` (`id`) ON DELETE SET NULL;

ALTER TABLE `commandes_clients` ADD CONSTRAINT `commandes_clients_carte_fidelite_id_etrangere` FOREIGN KEY (`carte_fidélité_id`) REFERENCES `cartes_fidélité` (`id`) ON DELETE SET NULL;

ALTER TABLE `commandes_clients` ADD CONSTRAINT `commandes_clients_camion_id_etrangere` FOREIGN KEY (`camion_id`) REFERENCES `camions` (`id`) ON DELETE CASCADE;

ALTER TABLE `ingrédients_plat` ADD CONSTRAINT `ingredients_plat_plat_id_etrangere` FOREIGN KEY (`plat_id`) REFERENCES `plats` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `ingrédients_plat` ADD CONSTRAINT `ingredients_plat_produit_id_etrangere` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `traductions_plat` ADD CONSTRAINT `traductions_plat_plat_id_etrangere` FOREIGN KEY (`plat_id`) REFERENCES `plats` (`id`) ON DELETE CASCADE;

ALTER TABLE `inscriptions_événements` ADD CONSTRAINT `inscriptions_evenements_evenement_id_etrangere` FOREIGN KEY (`événement_id`) REFERENCES `événements` (`id`) ON DELETE CASCADE;

ALTER TABLE `inscriptions_événements` ADD CONSTRAINT `inscriptions_evenements_camion_id_etrangere` FOREIGN KEY (`camion_id`) REFERENCES `camions` (`id`) ON DELETE CASCADE;

ALTER TABLE `inventaire` ADD CONSTRAINT `inventaire_produit_id_etrangere` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `inventaire` ADD CONSTRAINT `inventaire_entrepot_id_etrangere` FOREIGN KEY (`entrepôt_id`) REFERENCES `entrepôts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `ajustements_inventaire` ADD CONSTRAINT `ajustements_inventaire_inventaire_id_etrangere` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaire` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `lots_inventaire` ADD CONSTRAINT `lots_inventaire_inventaire_id_etrangere` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaire` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `mouvements_inventaire` ADD CONSTRAINT `mouvements_inventaire_inventaire_id_etrangere` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaire` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `cartes_fidélité` ADD CONSTRAINT `cartes_fidelite_utilisateur_id_etrangere` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

ALTER TABLE `transactions_fidélité` ADD CONSTRAINT `transactions_fidelite_commande_client_id_etrangere` FOREIGN KEY (`commande_client_id`) REFERENCES `commandes_clients` (`id`) ON DELETE SET NULL;

ALTER TABLE `transactions_fidélité` ADD CONSTRAINT `transactions_fidelite_carte_fidelite_id_etrangere` FOREIGN KEY (`carte_fidélité_id`) REFERENCES `cartes_fidélité` (`id`) ON DELETE CASCADE;

ALTER TABLE `enregistrements_maintenance` ADD CONSTRAINT `enregistrements_maintenance_camion_id_etrangere` FOREIGN KEY (`camion_id`) REFERENCES `camions` (`id`) ON DELETE CASCADE;

ALTER TABLE `envois_newsletters` ADD CONSTRAINT `envois_newsletters_newsletter_id_etrangere` FOREIGN KEY (`newsletter_id`) REFERENCES `newsletters` (`id`) ON DELETE CASCADE;

ALTER TABLE `envois_newsletters` ADD CONSTRAINT `envois_newsletters_utilisateur_id_etrangere` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

ALTER TABLE `lignes_commande` ADD CONSTRAINT `lignes_commande_commande_client_id_etrangere` FOREIGN KEY (`commande_client_id`) REFERENCES `commandes_clients` (`id`) ON DELETE CASCADE;

ALTER TABLE `lignes_commande` ADD CONSTRAINT `lignes_commande_plat_id_etrangere` FOREIGN KEY (`plat_id`) REFERENCES `plats` (`id`) ON DELETE CASCADE;

ALTER TABLE `paiements` ADD CONSTRAINT `paiements_commande_client_id_etrangere` FOREIGN KEY (`commande_client_id`) REFERENCES `commandes_clients` (`id`) ON DELETE CASCADE;

ALTER TABLE `paiements` ADD CONSTRAINT `paiements_remboursement_parent_id_etrangere` FOREIGN KEY (`remboursement_parent_id`) REFERENCES `paiements` (`id`) ON DELETE SET NULL;

ALTER TABLE `sessions` ADD CONSTRAINT `fk_sessions_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

ALTER TABLE `lignes_commande_stock` ADD CONSTRAINT `lignes_commande_stock_commande_stock_id_etrangere` FOREIGN KEY (`commande_stock_id`) REFERENCES `commandes_stock` (`id`) ON DELETE CASCADE;

ALTER TABLE `lignes_commande_stock` ADD CONSTRAINT `lignes_commande_stock_produit_id_etrangere` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

ALTER TABLE `commandes_stock` ADD CONSTRAINT `commandes_stock_fournisseur_id_etrangere` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `commandes_stock` ADD CONSTRAINT `commandes_stock_camion_id_etrangere` FOREIGN KEY (`camion_id`) REFERENCES `camions` (`id`) ON DELETE CASCADE;

ALTER TABLE `commandes_stock` ADD CONSTRAINT `commandes_stock_entrepot_id_etrangere` FOREIGN KEY (`entrepôt_id`) REFERENCES `entrepôts` (`id`) ON DELETE CASCADE;

ALTER TABLE `déploiements_camion` ADD CONSTRAINT `deploiements_camion_emplacement_id_etrangere` FOREIGN KEY (`emplacement_id`) REFERENCES `emplacements` (`id`) ON DELETE CASCADE;

ALTER TABLE `déploiements_camion` ADD CONSTRAINT `deploiements_camion_camion_id_etrangere` FOREIGN KEY (`camion_id`) REFERENCES `camions` (`id`) ON DELETE CASCADE;

ALTER TABLE `camions` ADD CONSTRAINT `camions_franchise_id_etrangere` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE;

ALTER TABLE `utilisateurs` ADD CONSTRAINT `utilisateurs_franchise_id_etrangere` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE SET NULL;

ALTER TABLE `entrepôts` ADD CONSTRAINT `entrepots_franchise_id_etrangere` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE;

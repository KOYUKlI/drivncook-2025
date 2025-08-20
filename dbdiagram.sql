
Enum "commissions_statut_enum" {
  "en_attente"
  "payée"
  "annulée"
}

Enum "commandes_type_enum" {
  "en_ligne"
  "sur_place"
  "réservation"
}

Enum "commandes_statut_enum" {
  "en_attente"
  "confirmée"
  "préparation"
  "prête"
  "terminée"
  "annulée"
}

Enum "commandes_statut_paiement_enum" {
  "en_attente"
  "payé"
  "échoué"
  "remboursé"
}

Enum "ajustements_inventaire_raison_enum" {
  "gaspillage"
  "casse"
  "audit"
}

Enum "mouvements_inventaire_type_enum" {
  "entrée"
  "sortie"
  "transfert"
}

Enum "mouvements_inventaire_raison_enum" {
  "achat"
  "vente"
  "préparation"
  "gaspillage"
  "ajustement"
  "transfert"
}

Enum "transactions_fidelite_type_enum" {
  "gain"
  "utilisation"
  "ajustement"
}

Enum "paiements_methode_enum" {
  "carte"
  "espèces"
  "bon"
}

Enum "paiements_statut_enum" {
  "en_attente"
  "capturé"
  "échoué"
  "remboursé"
}

Enum "commandes_stock_statut_enum" {
  "en_attente"
  "validée"
  "terminée"
  "annulée"
}

Enum "camions_statut_enum" {
  "actif"
  "maintenance"
  "inactif"
}

Table "cache" {
  "clé" varchar(255) [pk, not null]
  "valeur" mediumtext [not null]
  "expiration" int [not null]
}

Table "verrous_cache" {
  "clé" varchar(255) [pk, not null]
  "propriétaire" varchar(255) [not null]
  "expiration" int [not null]
}

Table "commissions" {
  "id" bigint [pk, not null, increment]
  "franchisé_id" bigint [not null]
  "année_période" int [not null]
  "mois_période" tinyint [not null]
  "chiffre_affaires" decimal(12,2) [not null]
  "taux" decimal(5,2) [not null, default: '4.00']
  "montant_dû" decimal(12,2)
  "statut" commissions_statut_enum [not null, default: 'en_attente']
  "calculé_le" timestamp [not null]
  "payé_le" timestamp [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]

  Indexes {
    (franchisé_id, année_période, mois_période) [unique, name: "uk_commissions_periode"]
  }
}

Table "indicateurs_conformité" {
  "id" bigint [pk, not null, increment]
  "franchise_id" bigint [not null]
  "année_période" int [not null]
  "mois_période" tinyint [not null]
  "chiffre_affaires_externe" decimal(12,2) [not null, default: '0.00']
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]

  Indexes {
    (franchise_id, année_période, mois_période) [unique, name: "uk_kpi_période"]
  }
}

Table "commandes_clients" {
  "id" bigint [pk, not null, increment]
  "camion_id" bigint [not null]
  "client_id" bigint [default: NULL]
  "emplacement_id" bigint [default: NULL]
  "carte_fidélité_id" bigint [default: NULL]
  "type_commande" commandes_type_enum [not null, default: 'en_ligne']
  "statut" commandes_statut_enum [not null, default: 'en_attente']
  "statut_paiement" commandes_statut_paiement_enum [not null, default: 'en_attente']
  "référence" varchar(30) [default: NULL]
  "retrait_le" datetime [default: NULL]
  "prix_total" decimal(12,2) [not null]
  "commandé_le" timestamp [not null, default: `CURRENT_TIMESTAMP`]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    référence [unique, name: "uk_commandes_ref"]
    ulid [unique, name: "commandes_clients_ulid_unique"]
    carte_fidélité_id [name: "commandes_clients_carte_fidelite_id_etrangere"]
    emplacement_id [name: "commandes_clients_emplacement_id_etrangere"]
    (camion_id, commandé_le) [name: "commandes_clients_camion_id_commande_index"]
    client_id [name: "commandes_clients_client_id_etrangere"]
  }
}

Table "ingrédients_plat" {
  "id" bigint [pk, not null, increment]
  "plat_id" bigint [not null]
  "produit_id" bigint [not null]
  "qté_par_plat" decimal(12,3) [not null]
  "unité" varchar(20) [not null]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    (plat_id, produit_id) [unique, name: "uk_nomenclature"]
    ulid [unique, name: "ingredients_plat_ulid_unique"]
    produit_id [name: "ingredients_plat_produit_id_etrangere"]
  }
}

Table "traductions_plat" {
  "id" bigint [pk, not null, increment]
  "plat_id" bigint [not null]
  "locale" varchar(10) [not null]
  "nom" varchar(255) [not null]
  "description" text
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]

  Indexes {
    (plat_id, locale) [unique, name: "uk_plat_locale"]
  }
}

Table "plats" {
  "id" bigint [pk, not null, increment]
  "nom" varchar(255) [not null]
  "description" text
  "prix" decimal(8,2) [not null]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "plats_ulid_unique"]
  }
}

Table "inscriptions_événements" {
  "id" bigint [pk, not null, increment]
  "événement_id" bigint [not null]
  "camion_id" bigint [not null]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    (événement_id, camion_id) [unique, name: "uk_evenement_camion"]
    ulid [unique, name: "inscriptions_evenements_ulid_unique"]
    camion_id [name: "inscriptions_evenements_camion_id_etrangere"]
  }
}

Table "événements" {
  "id" bigint [pk, not null, increment]
  "nom" varchar(255) [not null]
  "date" date [default: NULL]
  "lieu" varchar(255) [default: NULL]
  "description" text
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
}

Table "travaux_échoués" {
  "id" bigint [pk, not null, increment]
  "uuid" varchar(255) [not null]
  "connexion" text [not null]
  "file" text [not null]
  "payload" longtext [not null]
  "exception" longtext [not null]
  "échoué_le" timestamp [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    uuid [unique, name: "travaux_echoues_uuid_unique"]
  }
}

Table "franchises" {
  "id" bigint [pk, not null, increment]
  "nom" varchar(255) [not null]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    nom [unique, name: "uk_franchises_nom"]
    ulid [unique, name: "franchises_ulid_unique"]
  }
}

Table "inventaire" {
  "id" bigint [pk, not null, increment]
  "entrepôt_id" bigint [not null]
  "produit_id" bigint [not null]
  "stock_disponible" decimal(12,3) [not null, default: '0.000']
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    (entrepôt_id, produit_id) [unique, name: "uk_inventaire"]
    ulid [unique, name: "inventaire_ulid_unique"]
    produit_id [name: "inventaire_produit_id_etrangere"]
    (entrepôt_id, produit_id) [name: "ix_inv_entrepot_produit"]
  }
}

Table "ajustements_inventaire" {
  "id" bigint [pk, not null, increment]
  "inventaire_id" bigint [not null]
  "écart_qté" decimal(12,3) [not null]
  "raison" ajustements_inventaire_raison_enum [not null]
  "note" varchar(255) [default: NULL]
  "créé_le" timestamp [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    inventaire_id [name: "ajustements_inventaire_inventaire_id_etrangere"]
  }
}

Table "lots_inventaire" {
  "id" bigint [pk, not null, increment]
  "inventaire_id" bigint [not null]
  "code_lot" varchar(64) [not null]
  "expire_le" date [default: NULL]
  "qté" decimal(12,3) [not null]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]

  Indexes {
    (inventaire_id, code_lot) [unique, name: "uk_invlot"]
  }
}

Table "mouvements_inventaire" {
  "id" bigint [pk, not null, increment]
  "inventaire_id" bigint [not null]
  "type" mouvements_inventaire_type_enum [not null]
  "qté" decimal(12,3) [not null]
  "raison" mouvements_inventaire_raison_enum [not null]
  "table_ref" varchar(40) [default: NULL]
  "ref_id" bigint [default: NULL]
  "créé_le" timestamp [not null, default: `CURRENT_TIMESTAMP`]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "mouvements_inventaire_ulid_unique"]
    inventaire_id [name: "mouvements_inventaire_inventaire_id_etrangere"]
  }
}

Table "lots_travaux" {
  "id" varchar(255) [pk, not null]
  "nom" varchar(255) [not null]
  "travaux_total" int [not null]
  "travaux_en_attente" int [not null]
  "travaux_échoués" int [not null]
  "ids_travaux_échoués" longtext [not null]
  "options" mediumtext
  "annulé_le" int [default: NULL]
  "créé_le" int [not null]
  "terminé_le" int [default: NULL]
}

Table "travaux" {
  "id" bigint [pk, not null, increment]
  "file" varchar(255) [not null]
  "payload" longtext [not null]
  "tentatives" tinyint [not null]
  "réservé_le" int [default: NULL]
  "disponible_le" int [not null]
  "créé_le" int [not null]

  Indexes {
    file [name: "travaux_file_index"]
  }
}

Table "emplacements" {
  "id" bigint [pk, not null, increment]
  "libellé" varchar(255) [not null]
  "adresse" varchar(255) [default: NULL]
  "ville" varchar(120) [default: NULL]
  "code_postal" varchar(20) [default: NULL]
  "lat" decimal(9,6) [default: NULL]
  "lng" decimal(9,6) [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "emplacements_ulid_unique"]
  }
}

Table "cartes_fidélité" {
  "id" bigint [pk, not null, increment]
  "utilisateur_id" bigint [default: NULL]
  "code" varchar(255) [not null]
  "points" int [not null, default: '0']
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]

  Indexes {
    code [unique, name: "cartes_fidelite_code_unique"]
    utilisateur_id [name: "cartes_fidelite_utilisateur_id_etrangere"]
  }
}

Table "règles_fidélité" {
  "id" bigint [pk, not null, increment]
  "points_par_euro" decimal(6,2) [not null, default: '1.00']
  "taux_récompense" decimal(6,2) [not null, default: '100.00']
  "expire_après_mois" int [default: NULL]
  "actif" tinyint(1) [not null, default: '1']
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
}

Table "transactions_fidélité" {
  "id" bigint [pk, not null, increment]
  "carte_fidélité_id" bigint [not null]
  "type" transactions_fidelite_type_enum [not null]
  "points" int [not null]
  "commande_client_id" bigint [default: NULL]
  "note" varchar(255) [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]

  Indexes {
    carte_fidélité_id [name: "transactions_fidelite_carte_fidelite_id_etrangere"]
    commande_client_id [name: "transactions_fidelite_commande_client_id_etrangere"]
  }
}

Table "enregistrements_maintenance" {
  "id" bigint [pk, not null, increment]
  "camion_id" bigint [not null]
  "date_maintenance" date [default: NULL]
  "description" text
  "coût" decimal(8,2) [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "enregistrements_maintenance_ulid_unique"]
    camion_id [name: "enregistrements_maintenance_camion_id_etrangere"]
  }
}

Table "migrations" {
  "id" int [pk, not null, increment]
  "migration" varchar(255) [not null]
  "lot" int [not null]
}

Table "envois_newsletters" {
  "newsletter_id" bigint [not null]
  "utilisateur_id" bigint [not null]
  "envoyé_le" timestamp [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    (newsletter_id, utilisateur_id) [pk]
    utilisateur_id [name: "envois_newsletters_utilisateur_id_etrangere"]
  }
}

Table "newsletters" {
  "id" bigint [pk, not null, increment]
  "sujet" varchar(255) [not null]
  "contenu" text [not null]
  "planifiée_le" timestamp [default: NULL]
  "envoyée_le" timestamp [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
}

Table "lignes_commande" {
  "id" bigint [pk, not null, increment]
  "commande_client_id" bigint [not null]
  "plat_id" bigint [not null]
  "quantité" int [not null]
  "prix" decimal(8,2) [not null]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "lignes_commande_ulid_unique"]
    plat_id [name: "lignes_commande_plat_id_etrangere"]
    commande_client_id [name: "ix_lignes_commande_commande"]
  }
}

Table "jetons_réinit_motdepasse" {
  "email" varchar(255) [pk, not null]
  "jeton" varchar(255) [not null]
  "créé_le" timestamp [default: NULL]
}

Table "paiements" {
  "id" bigint [pk, not null, increment]
  "commande_client_id" bigint [not null]
  "montant" decimal(12,2) [not null]
  "méthode" paiements_methode_enum [not null, default: 'carte']
  "réf_prestataire" varchar(100) [default: NULL]
  "statut" paiements_statut_enum [not null, default: 'en_attente']
  "capturé_le" timestamp [default: NULL]
  "remboursé_le" timestamp [default: NULL]
  "remboursement_parent_id" bigint [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]

  Indexes {
    commande_client_id [name: "paiements_commande_client_id_etrangere"]
    remboursement_parent_id [name: "paiements_remboursement_parent_id_etrangere"]
  }
}

Table "sessions" {
  "id" varchar(255) [pk, not null]
  "utilisateur_id" bigint [default: NULL]
  "adresse_ip" varchar(45) [default: NULL]
  "agent_utilisateur" text
  "payload" longtext [not null]
  "dernière_activité" int [not null]

  Indexes {
    utilisateur_id [name: "sessions_utilisateur_id_index"]
    dernière_activité [name: "sessions_derniere_activite_index"]
  }
}

Table "lignes_commande_stock" {
  "id" bigint [pk, not null, increment]
  "commande_stock_id" bigint [not null]
  "produit_id" bigint [not null]
  "quantité" int [not null]
  "prix_unitaire" decimal(12,2) [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "lignes_commande_stock_ulid_unique"]
    commande_stock_id [name: "lignes_commande_stock_commande_stock_id_etrangere"]
    produit_id [name: "lignes_commande_stock_produit_id_etrangere"]
  }
}

Table "commandes_stock" {
  "id" bigint [pk, not null, increment]
  "camion_id" bigint [not null]
  "entrepôt_id" bigint [default: NULL]
  "fournisseur_id" bigint [default: NULL]
  "statut" commandes_stock_statut_enum [not null, default: 'en_attente']
  "commandé_le" timestamp [not null, default: `CURRENT_TIMESTAMP`]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "commandes_stock_ulid_unique"]
    entrepôt_id [name: "commandes_stock_entrepot_id_etrangere"]
    fournisseur_id [name: "commandes_stock_fournisseur_id_etrangere"]
    (camion_id, créé_le) [name: "ix_cs_camion_cree"]
  }
}

Table "fournisseurs" {
  "id" bigint [pk, not null, increment]
  "nom" varchar(255) [not null]
  "siret" varchar(20) [default: NULL]
  "email_contact" varchar(190) [default: NULL]
  "phone" varchar(40) [default: NULL]
  "actif" tinyint(1) [not null, default: '1']
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "fournisseurs_ulid_unique"]
    siret [unique, name: "uk_fournisseurs_siret"]
  }
}

Table "produits" {
  "id" bigint [pk, not null, increment]
  "nom" varchar(255) [not null]
  "sku" varchar(255) [default: NULL]
  "unité" varchar(255) [default: NULL]
  "coût" decimal(8,2) [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    sku [unique, name: "uk_produits_sku"]
    ulid [unique, name: "produits_ulid_unique"]
  }
}

Table "déploiements_camion" {
  "id" bigint [pk, not null, increment]
  "camion_id" bigint [not null]
  "emplacement_id" bigint [not null]
  "début_le" datetime [not null]
  "fin_le" datetime [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "deploiements_camion_ulid_unique"]
    emplacement_id [name: "deploiements_camion_emplacement_id_etrangere"]
    (camion_id, début_le, fin_le) [name: "ix_dc_plage"]
  }
}

Table "camions" {
  "id" bigint [pk, not null, increment]
  "ulid" char(26) [default: NULL]
  "franchise_id" bigint [not null]
  "nom" varchar(255) [not null]
  "immatriculation" varchar(255) [default: NULL]
  "statut" camions_statut_enum [not null, default: 'actif']
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "supprimé_le" timestamp [default: NULL]

  Indexes {
    immatriculation [unique, name: "uk_camions_immatriculation"]
    ulid [unique, name: "camions_ulid_unique"]
    franchise_id [name: "camions_franchise_id_etrangere"]
  }
}

Table "utilisateurs" {
  "id" bigint [pk, not null, increment]
  "nom" varchar(255) [not null]
  "email" varchar(255) [not null]
  "email_vérifié_le" timestamp [default: NULL]
  "mot_de_passe" varchar(255) [not null]
  "jeton_mémoire" varchar(100) [default: NULL]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "rôle" varchar(255) [not null, default: 'franchise']
  "franchise_id" bigint [default: NULL]
  "langue_préférée" varchar(5) [default: NULL]
  "newsletter_opt_in" tinyint(1) [not null, default: '0']

  Indexes {
    email [unique, name: "utilisateurs_email_unique"]
    franchise_id [name: "utilisateurs_franchise_id_etrangere"]
  }
}

Table "entrepôts" {
  "id" bigint [pk, not null, increment]
  "franchise_id" bigint [not null]
  "emplacement" varchar(255) [default: NULL]
  "nom" varchar(255) [not null]
  "créé_le" timestamp [default: NULL]
  "mis_à_jour_le" timestamp [default: NULL]
  "ulid" char(26) [default: NULL]

  Indexes {
    ulid [unique, name: "entrepots_ulid_unique"]
    franchise_id [name: "entrepots_franchise_id_etrangere"]
  }
}

Ref "commissions_franchisé_id_etrangere":"utilisateurs"."id" < "commissions"."franchisé_id" [update: cascade, delete: restrict]

Ref "indicateurs_conformité_franchise_id_etrangere":"franchises"."id" < "indicateurs_conformité"."franchise_id" [delete: cascade]

Ref "commandes_clients_client_id_etrangere":"utilisateurs"."id" < "commandes_clients"."client_id" [delete: set null]

Ref "commandes_clients_emplacement_id_etrangere":"emplacements"."id" < "commandes_clients"."emplacement_id" [delete: set null]

Ref "commandes_clients_carte_fidelite_id_etrangere":"cartes_fidélité"."id" < "commandes_clients"."carte_fidélité_id" [delete: set null]

Ref "commandes_clients_camion_id_etrangere":"camions"."id" < "commandes_clients"."camion_id" [delete: cascade]

Ref "ingredients_plat_plat_id_etrangere":"plats"."id" < "ingrédients_plat"."plat_id" [update: cascade, delete: restrict]

Ref "ingredients_plat_produit_id_etrangere":"produits"."id" < "ingrédients_plat"."produit_id" [update: cascade, delete: restrict]

Ref "traductions_plat_plat_id_etrangere":"plats"."id" < "traductions_plat"."plat_id" [delete: cascade]

Ref "inscriptions_evenements_evenement_id_etrangere":"événements"."id" < "inscriptions_événements"."événement_id" [delete: cascade]

Ref "inscriptions_evenements_camion_id_etrangere":"camions"."id" < "inscriptions_événements"."camion_id" [delete: cascade]

Ref "inventaire_produit_id_etrangere":"produits"."id" < "inventaire"."produit_id" [update: cascade, delete: restrict]

Ref "inventaire_entrepot_id_etrangere":"entrepôts"."id" < "inventaire"."entrepôt_id" [update: cascade, delete: restrict]

Ref "ajustements_inventaire_inventaire_id_etrangere":"inventaire"."id" < "ajustements_inventaire"."inventaire_id" [update: cascade, delete: restrict]

Ref "lots_inventaire_inventaire_id_etrangere":"inventaire"."id" < "lots_inventaire"."inventaire_id" [update: cascade, delete: restrict]

Ref "mouvements_inventaire_inventaire_id_etrangere":"inventaire"."id" < "mouvements_inventaire"."inventaire_id" [update: cascade, delete: restrict]

Ref "cartes_fidelite_utilisateur_id_etrangere":"utilisateurs"."id" < "cartes_fidélité"."utilisateur_id" [delete: set null]

Ref "transactions_fidelite_commande_client_id_etrangere":"commandes_clients"."id" < "transactions_fidélité"."commande_client_id" [delete: set null]

Ref "transactions_fidelite_carte_fidelite_id_etrangere":"cartes_fidélité"."id" < "transactions_fidélité"."carte_fidélité_id" [delete: cascade]

Ref "enregistrements_maintenance_camion_id_etrangere":"camions"."id" < "enregistrements_maintenance"."camion_id" [delete: cascade]

Ref "envois_newsletters_newsletter_id_etrangere":"newsletters"."id" < "envois_newsletters"."newsletter_id" [delete: cascade]

Ref "envois_newsletters_utilisateur_id_etrangere":"utilisateurs"."id" < "envois_newsletters"."utilisateur_id" [delete: cascade]

Ref "lignes_commande_commande_client_id_etrangere":"commandes_clients"."id" < "lignes_commande"."commande_client_id" [delete: cascade]

Ref "lignes_commande_plat_id_etrangere":"plats"."id" < "lignes_commande"."plat_id" [delete: cascade]

Ref "paiements_commande_client_id_etrangere":"commandes_clients"."id" < "paiements"."commande_client_id" [delete: cascade]

Ref "paiements_remboursement_parent_id_etrangere":"paiements"."id" < "paiements"."remboursement_parent_id" [delete: set null]

Ref "fk_sessions_utilisateur":"utilisateurs"."id" < "sessions"."utilisateur_id" [delete: set null]

Ref "lignes_commande_stock_commande_stock_id_etrangere":"commandes_stock"."id" < "lignes_commande_stock"."commande_stock_id" [delete: cascade]

Ref "lignes_commande_stock_produit_id_etrangere":"produits"."id" < "lignes_commande_stock"."produit_id" [delete: cascade]

Ref "commandes_stock_fournisseur_id_etrangere":"fournisseurs"."id" < "commandes_stock"."fournisseur_id" [update: cascade, delete: restrict]

Ref "commandes_stock_camion_id_etrangere":"camions"."id" < "commandes_stock"."camion_id" [delete: cascade]

Ref "commandes_stock_entrepot_id_etrangere":"entrepôts"."id" < "commandes_stock"."entrepôt_id" [delete: cascade]

Ref "deploiements_camion_emplacement_id_etrangere":"emplacements"."id" < "déploiements_camion"."emplacement_id" [delete: cascade]

Ref "deploiements_camion_camion_id_etrangere":"camions"."id" < "déploiements_camion"."camion_id" [delete: cascade]

Ref "camions_franchise_id_etrangere":"franchises"."id" < "camions"."franchise_id" [delete: cascade]

Ref "utilisateurs_franchise_id_etrangere":"franchises"."id" < "utilisateurs"."franchise_id" [delete: set null]

Ref "entrepots_franchise_id_etrangere":"franchises"."id" < "entrepôts"."franchise_id" [delete: cascade]

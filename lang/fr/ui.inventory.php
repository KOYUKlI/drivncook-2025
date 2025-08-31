<?php

return [
    // ... Existing translations ...
    
    // === INVENTORY SYSTEM ===
    'inventory' => [
        'title' => 'Inventaire des entrepôts',
        'title_for_warehouse' => 'Inventaire de l\'entrepôt :warehouse',
        'create_movement' => 'Nouveau mouvement',
        'warehouse' => 'Entrepôt',
        'stock_item' => 'Article',
        'quantity' => 'Quantité',
        'threshold' => 'Seuil',
        'status' => 'Statut',
        'last_movement' => 'Dernier mouvement',
        'add_movement' => 'Ajouter un mouvement',
        'low_stock' => 'Stock bas',
        'in_stock' => 'En stock',
        'filter_by_warehouse' => 'Filtrer par entrepôt',
        'filter_by_stock_item' => 'Filtrer par article',
        'show_low_stock' => 'Afficher uniquement les stocks bas',
        'reason' => 'Motif',
        'adjustment_type' => 'Type d\'ajustement',
        'increase' => 'Augmentation',
        'decrease' => 'Diminution',
        'source_warehouse' => 'Entrepôt source',
        'destination_warehouse' => 'Entrepôt destination',
        'transfer_to' => 'Transfert vers entrepôt :warehouse',
        'transfer_from' => 'Transfert depuis entrepôt :warehouse',
        
        'stock_movements' => [
            'create_title' => 'Créer un mouvement de stock',
            'receipt' => 'Réception',
            'withdrawal' => 'Retrait',
            'adjustment' => 'Ajustement',
            'transfer' => 'Transfert',
        ],
        
        'movement_types' => [
            'receipt' => 'Réception',
            'withdrawal' => 'Retrait',
            'adjustment' => 'Ajustement',
            'transfer_in' => 'Transfert entrant',
            'transfer_out' => 'Transfert sortant',
        ],
        
        'flash' => [
            'receipt_success' => 'Réception enregistrée avec succès',
            'withdrawal_success' => 'Retrait enregistré avec succès',
            'adjustment_success' => 'Ajustement enregistré avec succès',
            'transfer_success' => 'Transfert effectué avec succès',
        ],
        
        'errors' => [
            'insufficient_stock' => 'Stock insuffisant pour cette opération',
        ],
    ],
    
    // Update common keys
    'common' => [
        'manage_truck_fleet' => 'Gérer la flotte',
        'manage_applications' => 'Gérer les candidatures',
        'manage_franchisees' => 'Gérer les franchisés',
        'manage_warehouses' => 'Gérer les entrepôts',
        'manage_stock_items' => 'Gérer les articles',
        'manage_purchase_orders' => 'Gérer les commandes',
        'view_reports' => 'Voir les rapports',
        'add_new' => 'Ajouter nouveau',
        'last_updated' => 'Dernière mise à jour',
        'created_by' => 'Créé par',
        'updated_by' => 'Modifié par',
        'no_items_found' => 'Aucun élément trouvé',
        'items_per_page' => 'éléments par page',
        'filter_by' => 'Filtrer par',
        'sort_by' => 'Trier par',
        'search_placeholder' => 'Rechercher...',
        'select_option' => 'Sélectionner une option',
        'upload_file' => 'Télécharger un fichier',
        'download_file' => 'Télécharger le fichier',
        'file_uploaded' => 'Fichier téléchargé',
        'file_download' => 'Téléchargement de fichier',
        'save' => 'Enregistrer',
        'cancel' => 'Annuler',
        'unassigned' => 'Non attribué',
        'back' => 'Retour',
        'filter' => 'Filtrer',
        'create' => 'Créer',
        'no_records' => 'Aucun enregistrement trouvé',
        'all_warehouses' => 'Tous les entrepôts',
        'all_stock_items' => 'Tous les articles',
        'actions' => 'Actions',
    ],
];

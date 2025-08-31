<?php

return [
    // Inventory Dashboard Translations
    'inventory' => [
        'view_inventory' => 'Voir l\'inventaire',
        'back_to_warehouses' => 'Retour aux entrepôts',
        'dashboard' => [
            'title' => 'Tableau de bord :warehouse',
            'menu_title' => 'Tableau de bord',
            'kpis' => [
                'active_items' => 'Articles actifs',
                'low_stock' => 'Articles en stock faible',
                'movements_7days' => 'Mouvements (7j)',
                'movements_30days' => 'Mouvements (30j)',
                'po_received_30days' => 'Commandes reçues (30j)'
            ],
            'recent_movements' => 'Mouvements de stock récents',
            'no_movements' => 'Aucun mouvement trouvé pour la période sélectionnée',
            'filters' => [
                'from_date' => 'Date de début',
                'to_date' => 'Date de fin',
                'movement_type' => 'Type de mouvement',
                'all_types' => 'Tous les types',
                'apply' => 'Appliquer les filtres',
                'reset' => 'Réinitialiser'
            ],
            'table' => [
                'date' => 'Date',
                'type' => 'Type',
                'item' => 'Article',
                'quantity' => 'Quantité',
                'user' => 'Utilisateur',
                'reason' => 'Raison'
            ],
            'export' => [
                'button' => 'Exporter en CSV',
                'date' => 'Date',
                'type' => 'Type',
                'item' => 'Article',
                'quantity' => 'Quantité',
                'user' => 'Utilisateur',
                'reason' => 'Raison'
            ]
        ],
        'movement_types' => [
            'receipt' => 'Réception',
            'withdrawal' => 'Retrait',
            'adjustment' => 'Ajustement',
            'transfer_in' => 'Transfert entrant',
            'transfer_out' => 'Transfert sortant'
        ]
    ]
];
